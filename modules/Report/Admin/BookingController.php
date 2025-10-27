<?php

namespace Modules\Report\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\AdminController;
use Modules\Booking\Emails\NewBookingEmail;
use Modules\Booking\Events\BookingUpdatedEvent;
use Modules\Booking\Models\Booking;

class BookingController extends AdminController
{
    public function __construct()
    {
        $this->setActiveMenu(route('report.admin.booking'));
    }

    public function index(Request $request)
    {
        $this->checkPermission('booking_view');
        $query = Booking::where('status', '!=', 'draft');

        // Advanced Filters

        // Order Number
        if (!empty($request->order_number)) {
            $query->where(function ($q) use ($request) {
                $q->where('code', 'like', '%' . $request->order_number . '%')
                    ->orWhere('id', $request->order_number);
            });
        }

        // Date Range (Created Date)
        if (!empty($request->from_date)) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if (!empty($request->to_date)) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Schedule Range (Booking Dates)
        if (!empty($request->schedule_from)) {
            $query->whereDate('start_date', '>=', $request->schedule_from);
        }
        if (!empty($request->schedule_to)) {
            $query->whereDate('end_date', '<=', $request->schedule_to);
        }

        // Order Status
        if (!empty($request->order_status)) {
            $query->where('status', $request->order_status);
        }

        // Payment Gateway
        if (!empty($request->gateway)) {
            $query->where('gateway', $request->gateway);
        }

        // Payment ID
        if (!empty($request->payment_id)) {
            $query->where('payment_id', $request->payment_id);
        }

        // Phone Number
        if (!empty($request->phone_number)) {
            $query->where('phone', 'like', '%' . $request->phone_number . '%');
        }

        // Activity (Service Title) - Search across all service types
        if (!empty($request->activity)) {
            $searchTerm = '%' . $request->activity . '%';
            $bookableServices = get_bookable_services();

            $query->where(function ($q) use ($searchTerm, $bookableServices) {
                foreach ($bookableServices as $objectModel => $serviceClass) {
                    // Get table name from service class
                    $tableName = (new $serviceClass)->getTable();

                    $q->orWhere(function ($subQuery) use ($objectModel, $tableName, $searchTerm) {
                        $subQuery->where('bravo_bookings.object_model', $objectModel)
                            ->whereIn('bravo_bookings.object_id', function ($query) use ($tableName, $searchTerm) {
                                $query->select('id')
                                    ->from($tableName)
                                    ->where('title', 'like', $searchTerm);
                            });
                    });
                }
            });
        }

        // Note (Customer Notes)
        if (!empty($request->note)) {
            $query->where('customer_notes', 'like', '%' . $request->note . '%');
        }

        // Reservation (Code or ID)
        if (!empty($request->reservation)) {
            $query->where(function ($q) use ($request) {
                $q->where('code', 'like', '%' . $request->reservation . '%')
                    ->orWhere('id', $request->reservation);
            });
        }

        // Confirm Type
        if (!empty($request->confirm_type)) {
            $query->where('confirm_type', $request->confirm_type);
        }

        // Salesman
        if (!empty($request->salesman_id)) {
            $query->where('salesman_id', $request->salesman_id);
        }

        // Legacy search
        if (!empty($request->s)) {
            if (is_numeric($request->s)) {
                $query->Where('id', '=', $request->s);
            } else {
                $query->where(function ($query) use ($request) {
                    $query->where('first_name', 'like', '%' . $request->s . '%')
                        ->orWhere('last_name', 'like', '%' . $request->s . '%')
                        ->orWhere('email', 'like', '%' . $request->s . '%')
                        ->orWhere('phone', 'like', '%' . $request->s . '%')
                        ->orWhere('address', 'like', '%' . $request->s . '%')
                        ->orWhere('address2', 'like', '%' . $request->s . '%');
                });
            }
        }

        // Vendor Filter
        if ($this->hasPermission('booking_manage_others')) {
            if (!empty($request->vendor_id)) {
                $query->where('vendor_id', $request->vendor_id);
            }
        } else {
            $query->where('vendor_id', Auth::id());
        }

        $query->whereIn('object_model', array_keys(get_bookable_services()));

        // Group by payment_id to avoid duplicate rows for cart orders
        // For orders with payment_id, show only the first booking (lowest ID)
        $query->whereRaw('(payment_id IS NULL OR payment_id = "" OR id IN (
            SELECT MIN(id) FROM bravo_bookings 
            WHERE payment_id IS NOT NULL AND payment_id != "" 
            GROUP BY payment_id
        ))');

        // Sorting
        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Get payment gateways for filter dropdown
        $gateways = get_payment_gateways();
        $availableGateways = [];
        foreach ($gateways as $key => $gateway) {
            if (class_exists($gateway)) {
                $obj = new $gateway($key);
                if ($obj->isAvailable()) {
                    $availableGateways[$key] = $obj->getDisplayName();
                }
            }
        }

        // Get salesmen (users with Sales role only)
        $salesmen = \App\User::where('status', 'publish')
            ->where('role_id', 4) // Sales role (seals)
            ->select('id', 'first_name', 'last_name')
            ->orderBy('first_name')
            ->get()
            ->mapWithKeys(function ($user) {
                return [$user->id => $user->first_name . ' ' . $user->last_name];
            });

        // Confirm types
        $confirmTypes = [
            'confirmed' => __('Confirmed'),
            'not_confirmed' => __('Not Confirmed'),
            'pending' => __('Pending'),
        ];

        $data = [
            'rows' => $query->paginate(20),
            'page_title' => __('Sales'),
            'booking_manage_others' => $this->hasPermission('booking_manage_others'),
            'booking_update' => $this->hasPermission('booking_update'),
            'statues' => config('booking.statuses'),
            'gateways' => $availableGateways,
            'salesmen' => $salesmen,
            'confirmTypes' => $confirmTypes,
            'filters' => $request->all(),
        ];

        return view('Report::admin.booking.index', $data);
    }

    public function bulkEdit(Request $request)
    {
        $ids = $request->input('ids');
        $action = $request->input('action');
        if (empty($ids) or !is_array($ids)) {
            return redirect()->back()->with('error', __('No items selected'));
        }
        if (empty($action)) {
            return redirect()->back()->with('error', __('Please select action'));
        }
        if ($action == 'delete') {
            foreach ($ids as $id) {
                $query = Booking::where('id', $id);
                if (!$this->hasPermission('booking_manage_others')) {
                    $query->where('vendor_id', Auth::id());
                }
                $row = $query->first();
                if (!empty($row)) {
                    $row->delete();
                    event(new BookingUpdatedEvent($row));

                }
            }
        } else {
            foreach ($ids as $id) {
                $query = Booking::where('id', $id);
                if (!$this->hasPermission('booking_manage_others')) {
                    $query->where('vendor_id', Auth::id());
                    $this->checkPermission('booking_update');
                }
                $item = $query->first();
                if (!empty($item)) {
                    $item->status = $action;
                    $item->save();

                    if ($action == Booking::CANCELLED) {
                        $item->tryRefundToWallet();
                    }
                    event(new BookingUpdatedEvent($item));
                }
            }
        }

        return redirect()->back()->with('success', __('Update success'));
    }

    public function email_preview(Request $request, $id)
    {
        $booking = Booking::find($id);

        return (new NewBookingEmail($booking))->render();
    }

    public function addNote(Request $request)
    {
        $this->checkPermission('booking_view');

        $request->validate([
            'booking_id' => 'required|exists:bravo_bookings,id',
            'note' => 'required|string|max:5000',
            'attachments.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt',
        ]);

        try {
            // Handle file uploads
            $attachmentPaths = [];
            if ($request->hasFile('attachments')) {
                $bookingId = $request->booking_id;
                $uploadPath = "booking_notes/{$bookingId}";

                foreach ($request->file('attachments') as $file) {
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '_' . uniqid() . '.' . $extension;

                    // Store file
                    $path = $file->storeAs($uploadPath, $filename, 'public');

                    $attachmentPaths[] = [
                        'path' => $path,
                        'original_name' => $originalName,
                        'size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                    ];
                }
            }

            $note = \Modules\Booking\Models\BookingNote::create([
                'booking_id' => $request->booking_id,
                'user_id' => Auth::id(),
                'note' => $request->note,
                'attachments' => !empty($attachmentPaths) ? $attachmentPaths : null,
            ]);

            // Get booking
            $booking = \Modules\Booking\Models\Booking::find($request->booking_id);

            // Extract mentions from note (@username)
            preg_match_all('/@([^\s]+)/', $request->note, $matches);
            if (!empty($matches[1])) {
                $mentionedUsernames = $matches[1];

                // Find users by display name and send notifications
                foreach ($mentionedUsernames as $username) {
                    // Search for user by first name, last name, or business name
                    $users = \App\User::where(function ($query) use ($username) {
                        $query->whereRaw("CONCAT(first_name, ' ', last_name) = ?", [$username])
                            ->orWhere('business_name', $username)
                            ->orWhereRaw("CONCAT(first_name, ' ', last_name, ' (#', id, ')') = ?", [$username]);
                    })
                        ->whereHas('role', function ($q) {
                            $q->whereNotIn('name', ['customer', 'vendor']);
                        })
                        ->get();

                    // Send notification to each mentioned user
                    foreach ($users as $user) {
                        if ($user->id !== Auth::id()) {
                            // Don't notify the user who created the mention
                            $user->notify(new \Modules\Booking\Notifications\BookingNoteMentionNotification(
                                $booking,
                                $note,
                                Auth::user()
                            ));
                        }
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => __('Note added successfully'),
                'note' => $note,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('Failed to add note: ') . $e->getMessage(),
            ], 500);
        }
    }

    public function getNotes(Request $request)
    {
        $this->checkPermission('booking_view');

        \Log::info('getNotes called with data:', $request->all());

        $request->validate([
            'booking_id' => 'required|exists:bravo_bookings,id',
        ]);

        try {
            $bookingId = $request->booking_id;
            \Log::info('Looking for notes for booking ID: ' . $bookingId);

            $notes = \Modules\Booking\Models\BookingNote::where('booking_id', $bookingId)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();

            \Log::info('Found ' . $notes->count() . ' notes for booking ' . $bookingId);

            $formattedNotes = $notes->map(function ($note) {
                $attachments = [];
                if ($note->attachments && is_array($note->attachments)) {
                    foreach ($note->attachments as $attachment) {
                        $attachments[] = [
                            'path' => $attachment['path'],
                            'url' => asset('storage/' . $attachment['path']),
                            'original_name' => $attachment['original_name'],
                            'size' => $attachment['size'],
                            'mime_type' => $attachment['mime_type'],
                            'extension' => pathinfo($attachment['original_name'], PATHINFO_EXTENSION),
                        ];
                    }
                }

                return [
                    'id' => $note->id,
                    'content' => $note->note,
                    'user_name' => $note->user ? $note->user->getDisplayName() : __('Unknown User'),
                    'user_avatar' => $note->user ? $note->user->getAvatarUrl() : null,
                    'created_at' => $note->created_at->format('d M Y, h:i A'),
                    'attachments' => $attachments,
                ];
            });

            return response()->json([
                'success' => true,
                'notes' => $formattedNotes,
                'count' => $notes->count(),
                'booking_id' => $bookingId,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getNotes: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => __('Failed to load notes: ') . $e->getMessage(),
            ], 500);
        }
    }

    public function confirmOrder(Request $request)
    {
        $this->checkPermission('booking_update');

        $request->validate([
            'booking_id' => 'required|exists:bravo_bookings,id',
            'has_ticket' => 'required|boolean',
            'send_method' => 'required|in:whatsapp,email',
            'ticket_file' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf',
        ]);

        try {
            $booking = \Modules\Booking\Models\Booking::find($request->booking_id);

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => __('Booking not found'),
                ], 404);
            }

            // Check if booking is already confirmed
            if ($booking->confirm_type === 'confirmed') {
                return response()->json([
                    'success' => false,
                    'message' => __('This order is already confirmed'),
                ], 400);
            }

            // Handle ticket file upload
            $ticketPath = null;
            if ($request->has_ticket && $request->hasFile('ticket_file')) {
                $file = $request->file('ticket_file');
                $bookingId = $booking->id;
                $uploadPath = "booking_tickets/{$bookingId}";

                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $filename = 'ticket_' . time() . '_' . uniqid() . '.' . $extension;

                // Store file
                $ticketPath = $file->storeAs($uploadPath, $filename, 'public');
            }

            // Update booking
            $booking->update([
                'confirm_type' => 'confirmed',
                'confirmation_method' => $request->send_method,
                'confirmed_at' => now(),
                'salesman_id' => Auth::id(),
            ]);

            // Create confirmation note
            $noteText = __('Order confirmed by :user', ['user' => Auth::user()->getDisplayName()]);

            if ($request->has_ticket) {
                $noteText .= "\n" . __('Customer will receive ticket via :method', [
                    'method' => $request->send_method === 'whatsapp' ? 'WhatsApp' : 'Email',
                ]);
            }

            $noteData = [
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'note' => $noteText,
            ];

            // Add ticket attachment to note if uploaded
            if ($ticketPath) {
                $noteData['attachments'] = [
                    [
                        'path' => $ticketPath,
                        'original_name' => $originalName,
                        'size' => $request->file('ticket_file')->getSize(),
                        'mime_type' => $request->file('ticket_file')->getMimeType(),
                    ],
                ];
            }

            \Modules\Booking\Models\BookingNote::create($noteData);

            // Prepare response data
            $responseData = [
                'success' => true,
                'message' => __('Order confirmed successfully! You are now assigned as the salesman.'),
            ];

            // Handle send method
            if ($request->send_method === 'whatsapp') {
                // Prepare WhatsApp message
                $message = $this->prepareWhatsAppMessage($booking, $ticketPath);
                $phone = $this->formatPhoneForWhatsApp($booking->phone);

                if ($phone) {
                    // Ensure proper UTF-8 encoding for emojis
                    $encodedMessage = rawurlencode(mb_convert_encoding($message, 'UTF-8', 'UTF-8'));
                    $whatsappUrl = "https://wa.me/{$phone}?text={$encodedMessage}";
                    $responseData['whatsapp_url'] = $whatsappUrl;
                    $responseData['message'] .= ' ' . __('WhatsApp will open to send details to customer.');
                } else {
                    $responseData['message'] .= ' ' . __('Warning: Customer phone number not found for WhatsApp.');
                }
            } elseif ($request->send_method === 'email') {
                // Send email
                $this->sendConfirmationEmail($booking, $ticketPath);
                $responseData['message'] .= ' ' . __('Confirmation email sent to customer.');
            }

            return response()->json($responseData);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('Failed to confirm order: ') . $e->getMessage(),
            ], 500);
        }
    }

    private function prepareWhatsAppMessage($booking, $ticketPath = null)
    {
        try {
            $orderNumber = $booking->code ?: $booking->id;
            $customerName = trim(($booking->first_name ?? '') . ' ' . ($booking->last_name ?? '')) ?: __('Customer');

            // Get all bookings with same payment_id (cart items)
            $relatedBookings = collect([$booking]); // Default to current booking

            if ($booking->payment_id) {
                try {
                    $cartBookings = \Modules\Booking\Models\Booking::where('payment_id', $booking->payment_id)
                        ->orderBy('id')
                        ->get();

                    if (!$cartBookings->isEmpty()) {
                        $relatedBookings = $cartBookings;
                    }
                } catch (\Exception $e) {
                    // Fallback to current booking if query fails
                    $relatedBookings = collect([$booking]);
                }
            }

            // Calculate total amount for all bookings
            $totalAmount = $relatedBookings->sum('total');

            $message = "Hello *{$customerName}*! \n\n";
            $message .= "*Your* *booking* *has* *been* *confirmed* *by* *Hallajoin*! \n";
            $message .= "*Order*: {$orderNumber}\n\n";

            foreach ($relatedBookings as $index => $relBooking) {
                // Get service name safely
                $serviceName = __('Unknown Service');
                try {
                    if ($relBooking->service && $relBooking->service->title) {
                        $serviceName = $relBooking->service->title;
                    }
                } catch (\Exception $e) {
                    // Service relationship failed, use default name
                    $serviceName = __('Booking Service');
                }

                $serviceAmount = format_money_simple($relBooking->total);

                $message .= "   *Service*: {$serviceName}\n";

                // Add date if available
                if ($relBooking->start_date) {
                    $message .= '   *Date*: ' . display_date($relBooking->start_date) . "\n";
                }

                // Add guest info if available
                $personTypes = $relBooking->getMeta('person_types');
                if ($personTypes) {
                    $personTypes = json_decode($personTypes, true);
                    if (is_array($personTypes) && !empty($personTypes)) {
                        $adults = 0;
                        $children = 0;
                        foreach ($personTypes as $type) {
                            if (isset($type['number']) && $type['number'] > 0) {
                                $typeName = strtolower($type['name'] ?? 'guest');
                                if (strpos($typeName, 'adult') !== false) {
                                    $adults += $type['number'];
                                } elseif (strpos($typeName, 'child') !== false) {
                                    $children += $type['number'];
                                } else {
                                    $adults += $type['number']; // Default to adults
                                }
                            }
                        }

                        if ($adults > 0) {
                            $message .= "   *Adults*: {$adults}\n";
                        }
                        if ($children > 0) {
                            $message .= "   *Children*: {$children}\n";
                        }
                    }
                }

                $message .= "   *Service* *Total*: {$serviceAmount}\n\n";
            }

            $message .= '*Services* *Total*: *' . format_money_simple($totalAmount) . "*\n\n";

            if ($ticketPath) {
                $ticketUrl = asset('storage/' . $ticketPath);
                $message .= "🎫 *Your* *ticket*: {$ticketUrl}\n\n";
            }

            $message .= "Thanks for choosing Hallajoin! ✨\n";
            $message .= "*Enjoy* *your* *visit* *and* *have* *an* *amazing* *experience!* ☺\n\n";
            $message .= '🔔 Join our Telegram group for the latest offers: hallajoin';

            return $message;

        } catch (\Exception $e) {
            // Fallback message if anything fails
            return __('Hello! Your booking has been confirmed. Order: :order', [
                'order' => $booking->code ?: $booking->id,
            ]);
        }
    }

    private function formatPhoneForWhatsApp($phone)
    {
        if (!$phone) {
            return null;
        }

        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Add country code if not present (assuming Egypt +20)
        if (strlen($phone) === 10 && !str_starts_with($phone, '20')) {
            $phone = '20' . $phone;
        } elseif (strlen($phone) === 11 && str_starts_with($phone, '0')) {
            $phone = '20' . substr($phone, 1);
        }

        return $phone;
    }

    private function sendConfirmationEmail($booking, $ticketPath = null)
    {
        // This would integrate with your email system
        // For now, we'll just log it or you can implement according to your email setup

        try {
            $customerEmail = $booking->email;
            if (!$customerEmail) {
                throw new \Exception('Customer email not found');
            }

            // Get all bookings with same payment_id (cart items)
            $relatedBookings = \Modules\Booking\Models\Booking::where('payment_id', $booking->payment_id)
                ->where('payment_id', '!=', null)
                ->with('service')
                ->orderBy('id')
                ->get();

            // If no related bookings found, fallback to current booking
            if ($relatedBookings->isEmpty()) {
                $relatedBookings = collect([$booking]);
            }

            // Prepare email data
            $emailData = [
                'booking' => $booking,
                'relatedBookings' => $relatedBookings,
                'totalAmount' => $relatedBookings->sum('total'),
                'ticketPath' => $ticketPath,
                'ticketUrl' => $ticketPath ? asset('storage/' . $ticketPath) : null,
            ];

            // You can implement your email sending logic here
            // Mail::to($customerEmail)->send(new BookingConfirmationMail($emailData));

            // For now, just log the action with more details
            \Log::info('Booking confirmation email would be sent', [
                'booking_id' => $booking->id,
                'customer_email' => $customerEmail,
                'total_activities' => $relatedBookings->count(),
                'total_amount' => $relatedBookings->sum('total'),
                'has_ticket' => !empty($ticketPath),
                'activities' => $relatedBookings->map(function ($b) {
                    return $b->service ? $b->service->title : 'Unknown Service';
                })->toArray(),
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to send confirmation email', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function getCustomerInfo(Request $request)
    {
        $this->checkPermission('booking_view');

        $request->validate([
            'booking_id' => 'required|exists:bravo_bookings,id',
        ]);

        try {
            $booking = \Modules\Booking\Models\Booking::find($request->booking_id);

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => __('Booking not found'),
                ], 404);
            }

            return response()->json([
                'success' => true,
                'customer' => [
                    'name' => $booking->first_name . ' ' . $booking->last_name,
                    'email' => $booking->email,
                    'phone' => $booking->phone,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('Failed to get customer info: ') . $e->getMessage(),
            ], 500);
        }
    }

    public function makePending(Request $request)
    {
        $this->checkPermission('booking_update');

        $request->validate([
            'booking_id' => 'required|exists:bravo_bookings,id',
        ]);

        try {
            $booking = \Modules\Booking\Models\Booking::find($request->booking_id);

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => __('Booking not found'),
                ], 404);
            }

            // Check if booking is already pending
            if ($booking->confirm_type === 'pending' || !$booking->confirm_type) {
                return response()->json([
                    'success' => false,
                    'message' => __('This order is already pending'),
                ], 400);
            }

            // Store previous confirmation info for note
            $previousMethod = $booking->confirmation_method;
            $previousConfirmedAt = $booking->confirmed_at;

            // Update booking to pending (but keep confirmation method and timestamp for history)
            $booking->update([
                'confirm_type' => 'pending',
                // Keep confirmation_method and confirmed_at for history
                // 'confirmation_method' => null,
                // 'confirmed_at' => null,
            ]);

            // Create note about status change
            $noteText = __('Order status changed to pending by :user', ['user' => Auth::user()->getDisplayName()]);

            if ($previousMethod) {
                $methodName = $previousMethod === 'whatsapp' ? 'WhatsApp' : 'Email';
                $noteText .= "\n" . __('Previously confirmed via :method on :date', [
                    'method' => $methodName,
                    'date' => $previousConfirmedAt ? date('d/m/Y H:i', strtotime($previousConfirmedAt)) : 'N/A',
                ]);
            }

            \Modules\Booking\Models\BookingNote::create([
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'note' => $noteText,
            ]);

            return response()->json([
                'success' => true,
                'message' => __('Order status changed to pending successfully'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('Failed to change order status: ') . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show Create Order Page
     */
    public function createOrder()
    {
        $this->checkPermission('booking_update');

        $data = [
            'page_title' => __('Create Order'),
            'breadcrumbs' => [
                ['name' => __('Sales'), 'url' => route('report.admin.booking')],
                ['name' => __('Create Order'), 'class' => 'active'],
            ],
        ];

        return view('Report::admin.booking.create', $data);
    }

    /**
     * Search Activities (Tours, Hotels, etc.)
     */
    public function searchActivities(Request $request)
    {
        $searchTerm = $request->input('search', '');
        $results = [];

        // Get all bookable services
        $bookableServices = get_bookable_services();

        foreach ($bookableServices as $objectModel => $serviceClass) {
            if (class_exists($serviceClass)) {
                $query = $serviceClass::query()
                    ->where('status', 'publish')
                    ->where('title', 'like', '%' . $searchTerm . '%')
                    ->limit(10);

                // Eager load category relationship if exists
                if (method_exists($serviceClass, 'category_tour')) {
                    $query->with('category_tour');
                }

                $services = $query->get();

                foreach ($services as $service) {
                    // Get category name
                    $categoryName = null;

                    // Try different relationship names
                    if (isset($service->category_tour) && $service->category_tour) {
                        $categoryName = $service->category_tour->name ?? null;
                    } elseif (isset($service->category) && $service->category) {
                        $categoryName = $service->category->name ?? null;
                    } elseif (isset($service->cat) && $service->cat) {
                        $categoryName = $service->cat->name ?? null;
                    }

                    $results[] = [
                        'id' => $service->id,
                        'type' => $objectModel,
                        'title' => $service->title,
                        'image' => $service->image_id ? get_file_url($service->image_id, 'medium') : null,
                        'base_price' => $service->price ?? 0,
                        'category' => $categoryName,
                    ];
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => $results,
        ]);
    }

    /**
     * Search Customer by Phone Number
     */
    public function searchCustomer(Request $request)
    {
        $phone = $request->input('phone', '');

        if (empty($phone)) {
            return response()->json([
                'success' => false,
                'message' => __('Please enter phone number'),
            ]);
        }

        $customer = \App\User::where('phone', 'like', '%' . $phone . '%')
            ->orWhere('phone', $phone)
            ->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => __('Customer not found'),
                'create_new' => true,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $customer->id,
                'name' => $customer->getDisplayName(),
                'first_name' => $customer->first_name,
                'last_name' => $customer->last_name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'avatar' => $customer->getAvatarUrl(),
                'wallet_balance' => floatval($customer->getMeta('wallet_balance', 0)),
                'points' => intval($customer->getMeta('points', 0)),
            ],
        ]);
    }

    /**
     * Get Activity Details (Dates, Times, Pricing)
     */
    public function getActivityDetails(Request $request)
    {
        $activityId = $request->input('activity_id');
        $activityType = $request->input('activity_type');

        $bookableServices = get_bookable_services();

        if (!isset($bookableServices[$activityType])) {
            return response()->json([
                'success' => false,
                'message' => __('Invalid activity type'),
            ]);
        }

        $serviceClass = $bookableServices[$activityType];
        $service = $serviceClass::find($activityId);

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => __('Activity not found'),
            ]);
        }

        $availableDates = [];
        $availableTimes = [];
        $personTypes = [];

        // Get available dates based on activity type
        if ($activityType === 'tour') {
            // Get dates from tour_dates table
            $tourDates = \Modules\Tour\Models\TourDate::where('target_id', $service->id)
                ->where('active', 1)
                ->where('start_date', '>=', now()->format('Y-m-d'))
                ->orderBy('start_date', 'asc')
                ->get();

            foreach ($tourDates as $tourDate) {
                $availableDates[] = [
                    'date' => $tourDate->start_date,
                    'price' => $tourDate->price ?? $service->price,
                    'person_types' => $tourDate->person_types ?? null,
                ];
            }
        } elseif ($activityType === 'event') {
            // Get dates from event_dates table
            $eventDates = \Modules\Event\Models\EventDate::where('target_id', $service->id)
                ->where('start_date', '>=', now()->format('Y-m-d'))
                ->orderBy('start_date', 'asc')
                ->get();

            foreach ($eventDates as $eventDate) {
                $availableDates[] = [
                    'date' => $eventDate->start_date,
                    'price' => $eventDate->price ?? $service->price,
                ];
            }

            // Get available time slots for events
            if (!empty($service->start_time) && !empty($service->end_time) && !empty($service->duration)) {
                $availableTimes = $this->generateTimeSlots(
                    $service->start_time,
                    $service->end_time,
                    $service->duration,
                    $service->duration_unit ?? 'hour'
                );
            }
        } else {
            // For other services (hotel, space, car, etc.), use a default 30-day range
            $startDate = now();
            for ($i = 0; $i < 30; $i++) {
                $date = $startDate->copy()->addDays($i);
                $availableDates[] = [
                    'date' => $date->format('Y-m-d'),
                    'price' => $service->price ?? 0,
                ];
            }
        }

        // Get person types from service meta (for tours and events)
        if (in_array($activityType, ['tour', 'event'])) {
            $meta = $service->meta;
            if ($meta && !empty($meta->enable_person_types) && !empty($meta->person_types)) {
                foreach ($meta->person_types as $type) {
                    $personTypes[] = [
                        'name' => $type['name'] ?? '',
                        'desc' => $type['desc'] ?? '',
                        'min' => $type['min'] ?? 0,
                        'max' => $type['max'] ?? 99,
                        'price' => $type['price'] ?? 0,
                    ];
                }
            }
        }

        // Default person type if none configured
        if (empty($personTypes)) {
            $personTypes = [
                [
                    'name' => __('Adult'),
                    'desc' => __('Ages 12+'),
                    'min' => 1,
                    'max' => 20,
                    'price' => $service->price ?? 0,
                ],
            ];
        }

        $responseData = [
            'success' => true,
            'data' => [
                'id' => $service->id,
                'type' => $activityType,
                'title' => $service->title,
                'image' => $service->image_id ? get_file_url($service->image_id, 'medium') : null,
                'base_price' => $service->price ?? 0,
                'available_dates' => $availableDates,
                'available_times' => $availableTimes,
                'person_types' => $personTypes,
            ],
        ];

        return response()->json($responseData);
    }

    /**
     * Get Available Times for Selected Date
     */
    public function getAvailableTimes(Request $request)
    {
        $activityId = $request->input('activity_id');
        $activityType = $request->input('activity_type');
        $selectedDate = $request->input('date');

        if (!$selectedDate) {
            return response()->json([
                'success' => false,
                'message' => __('Date is required'),
            ]);
        }

        $bookableServices = get_bookable_services();

        if (!isset($bookableServices[$activityType])) {
            return response()->json([
                'success' => false,
                'message' => __('Invalid activity type'),
            ]);
        }

        $serviceClass = $bookableServices[$activityType];
        $service = $serviceClass::find($activityId);

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => __('Activity not found'),
            ]);
        }

        $availableTimes = [];

        // Get day of week from selected date (1=Monday, 7=Sunday)
        $dayOfWeek = date('N', strtotime($selectedDate));

        // For tours, get open hours from meta
        if ($activityType === 'tour') {
            $meta = $service->meta;

            if ($meta && !empty($meta->enable_open_hours) && !empty($meta->open_hours)) {
                $openHours = $meta->open_hours;

                // Check if this day is enabled
                if (isset($openHours[$dayOfWeek]) && !empty($openHours[$dayOfWeek]['enable'])) {
                    $dayHours = $openHours[$dayOfWeek];
                    $fromTime = $dayHours['from'] ?? null;
                    $toTime = $dayHours['to'] ?? null;

                    if ($fromTime && $toTime) {
                        // Generate hourly time slots
                        $availableTimes = $this->generateTimeSlots($fromTime, $toTime, 1, 'hour');
                    }
                }
            }
        }
        // For events, use start_time and end_time with duration
        elseif ($activityType === 'event') {
            if (!empty($service->start_time) && !empty($service->end_time) && !empty($service->duration)) {
                $availableTimes = $this->generateTimeSlots(
                    $service->start_time,
                    $service->end_time,
                    $service->duration,
                    $service->duration_unit ?? 'hour'
                );
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'date' => $selectedDate,
                'day_of_week' => $dayOfWeek,
                'available_times' => $availableTimes,
            ],
        ]);
    }

    /**
     * Generate time slots based on start time, end time, and duration
     */
    protected function generateTimeSlots($startTime, $endTime, $duration, $durationUnit = 'hour')
    {
        $slots = [];

        // Convert times to timestamps
        $start = strtotime($startTime);
        $end = strtotime($endTime);

        // Calculate duration in seconds
        $durationSeconds = $durationUnit === 'hour'
            ? $duration * 3600  // HOUR_IN_SECONDS
            : $duration * 60;   // MINUTE_IN_SECONDS

        // Generate slots
        $current = $start;
        while ($current + $durationSeconds <= $end) {
            $slotStart = date('H:i', $current);
            $slotEnd = date('H:i', $current + $durationSeconds);

            $slots[] = [
                'start' => $slotStart,
                'end' => $slotEnd,
                'display' => $slotStart . ' - ' . $slotEnd,
            ];

            $current += $durationSeconds;
        }

        return $slots;
    }

    /**
     * Store New Order
     */
    public function storeOrder(Request $request)
    {
        $this->checkPermission('booking_update');

        try {
            $customerId = $request->input('customer_id');
            $cartItems = $request->input('cart_items', []);

            if (empty($customerId)) {
                return response()->json([
                    'success' => false,
                    'message' => __('Please select a customer'),
                ]);
            }

            if (empty($cartItems)) {
                return response()->json([
                    'success' => false,
                    'message' => __('Cart is empty'),
                ]);
            }

            $customer = \App\User::find($customerId);
            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => __('Customer not found'),
                ]);
            }

            // Generate payment_id for grouping cart items
            $paymentId = 'OP_' . time() . '_' . $customerId;
            $createdBookings = [];
            $sharedOrderCode = null; // Will store the code for all bookings in this order

            foreach ($cartItems as $index => $item) {
                // Calculate total guests
                $totalGuests = 0;
                foreach ($item['person_types'] as $personType) {
                    $totalGuests += (int) $personType['number'];
                }

                $booking = new Booking;
                $booking->customer_id = $customerId;
                $booking->object_id = $item['activity_id'];
                $booking->object_model = $item['activity_type'];
                $booking->start_date = $item['date'];
                $booking->end_date = $item['date']; // Same as start for tours
                $booking->total = $item['total'];
                $booking->total_guests = $totalGuests;
                $booking->status = 'processing'; // Pending payment
                $booking->payment_id = $paymentId;
                $booking->first_name = $customer->first_name;
                $booking->last_name = $customer->last_name;
                $booking->email = $customer->email;
                $booking->phone = $customer->phone;
                $booking->salesman_id = Auth::id(); // Current agent
                $booking->vendor_id = 1; // Default vendor
                $booking->create_user = Auth::id();

                // Only the first booking gets auto-generated code
                // All other bookings will use the same code
                if ($index === 0) {
                    // First booking - save normally to get auto-generated code
                    $booking->save();
                    $sharedOrderCode = $booking->code;
                } else {
                    // Subsequent bookings - set code manually before save
                    $booking->code = $sharedOrderCode;
                    $booking->save();
                }

                // Store person types as meta
                if (!empty($item['person_types'])) {
                    $booking->addMeta('person_types', $item['person_types']);
                }

                // Store time if available
                if (!empty($item['time'])) {
                    $booking->addMeta('selected_time', $item['time']);
                }

                $createdBookings[] = $booking;
            }

            // Calculate total amount for all items
            $totalAmount = collect($cartItems)->sum('total');
            $itemsCount = count($cartItems);

            // Send notification to customer with checkout link
            $customer->notify(new \App\Notifications\PendingPaymentNotification(
                $paymentId,
                $totalAmount,
                $itemsCount
            ));

            return response()->json([
                'success' => true,
                'message' => __('Order created successfully. Customer has been notified.'),
                'payment_id' => $paymentId,
                'bookings' => collect($createdBookings)->pluck('id'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('Failed to create order: ') . $e->getMessage(),
            ], 500);
        }
    }
}

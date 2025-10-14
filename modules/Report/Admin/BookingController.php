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

        $request->validate([
            'booking_id' => 'required|exists:bravo_bookings,id',
        ]);

        try {
            $notes = \Modules\Booking\Models\BookingNote::where('booking_id', $request->booking_id)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($note) {
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
                'notes' => $notes,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('Failed to load notes: ') . $e->getMessage(),
            ], 500);
        }
    }
}

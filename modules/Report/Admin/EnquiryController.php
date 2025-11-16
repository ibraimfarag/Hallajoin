<?php

namespace Modules\Report\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\AdminController;
use Modules\Booking\Events\EnquiryReplyCreated;
use Modules\Booking\Models\Enquiry;
use Modules\Booking\Models\EnquiryReply;

class EnquiryController extends AdminController
{
    /**
     * @var Enquiry
     */
    protected $enquiryClass;

    public function __construct(Enquiry $enquiry)
    {
        $this->setActiveMenu(route('report.admin.booking'));
        $this->enquiryClass = $enquiry;

    }

    public function index(Request $request)
    {
        $this->checkPermission('enquiry_view');
        $query = $this->enquiryClass->query()->where('status', '!=', 'draft');

        // Search filter
        if (! empty($request->s)) {
            $query->where(function ($q) use ($request) {
                $q->where('email', 'LIKE', '%'.$request->s.'%')
                    ->orWhere('name', 'LIKE', '%'.$request->s.'%')
                    ->orWhere('phone', 'LIKE', '%'.$request->s.'%')
                    ->orWhere('activity_name', 'LIKE', '%'.$request->s.'%');
            });
            $title_page = __('Search results: ":s"', ['s' => $request->s]);
        }

        // Salesman filter
        if ($request->filled('salesman')) {
            if ($request->salesman === '0') {
                $query->whereNull('salesman');
            } else {
                $query->where('salesman', $request->salesman);
            }
        }

        $query->whereIn('object_model', array_keys(get_bookable_services()));
        $query->orderBy('id', 'desc');

        // Get users with 'seals' role for filter
        $salesRole = \Modules\User\Models\Role::where('name', 'seals')->first();
        if ($salesRole) {
            $salesmen = \App\User::where('role_id', $salesRole->id)
                ->where('status', 'publish')
                ->get()
                ->mapWithKeys(function ($user) {
                    return [$user->id => $user->getDisplayName(true)];
                })
                ->toArray();
        } else {
            $salesmen = [];
        }

        $data = [
            'rows' => $query->withCount(['replies'])->paginate(20),
            'salesmen' => $salesmen,
            'breadcrumbs' => [
                [
                    'name' => __('Enquiry'),
                    'url' => route('report.admin.enquiry.index'),
                ],
                [
                    'name' => __('All'),
                    'class' => 'active',
                ],
            ],
            'enquiry_update' => $this->hasPermission('enquiry_update'),
            'enquiry_manage_others' => $this->hasPermission('enquiry_manage_others'),
            'statues' => $this->enquiryClass->enquiryStatus,
            'page_title' => $title_page ?? __('Enquiry Management'),
        ];

        return view('Report::admin.enquiry.index', $data);
    }

    public function bulkEdit(Request $request)
    {
        $ids = $request->input('ids');
        $action = $request->input('action');
        if (empty($ids) or ! is_array($ids)) {
            return redirect()->back()->with('error', __('No items selected'));
        }
        if (empty($action)) {
            return redirect()->back()->with('error', __('Please select action'));
        }
        if ($action == 'delete') {
            foreach ($ids as $id) {
                $query = $this->enquiryClass->query()->where('id', $id);
                if (! $this->hasPermission('enquiry_manage_others')) {
                    $query->where('vendor_id', Auth::id());
                    $this->checkPermission('enquiry_update');
                }
                $query->first();
                if (! empty($query)) {
                    $query->delete();
                }
            }
        } else {
            foreach ($ids as $id) {
                $query = $this->enquiryClass->query()->where('id', $id);
                if (! $this->hasPermission('enquiry_manage_others')) {
                    $query->where('vendor_id', Auth::id());
                    $this->checkPermission('enquiry_update');
                }
                $item = $query->first();
                if (! empty($item)) {
                    $item->status = $action;
                    $item->save();
                }
            }
        }

        return redirect()->back()->with('success', __('Update success'));
    }

    public function reply(Enquiry $enquiry, Request $request)
    {
        $this->checkPermission('enquiry_view');

        $data = [
            'rows' => $enquiry->replies()->orderByDesc('id')->paginate(20),

            'breadcrumbs' => [
                [
                    'name' => __('Enquiry'),
                    'url' => route('report.admin.enquiry.index'),
                ],
                [
                    'name' => __('Enquiry :name', ['name' => '#'.$enquiry->id.' - '.($enquiry->service->title ?? '')]),
                ],
                [
                    'name' => __('All Replies'),
                    'class' => 'active',
                ],
            ],
            'page_title' => __('Replies'),
            'enquiry' => $enquiry,
        ];

        return view('Report::admin.enquiry.reply', $data);
    }

    public function replyStore(Enquiry $enquiry, Request $request)
    {
        $this->checkPermission('enquiry_view');

        $request->validate([
            'content' => 'required',
        ]);

        $reply = new EnquiryReply;
        $reply->content = $request->input('content');
        $reply->parent_id = $enquiry->id;
        $reply->user_id = auth()->id();

        $reply->save();

        EnquiryReplyCreated::dispatch($reply, $enquiry);

        return back()->with('success', __('Reply added'));
    }

    public function getNotes(Enquiry $enquiry)
    {
        $this->checkPermission('enquiry_view');

        $notes = $enquiry->replies()
            ->with('author')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($note) {
                return [
                    'id' => $note->id,
                    'content' => $note->content,
                    'user_name' => $note->author ? $note->author->getDisplayName(true) : 'Unknown',
                    'created_at' => $note->created_at->format('d/M/Y H:i'),
                    'attachment' => $note->attachment ? asset($note->attachment) : null,
                ];
            });

        return response()->json([
            'success' => true,
            'notes' => $notes,
        ]);
    }

    public function getUsersForMention()
    {
        $this->checkPermission('enquiry_view');

        // Get role IDs for administrator and seals
        $roleIds = \DB::table('core_roles')
            ->whereIn('code', ['administrator', 'seals'])
            ->pluck('id')
            ->toArray();

        $users = \App\User::select('id', 'first_name', 'last_name', 'email', 'role_id')
            ->where('status', 'publish')
            ->whereIn('role_id', $roleIds)
            ->get()
            ->map(function ($user) {
                $role = \DB::table('core_roles')->where('id', $user->role_id)->first();

                return [
                    'id' => $user->id,
                    'name' => $user->getDisplayName(true),
                    'role' => $role ? ucfirst($role->name) : '',
                    'avatar' => $user->getAvatarUrl(),
                ];
            });

        return response()->json([
            'success' => true,
            'users' => $users,
        ]);
    }

    public function storeNote(Enquiry $enquiry, Request $request)
    {
        $this->checkPermission('enquiry_view');

        $request->validate([
            'content' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        // Check if enquiry has no salesman assigned and current user is sales
        $salesRoleId = \DB::table('core_roles')->where('code', 'seals')->value('id');
        $currentUser = auth()->user();

        if (empty($enquiry->salesman) && $currentUser->role_id == $salesRoleId) {
            $enquiry->salesman = $currentUser->id;
            $enquiry->save();
        }

        $note = new EnquiryReply;
        $note->content = $request->input('content');
        $note->parent_id = $enquiry->id;
        $note->user_id = auth()->id();

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time().'_'.$file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/enquiry_notes', $fileName, 'public');
            $note->attachment = 'storage/'.$filePath;
        }

        $note->save();

        // Send notifications to mentioned users
        preg_match_all('/@(\w+)/', $note->content, $mentions);
        if (! empty($mentions[1])) {
            foreach ($mentions[1] as $mentionedName) {
                $mentionedUser = \App\User::where('status', 'active')
                    ->where(function ($query) use ($mentionedName) {
                        $query->where('first_name', 'LIKE', "%{$mentionedName}%")
                            ->orWhere('last_name', 'LIKE', "%{$mentionedName}%")
                            ->orWhere('email', 'LIKE', "%{$mentionedName}%");
                    })
                    ->first();

                if ($mentionedUser && $mentionedUser->id !== auth()->id()) {
                    $mentionedUser->notify(new \App\Notifications\EnquiryMentionNotification(
                        $enquiry,
                        $note,
                        auth()->user()
                    ));
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => __('Note added successfully'),
            'note' => [
                'id' => $note->id,
                'content' => $note->content,
                'user_name' => trim(auth()->user()->first_name . ' ' . auth()->user()->last_name),
                'created_at' => $note->created_at->format('d/M/Y H:i'),
                'attachment' => $note->attachment ? asset($note->attachment) : null,
            ],
        ]);
    }
}

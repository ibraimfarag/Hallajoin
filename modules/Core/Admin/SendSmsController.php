<?php

namespace Modules\Core\Admin;

use App\User;
use Illuminate\Http\Request;
use Modules\AdminController;

class SendSmsController extends AdminController
{
    public function index()
    {
        $data = [
            'page_title' => __('Send SMS'),
        ];

        return view('Core::admin.send-sms.index', $data);
    }

    public function getForSelect2(Request $request)
    {
        $q = $request->query('q', '');

        $query = User::query();

        if (! empty($q)) {
            $query->where(function ($query) use ($q) {
                $query->where('name', 'like', '%'.$q.'%')
                    ->orWhere('email', 'like', '%'.$q.'%')
                    ->orWhere('phone', 'like', '%'.$q.'%');
            });
        }

        $users = $query->limit(20)->get();

        $results = [];
        foreach ($users as $user) {
            $results[] = [
                'id' => $user->id,
                'text' => $user->name,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'avatar' => $user->avatar_url ?? ($user->getAvatarUrl() ?? asset('images/avatar.png')),
            ];
        }

        return response()->json([
            'results' => $results,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:160',
            'send_to_all' => 'sometimes',
            'user_ids' => 'required_unless:send_to_all,1|string',
        ]);

        $message = $request->input('message');
        $sendToAll = $request->has('send_to_all');
        $userIds = $request->input('user_ids', '');

        if ($sendToAll) {
            // Send to all users
            // TODO: Implement SMS sending to all users
            \Log::info('SMS: Sending to all users', ['message' => $message]);
        } else {
            // Send to specific users
            $ids = array_filter(array_map('trim', explode(',', $userIds)));
            if (! empty($ids)) {
                // TODO: Implement SMS sending to specific users
                \Log::info('SMS: Sending to users', ['user_ids' => $ids, 'message' => $message]);
            }
        }

        return redirect()->back()->with('success', __('SMS sent successfully'));
    }
}

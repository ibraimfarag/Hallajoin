<?php

namespace Modules\Core\Admin;

use App\User;
use Illuminate\Http\Request;
use Modules\AdminController;

class SendEmailController extends AdminController
{
    public function index()
    {
        $data = [
            'page_title' => __('Send Email'),
        ];

        return view('Core::admin.send-email.index', $data);
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
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'send_to_all' => 'sometimes',
            'user_ids' => 'required_unless:send_to_all,1|string',
        ]);

        $subject = $request->input('subject');
        $message = $request->input('message');
        $sendToAll = $request->has('send_to_all');
        $userIds = $request->input('user_ids', '');

        if ($sendToAll) {
            // Send to all users
            // TODO: Implement Email sending to all users
            \Log::info('Email: Sending to all users', ['subject' => $subject]);
        } else {
            // Send to specific users
            $ids = array_filter(array_map('trim', explode(',', $userIds)));
            if (! empty($ids)) {
                // TODO: Implement Email sending to specific users
                \Log::info('Email: Sending to users', ['user_ids' => $ids, 'subject' => $subject]);
            }
        }

        return redirect()->back()->with('success', __('Email sent successfully'));
    }
}

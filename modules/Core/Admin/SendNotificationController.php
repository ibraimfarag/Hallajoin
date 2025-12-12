<?php

namespace Modules\Core\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Modules\AdminController;
use Modules\Core\Models\NotificationPush;
use App\User;

class SendNotificationController extends AdminController
{
    public function index()
    {
        $data = [
            'page_title' => __('Send Notifications'),
        ];
        return view('Core::admin.send-notification.index', $data);
    }

    public function getForSelect2(Request $request)
    {
        $q = $request->query('q', '');
        
        $query = User::query();
        
        if (!empty($q)) {
            $query->where(function($query) use ($q) {
                $query->where('name', 'like', '%' . $q . '%')
                      ->orWhere('email', 'like', '%' . $q . '%')
                      ->orWhere('phone', 'like', '%' . $q . '%');
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
            ];
        }
        
        return response()->json([
            'results' => $results,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'link' => 'nullable|string',
            'user_ids' => 'required_without:send_to_all|string',
            'send_to_all' => 'nullable|boolean',
        ]);

        if ($request->input('send_to_all')) {
            // Send to all users
            $users = User::all();
            foreach ($users as $user) {
                DB::table('notifications')->insert([
                    'id' => (string) Str::uuid(),
                    'type' => 'App\\Notifications\\AdminChannelServices',
                    'notifiable_type' => User::class,
                    'notifiable_id' => $user->id,
                    'data' => json_encode([
                        'title' => $request->input('title'),
                        'message' => $request->input('message'),
                        'link' => $request->input('link'),
                        'for_admin' => false,
                    ]),
                    'for_admin' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } else {
            // Send to specific users
            $userIdsString = $request->input('user_ids');
            $userIds = array_map('trim', explode(',', $userIdsString));
            
            foreach ($userIds as $userId) {
                if (empty($userId)) continue;
                
                $user = User::find($userId);
                if ($user) {
                    DB::table('notifications')->insert([
                        'id' => (string) Str::uuid(),
                        'type' => 'App\\Notifications\\AdminChannelServices',
                        'notifiable_type' => User::class,
                        'notifiable_id' => $user->id,
                        'data' => json_encode([
                            'title' => $request->input('title'),
                            'message' => $request->input('message'),
                            'link' => $request->input('link'),
                            'for_admin' => false,
                        ]),
                        'for_admin' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        return redirect()->route('core.admin.send-notification.index')
                        ->with('success', __('Notifications sent successfully'));
    }
}

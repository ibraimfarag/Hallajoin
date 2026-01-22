<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class BalanceAdminController extends Controller
{
    public function index(Request $request)
    {
        // Get all users
        $allUsers = User::select('id', 'first_name', 'last_name', 'phone', 'avatar_id', 'created_at')
            ->whereNotNull('id')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get users with their balance and points
        $users = $allUsers->map(function ($user) {
            // Get wallet balance from user_meta
            $walletBalance = \App\UserMeta::where('user_id', $user->id)
                ->where('name', 'balance')
                ->value('val') ?? 0;

            // Get points from user_meta
            $points = \App\UserMeta::where('user_id', $user->id)
                ->where('name', 'points')
                ->value('val') ?? 0;

            // Calculate total balance (wallet + points as AED value)
            $totalBalance = floatval($walletBalance) + floatval($points);

            // Get avatar URL
            $avatarUrl = null;
            $hasAvatar = false;
            if ($user->avatar_id) {
                $avatar = \Modules\Media\Models\MediaFile::find($user->avatar_id);
                if ($avatar && $avatar->file_path) {
                    $avatarUrl = asset('uploads/'.$avatar->file_path);
                    $hasAvatar = true;
                }
            }

            // Get first letter for placeholder
            $userName = trim($user->first_name.' '.$user->last_name) ?: 'N/A';
            $firstLetter = strtoupper(mb_substr($userName, 0, 1));

            return [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'name' => $userName,
                'phone' => $user->phone,
                'avatar' => $avatarUrl,
                'has_avatar' => $hasAvatar,
                'first_letter' => $firstLetter,
                'wallet' => number_format($walletBalance, 2),
                'points' => intval($points),
                'total_balance' => number_format($totalBalance, 2),
                'total_balance_raw' => $totalBalance,
                'created_at' => $user->created_at->format('d/m/Y H:i'),
            ];
        });

        // Sort by total balance descending
        $users = $users->sortByDesc('total_balance_raw')->values();

        $data = [
            'users' => $users,
            'page_title' => __('User Balances'),
            'breadcrumbs' => [
                [
                    'name' => __('Members'),
                    'url' => route('admin.users.index'),
                ],
                [
                    'name' => __('Balance'),
                    'class' => 'active',
                ],
            ],
        ];

        return view('admin.balance.index', $data);
    }
}

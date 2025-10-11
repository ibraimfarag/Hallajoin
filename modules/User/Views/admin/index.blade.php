@extends('admin.layouts.app')

@section('content')

    <style>
        body.dark-mode .card,
        body.dark-mode .panel {
            background: transparent;
            box-shadow: none;
        }

        .card {
            border: 0px solid rgba(0, 0, 0, .125);
        }

        .filter-checkbox-bar {
            background: none;
            padding: 8px 12px;
        }

        .filter-checkbox-bar .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
            padding: 0;
        }

        .filter-checkbox-bar .form-check-input {
            background: transparent;
            border: 2px solid #e5e7eb;
            border-radius: 4px;
            margin: 0 !important;
            box-shadow: none;
            transition: border-color 0.2s;
            flex-shrink: 0;
            position: relative;
        }

        .filter-checkbox-bar .form-check-input:checked {
            background: #7c3aed;
            border-color: #7c3aed;
        }

        .filter-checkbox-bar .form-check-label {
            color: #fff;
            font-size: 18px;
            font-weight: 400;
            margin: 0;
            line-height: 1.2;
            white-space: nowrap;
            user-select: none;
        }

        .filter-checkbox-bar .btn {
            font-size: 14px;
            font-weight: 600;
            border: none;
            transition: all 0.2s ease;
        }

        .filter-checkbox-bar .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        body.dark-mode .filter-checkbox-bar .form-check-label {
            color: #fff !important;
        }

        body.dark-mode .filter-checkbox-bar .btn {
            color: #fff;
        }

        body.dark-mode .form-control {
            height: 50px;
            color: #c5c5c5 !important;
            background: #122438;
            border: 1.9px solid #1b3d63;
            border-radius: 8px;
            padding: 0 20px;
        }

        body.dark-mode .input-group-text i {
            color: #fff !important;
        }

        body.dark-mode .fas {
            color: #fff !important;
        }

        /* Light Mode Styles */
        body:not(.dark-mode) .filter-container {
            background: #f8fafc !important;
            border: 1px solid #e2e8f0;
        }

        body:not(.dark-mode) .filter-checkbox-bar .form-check-label {
            color: #374151 !important;
        }

        body:not(.dark-mode) .filter-checkbox-bar .btn {
            color: #fff;
        }

        body:not(.dark-mode) .form-control {
            height: 50px;
            color: #374151 !important;
            background: #fff;
            border: 1.9px solid #d1d5db;
            border-radius: 8px;
            padding: 0 20px;
        }

        body:not(.dark-mode) .input-group-text i {
            color: #6b7280 !important;
        }

        body:not(.dark-mode) .fas {
            color: #6b7280 !important;
        }

        body:not(.dark-mode) .card {
            background: #fff;
            border: 1px solid #e5e7eb;
        }

        body:not(.dark-mode) .table-responsive {
            background: #f8fafc !important;
        }

        body:not(.dark-mode) .table-dark {
            background: #fff !important;
            color: #374151 !important;
        }

        body:not(.dark-mode) .table-dark th,
        body:not(.dark-mode) .table-dark td {
            background: #fff !important;
            color: #374151 !important;
            border-color: #e5e7eb !important;
        }

        body:not(.dark-mode) .table-dark tbody tr:hover {
            background: #f9fafb !important;
        }

        body:not(.dark-mode) h4 {
            color: #374151;
        }

        @media (max-width: 768px) {
            .filter-checkbox-bar {
                gap: 15px !important;
            }

            .filter-checkbox-bar .form-check {
                margin-right: 15px;
            }
        }


        body.dark-mode .table thead th {
            font-size: 18px !important;
            font-weight: 600;
            text-transform: none;
            font-weight: 500;
        }
    </style>


    <div class="container-fluid ">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <div class="p-3 mb-4 filter-container" style="background:#132438; border-radius:18px;">
                            <form method="GET" action="{{ route('user.admin.index') }}" class="mb-0">
                                <div class="row g-2 align-items-center mb-2">
                                    <div class="col-md-2">
                                        <input type="text" name="search" class="form-control" placeholder="Search"
                                            value="{{ request('search') }}" style="border-radius:20px; font-size:15px;">
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <input type="date" name="from" class="form-control" placeholder="From"
                                                value="{{ request('from') }}" style="border-radius:20px;  font-size:15px;">
                                            <span class="input-group-text"
                                                style="background:transparent; border:none; color:#7c8ba1; font-size:18px;"><i
                                                    class="fas fa-calendar-alt"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <input type="date" name="to" class="form-control" placeholder="To"
                                                value="{{ request('to') }}" style="border-radius:20px;  font-size:15px;">
                                            <span class="input-group-text"
                                                style="background:transparent; border:none; color:#7c8ba1; font-size:18px;"><i
                                                    class="fas fa-calendar-alt"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="country" class="form-control"
                                            style="border-radius:20px; font-size:15px;">
                                            <option value="">Country</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>{{ $country }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="role" class="form-control"
                                            style="border-radius:20px; font-size:15px;">
                                            <option value="">Any Role</option>
                                            @foreach($roles as $role)
                                                <option value="{{$role->name}}" {{ request('role') == $role->name ? 'selected' : '' }}>{{ucfirst($role->name)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="d-none col-md-2">
                                        <select name="language" class="form-control"
                                            style="border-radius:10px; font-size:15px;">
                                            <option value="">Languages</option>
                                            <option value="Arabic" {{ request('language') == 'Arabic' ? 'selected' : '' }}>
                                                Arabic</option>
                                            <option value="English" {{ request('language') == 'English' ? 'selected' : '' }}>
                                                English</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-5 mb-2 mt-4 flex-wrap filter-checkbox-bar justify-content-start"
                                    style="gap: 100px;">
                                    <div class="form-check d-flex align-items-center" style="margin-right: 20px;">
                                        <input class="form-check-input" type="checkbox" name="blocked" id="blocked"
                                            value="1" {{ request('blocked') ? 'checked' : '' }}
                                            style="width: 20px; height: 20px; margin-right: 8px; flex-shrink: 0;">
                                        <label class="form-check-label" for="blocked"
                                            style="font-size: 18px; white-space: nowrap;">Blocked</label>
                                    </div>
                                    <div class="form-check d-flex align-items-center" style="margin-right: 20px;">
                                        <input class="form-check-input" type="checkbox" name="order_blocked"
                                            id="order_blocked" value="1" {{ request('order_blocked') ? 'checked' : '' }}
                                            style="width: 20px; height: 20px; margin-right: 8px; flex-shrink: 0;">
                                        <label class="form-check-label" for="order_blocked"
                                            style="font-size: 18px; white-space: nowrap;">Order Blocked</label>
                                    </div>
                                </div>

                                <div class="d-flex mt-3 mb-2 filter-btn-bar">
                                    <button type="submit" class="btn"
                                        style="background:#7c3aed; color:#fff; font-weight:600; border-radius:8px; padding:8px 24px; min-width:90px; font-size: 14px;">FILTER</button>
                                    <a href="{{ route('user.admin.index') }}" class="btn"
                                        style="background:#374151; color:#b5b5b5; font-weight:600; border-radius:8px; padding:8px 20px; min-width:90px; font-size: 14px; margin-left:12px;">RESET</a>
                                </div>

                            </form>
                        </div>



                        <div class="table-responsive " style="
        padding: 20px;
        background: #132438;
        border-radius: 20px;
    ">
                            <h4 class="mt-2 mb-5">Users</h4>
                            <table class="table table-dark table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Phone Number</th>
                                        <th>Country</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Registered On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rows as $user)
                                        <tr>
                                            <td>
                                                @if($user->avatar)
                                                    <img src="{{ $user->avatar }}" alt="avatar" class="rounded-circle me-2"
                                                        width="32" height="32">
                                                @else
                                                    <div class="rounded-circle me-2 d-inline-block"
                                                        style="width: 32px; height: 32px; background: linear-gradient(45deg, #f093fb 0%, #f5576c 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; text-align: center; line-height: 32px;">
                                                        {{ strtoupper(substr($user->first_name ?? $user->name ?? 'U', 0, 1)) }}
                                                    </div>
                                                @endif
                                                <a href="{{ route('user.admin.profile', ['id' => $user->id]) }}"
                                                    style="color: #60a5fa; text-decoration: none;font-size: 19px">{{ $user->phone }}</a>
                                            </td>
                                            <td>
                                                <div style="display: flex; align-items: center; gap: 8px;">
                                                    
                                                    <a href="{{ route('user.admin.profile', ['id' => $user->id]) }}"
                                                        style="color: inherit; text-decoration: none;font-size: 19px">{{ $user->country ?? 'United Arab Emirates' }}</a>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('user.admin.profile', ['id' => $user->id]) }}"
                                                    style="color: inherit; text-decoration: none;font-size: 19px">{{ $user->getDisplayName() }}</a>
                                            </td>
                                            <td>
                                                <a href="{{ route('user.admin.profile', ['id' => $user->id]) }}"
                                                    style="color: inherit; text-decoration: none;font-size: 19px">{{ $user->email }}</a>
                                            </td>
                                            <td><span  style="font-size: 18px">{{ $user->created_at ? $user->created_at->format('d/M/Y H:i') : '' }}</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No users found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if(method_exists($rows, 'links'))
                            <div class="d-flex justify-content-center mt-3">
                                {{ $rows->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{__('Shopping Carts Management')}}</h1>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{__('Total Carts')}}
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Cart::count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    {{__('Active Carts')}}</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ \App\Models\Cart::where('status', 'active')->count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    {{__('Abandoned Carts')}}</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ \App\Models\Cart::where('status', 'abandoned')->count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{__('Total Value')}}
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format(\App\Models\Cart::where('status', 'active')->sum('total_amount'), 2) }}
                                    AED</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{__('Filters')}}</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.carts.index') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <label>{{__('Status')}}</label>
                            <select name="status" class="form-control">
                                <option value="">{{__('All Statuses')}}</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{__('Active')}}
                                </option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                    {{__('Completed')}}</option>
                                <option value="abandoned" {{ request('status') == 'abandoned' ? 'selected' : '' }}>
                                    {{__('Abandoned')}}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>{{__('Search User')}}</label>
                            <input type="text" name="search" class="form-control" placeholder="{{__('Name or Email')}}"
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label><br>
                            <button type="submit" class="btn btn-primary">{{__('Filter')}}</button>
                            <a href="{{ route('admin.carts.index') }}" class="btn btn-secondary">{{__('Reset')}}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Carts Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{__('Shopping Carts')}}</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>{{__('ID')}}</th>
                                <th>{{__('User')}}</th>
                                <th>{{__('Items')}}</th>
                                <th>{{__('Total Amount')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Created')}}</th>
                                <th>{{__('Actions')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($carts as $cart)
                                <tr>
                                    <td>{{ $cart->id }}</td>
                                    <td>
                                        @if($cart->user)
                                            {{ $cart->user->first_name }} {{ $cart->user->last_name }}<br>
                                            <small class="text-muted">{{ $cart->user->email }}</small>
                                        @else
                                            <span class="text-muted">{{__('Guest')}}</span>
                                        @endif
                                    </td>
                                    <td>{{ $cart->items->count() }}</td>
                                    <td>{{ number_format($cart->total_amount, 2) }} AED</td>
                                    <td>
                                        <span
                                            class="badge badge-{{ $cart->status == 'active' ? 'success' : ($cart->status == 'completed' ? 'primary' : 'warning') }}">
                                            {{ ucfirst($cart->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $cart->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.carts.show', $cart) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> {{__('View')}}
                                        </a>
                                        <form method="POST" action="{{ route('admin.carts.destroy', $cart) }}"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('{{__('Are you sure?')}}')">
                                                <i class="fas fa-trash"></i> {{__('Delete')}}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">{{__('No carts found')}}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($carts->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $carts->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
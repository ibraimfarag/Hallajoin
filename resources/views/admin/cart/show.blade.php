@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{__('Cart Details')}} #{{ $cart->id }}</h1>
            <a href="{{ route('admin.carts.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> {{__('Back to Carts')}}
            </a>
        </div>

        <div class="row">
            <!-- Cart Information -->
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">{{__('Cart Information')}}</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>{{__('Cart ID')}}:</strong></td>
                                <td>{{ $cart->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{__('User')}}:</strong></td>
                                <td>
                                    @if($cart->user)
                                        {{ $cart->user->first_name }} {{ $cart->user->last_name }}<br>
                                        <small>{{ $cart->user->email }}</small>
                                    @else
                                        <span class="text-muted">{{__('Guest')}}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>{{__('Status')}}:</strong></td>
                                <td>
                                    <span
                                        class="badge badge-{{ $cart->status == 'active' ? 'success' : ($cart->status == 'completed' ? 'primary' : 'warning') }}">
                                        {{ ucfirst($cart->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>{{__('Total Items')}}:</strong></td>
                                <td>{{ $cart->items->count() }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{__('Total Amount')}}:</strong></td>
                                <td><strong>{{ number_format($cart->total_amount, 2) }} AED</strong></td>
                            </tr>
                            <tr>
                                <td><strong>{{__('Created')}}:</strong></td>
                                <td>{{ $cart->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{__('Updated')}}:</strong></td>
                                <td>{{ $cart->updated_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Status Update -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">{{__('Update Status')}}</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.carts.status', $cart) }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label>{{__('Status')}}</label>
                                <select name="status" class="form-control">
                                    <option value="active" {{ $cart->status == 'active' ? 'selected' : '' }}>{{__('Active')}}
                                    </option>
                                    <option value="completed" {{ $cart->status == 'completed' ? 'selected' : '' }}>
                                        {{__('Completed')}}
                                    </option>
                                    <option value="abandoned" {{ $cart->status == 'abandoned' ? 'selected' : '' }}>
                                        {{__('Abandoned')}}
                                    </option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">{{__('Update Status')}}</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">{{__('Cart Items')}}</h6>
                    </div>
                    <div class="card-body">
                        @if($cart->items->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{__('Service')}}</th>
                                            <th>{{__('Type')}}</th>
                                            <th>{{__('Quantity')}}</th>
                                            <th>{{__('Price')}}</th>
                                            <th>{{__('Total')}}</th>
                                            <th>{{__('Booking Data')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cart->items as $item)
                                            <tr>
                                                <td>
                                                    <strong>{{ $item->service_title }}</strong><br>
                                                    <small class="text-muted">ID: {{ $item->service_id }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge badge-secondary">{{ ucfirst($item->service_type) }}</span>
                                                </td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>{{ number_format($item->price, 2) }} AED</td>
                                                <td><strong>{{ number_format($item->total_price, 2) }} AED</strong></td>
                                                <td>
                                                    @php
                                                        if (!function_exists('flattenValue')) {
                                                            function flattenValue($val) {
                                                                if (is_array($val)) {
                                                                    return implode(', ', array_map(function($v) {
                                                                        return is_array($v) ? flattenValue($v) : $v;
                                                                    }, $val));
                                                                }
                                                                return $val;
                                                            }
                                                        }
                                                    @endphp
                                                    @if($item->booking_data)
                                                        @foreach($item->booking_data as $key => $value)
                                                            <small><strong>{{ ucfirst($key) }}:</strong> {{ flattenValue($value) }}</small><br>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">{{__('No additional data')}}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-right">{{__('Total')}}</th>
                                            <th>{{ number_format($cart->total_amount, 2) }} AED</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">{{__('This cart is empty')}}</h5>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
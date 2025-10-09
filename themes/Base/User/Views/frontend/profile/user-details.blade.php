@extends('layouts.app')

@section('content')
    <div class="page-profile-content page-template-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="profile-name">{{ __("User Details") }}</h3>
                    <table class="table table-bordered">
                        <tr>
                            <th>{{ __("Name") }}</th>
                            <td>{{ $user->getDisplayName() }}</td>
                        </tr>
                        <tr>
                            <th>{{ __("Email") }}</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>{{ __("Phone") }}</th>
                            <td>{{ $user->phone }}</td>
                        </tr>
                        <tr>
                            <th>{{ __("Country") }}</th>
                            <td>{{ $user->country ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __("Registered On") }}</th>
                            <td>{{ $user->created_at ? $user->created_at->format('d/M/Y H:i') : 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
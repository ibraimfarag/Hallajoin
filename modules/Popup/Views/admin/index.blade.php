@extends('admin.layouts.app')

<style>
    .notification-form-container {
        border-radius: 12px;
        padding: 30px;
    }

    body.dark-mode .notification-form-container {}

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: 600;
        margin-bottom: 10px;
        display: block;
        color: #333;
        font-size: 14px;
    }

    body.dark-mode .form-group label {
        color: #e0e0e0;
    }

    body.dark-mode .form-control:focus {
        background: transparent;
        border-color: #25466ad6;
        color: var(--dark-text-primary);
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
    }

    .form-control {
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 10px 12px;
        font-size: 14px;
        transition: border-color 0.3s;
        background: transparent;
        color: #333;
    }

    .form-control:focus {
        border-color: #25466ad6;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        outline: none;
    }

    body.dark-mode .form-control {
        background: transparent;
        border-color: #25466ad6;
        color: #e0e0e0;
    }

    body.dark-mode .form-control:focus {
        border-color: #25466ad6;
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
        font-family: inherit;
    }

    .btn-submit {
        background: #224162;
        color: #fff;
        border: none;
        padding: 10px 30px;
        border-radius: 4px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-submit:hover {
        background: #1a2e4a;
    }

    .btn-secondary {
        background: #6c757d;
        color: #fff;
        border: none;
        padding: 10px 30px;
        border-radius: 4px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-secondary:hover {
        background: #5a6268;
    }

    .btn {
        color: #333;
        font-weight: 600;
        font-size: 14px;
        padding: 10px 30px;
        border: none;
        background: transparent;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn:hover {
        background: transparent;
    }

    body.dark-mode .btn {
        color: #e0e0e0;
    }

    body.dark-mode .btn:hover {
        background: transparent;
    }

    small.text-muted {
        color: #999;
        font-size: 12px;
        display: block;
        margin-top: 5px;
    }

    body.dark-mode small.text-muted {
        color: #aaa;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 20px;
        color: #333;
    }

    body.dark-mode .section-title {
        color: #e0e0e0;
    }

    .tags-input-container {
        border: 1px solid #b3b3b3;
        border-radius: 6px;
        padding: 8px;
        min-height: 42px;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
        background: transparent;
        cursor: text;
        transition: border-color 0.3s;
    }

    .tags-input-container:focus-within {
        border-color: #224162;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
    }

    body.dark-mode .tags-input-container {
        background: transparent;
        border-color: #224162;
    }

    body.dark-mode .tags-input-container:focus-within {
        border-color: #224162;
    }

    .tag-item {
        background: #059669;
        color: #fff;
        padding: 5px 10px;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 500;
    }

    .tag-remove {
        cursor: pointer;
        font-weight: bold;
        font-size: 16px;
        line-height: 1;
        opacity: 0.8;
        transition: opacity 0.2s;
    }

    .tag-remove:hover {
        opacity: 1;
    }

    .tag-input {
        border: none;
        outline: none;
        background: transparent;
        flex: 1;
        min-width: 120px;
        padding: 4px;
        font-size: 14px;
        color: #333;
    }

    body.dark-mode .tag-input {
        color: #e0e0e0;
    }

    .tag-input::placeholder {
        color: #999;
    }

    body.dark-mode .tag-input::placeholder {
        color: #666;
    }

    #user_ids_hidden {
        display: none;
    }

    .user-search-dropdown {
        position: absolute;
        background: #fff;
        border: 1px solid #059669;
        border-radius: 6px;
        max-height: 250px;
        overflow-y: auto;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 1000;
        display: none;
        margin-top: 5px;
        min-width: 300px;
    }

    body.dark-mode .user-search-dropdown {
        background: #0f1c2e;
        border-color: #059669;
    }

    .user-search-item {
        padding: 12px 15px;
        cursor: pointer;
        border-bottom: 1px solid #f0f0f0;
        transition: background 0.2s;
    }

    body.dark-mode .user-search-item {
        border-bottom-color: #2a3f5f;
    }

    .user-search-item:hover {
        background: #f8f9fa;
    }

    body.dark-mode .user-search-item:hover {
        background: #1e3a5f;
    }

    .user-search-item:last-child {
        border-bottom: none;
    }

    .user-name {
        font-weight: 600;
        color: #333;
        margin-bottom: 4px;
    }

    body.dark-mode .user-name {
        color: #e0e0e0;
    }

    .user-details {
        font-size: 12px;
        color: #666;
    }

    body.dark-mode .user-details {
        color: #999;
    }

    .dropdown-loading,
    .dropdown-no-results {
        padding: 12px 15px;
        text-align: center;
        color: #666;
        font-size: 13px;
    }

    body.dark-mode .dropdown-loading,
    body.dark-mode .dropdown-no-results {
        color: #999;
    }

    .tags-wrapper {
        position: relative;
    }

    body.dark-mode .container-fluid {
        background: #0f1c2e;
    }


    .bg-form {
        background: #ffffff;
        flex: 1;
        padding: 30px 20px;
        border-radius: 20px;
        height: fit-content;
    }

    body.dark-mode .bg-form {

        background: #12243a;
    }

    .btn-outline-success {
        border: 2px solid #059669;
        background: transparent;
        color: #059669;
        padding: 10px 28px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-outline-success:hover {
        background: #059669;
        color: #fff;
    }

    .btn-outline-success i {
        font-size: 16px;
    }

    body.dark-mode .btn-outline-success {
        border-color: #059669;
        color: #059669;
    }

    body.dark-mode .btn-outline-success:hover {
        background: #059669;
        color: #fff;
    }

    body.dark-mode .panel-body, 
    body.dark-mode .panel 
    {
        background: #132438 !important;
        border-radius: 17px !important;
    } 
    .panel-body, 
    .panel 
    {
       
        border-radius: 17px !important;
    }
.btn-mint-outline {
    background: transparent;
    border: 2px solid rgb(5, 150, 105);
    color: rgb(5, 150, 105);
    padding: 10px 20px;
    border-radius: 15px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: 0.3s;
    text-decoration: none;
}

.btn-mint-outline:hover, .btn-mint-outline:focus {
    background: #059669;
    color: #fff;
    text-decoration: none;
}

</style>







@section('content')
    <div class="container-fluid" style="padding: 24px;">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{ !empty($recovery) ? __('Recovery') : __('Popups') }}</h1>
            <div class="title-actions">
                @if (empty($recovery))
                    <a href="{{ route('popup.admin.create') }}" class=" btn-mint-outline">
                        <i class="fa fa-plus-circle"></i>
                        {{ __('New Popup') }}
                    </a>
                @endif
            </div>
        </div>
        @include('admin.message')
        <div class="filter-div d-flex justify-content-between ">
            <div class="col-left">
                @if (false && !empty($rows))
                    <form method="post" action="{{ route('popup.admin.bulkEdit') }}"
                        class="filter-form filter-form-left d-flex justify-content-start">
                        {{ csrf_field() }}
                        <select name="action" class="form-control">
                            <option value="">{{ __(' Bulk Actions ') }}</option>

                            @if (!empty($recovery))
                                <option value="recovery">{{ __(' Recovery ') }}</option>
                                <option value="permanently_delete">{{ __('Permanently delete') }}</option>
                            @else
                                <option value="publish">{{ __(' Publish ') }}</option>
                                <option value="draft">{{ __(' Move to Draft ') }}</option>
                                <option value="pending">{{ __('Move to Pending') }}</option>
                                <option value="clone">{{ __(' Clone ') }}</option>
                                <option value="delete">{{ __(' Delete ') }}</option>
                            @endif
                        </select>
                        <button data-confirm="{{ __('Do you want to delete?') }}"
                            class="btn-info btn btn-icon dungdt-apply-form-btn" type="button">{{ __('Apply') }}</button>
                    </form>
                @endif
            </div>
            <div class="col-left">
                @if (false)
                    <form method="get"
                        action="{{ !empty($recovery) ? route('popup.admin.recovery') : route('popup.admin.index') }}"
                        class="filter-form filter-form-right d-flex justify-content-end flex-column flex-sm-row"
                        role="search">
                        <input type="text" name="s" value="{{ Request()->s }}"
                            placeholder="{{ __('Search by name') }}" class="form-control">
                        <button class="btn-info btn btn-icon btn_search" type="submit">{{ __('Search') }}</button>
                    </form>
                @endif
            </div>
        </div>
        <div class="text-right d-none">
            <p><i>{{ __('Found :total items', ['total' => $rows->total()]) }}</i></p>
        </div>
        <div class="panel">
            <div class="panel-body">
                <form action="" class="bravo-form-item">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    {{-- <th width="60px"><input type="checkbox" class="check-all"></th> --}}
                                    <th> {{ __('Name') }}</th>
                                    <th width="100px"> {{ __('Status') }}</th>
                                    <th width="100px"> {{ __('Date') }}</th>
                                    <th width="100px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($rows->total() > 0)
                                    @foreach ($rows as $row)
                                        <tr class="{{ $row->status }}">
                                            <td><input type="checkbox" name="ids[]" class="check-item"
                                                    value="{{ $row->id }}">
                                            </td>
                                            <td class="title">
                                                <a
                                                    href="{{ route('popup.admin.edit', ['id' => $row->id]) }}">{{ $row->title }}</a>
                                            </td>
                                            <td><span class="badge badge-{{ $row->status }}">{{ $row->status }}</span>
                                            </td>
                                            <td>{{ display_date($row->updated_at) }}</td>
                                            <td>
                                                <a href="{{ route('popup.admin.edit', ['id' => $row->id]) }}"
                                                    class="btn btn-primary btn-sm"><i class="fa fa-edit"></i>
                                                    {{ __('Edit') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7">{{ __('No popup found') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </form>
                {{ $rows->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection

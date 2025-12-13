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
    body.dark-mode .panel {
        background: #132438 !important;
        border-radius: 17px !important;
    }

    .panel-body,
    .panel {

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

    .btn-mint-outline:hover,
    .btn-mint-outline:focus {
        background: #059669;
        color: #fff;
        text-decoration: none;
    }

    /* Toggle Switch Styles */
    .toggle-switch {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 12px;
    }

    .toggle-switch input[type="checkbox"] {
        display: none;
    }

    .toggle-switch-slider {
        position: relative;
        width: 70px;
        height: 32px;
        background-color: #ccc;
        border-radius: 50px;
        cursor: pointer;
        transition: background-color 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .toggle-switch input[type="checkbox"]:checked+.toggle-switch-slider {
        background-color: #059669;
    }

    .toggle-switch-slider:before {
        content: '';
        position: absolute;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background-color: white;
        top: 3px;
        left: 3px;
        transition: transform 0.3s;
        z-index: 2;
    }

    .toggle-switch input[type="checkbox"]:checked+.toggle-switch-slider:before {
        transform: translateX(38px);
    }

    .toggle-text {
        position: absolute;
        font-size: 11px;
        font-weight: 700;
        color: white;
        z-index: 1;
        transition: opacity 0.3s;
        text-transform: uppercase;
    }

    .toggle-text-no {
        right: 8px;
    }

    .toggle-text-yes {
        left: 8px;
        opacity: 0;
    }

    .toggle-switch input[type="checkbox"]:checked~.toggle-text-no {
        opacity: 0;
    }

    .toggle-switch input[type="checkbox"]:checked~.toggle-text-yes {
        opacity: 1;
    }

    body.dark-mode .toggle-switch-slider {
        background-color: #ccc;
    }

    body.dark-mode .toggle-switch input[type="checkbox"]:checked+.toggle-switch-slider {
        background-color: #059669;
    }

    .toggle-switch-label {
        font-weight: 700;
        color: #333;
        margin-bottom: 12px;
        font-size: 14px;
    }

    body.dark-mode .toggle-switch-label {
        color: #e0e0e0;
    }

    .publish-controls {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 15px 0;
    }

    .publish-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .publish-left label {
        font-size: 15px;
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    body.dark-mode .publish-left label {
        color: #e0e0e0;
    }

    .btn-mint-outline {
        background: transparent;
        border: 2px solid #059669;
        color: #059669;
        padding: 8px 24px;
        border-radius: 15px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: 0.3s;
        text-decoration: none;
    }

    body.dark-mode .main-content {
    min-height: auto !important;
}


</style>


@section('content')
    <form action="{{ route('popup.admin.store', ['id' => $row->id ? $row->id : '-1', 'lang' => request()->query('lang')]) }}" method="post">
        @csrf
        
        {{-- Main Container --}}
        <div class="container-fluid" style="padding: 24px;">
            
            {{-- Header Section --}}
            <div class="d-flex justify-content-between mb20">
                <div class="">
                    <h1 class="title-bar">{{ $row->id ? __('Edit: ') . $row->title : __('Add new popup') }}</h1>
                </div>
                <div class="">
                    @if ($row->id)
                        <a class="btn btn-primary btn-sm" href="{{ $row->getDetailUrl(request()->query('lang')) }}" target="_blank">{{ __('Preview') }}</a>
                    @endif
                </div>
            </div>
            {{-- End Header --}}
            
            @include('admin.message')
            @if ($row->id)
                @include('Language::admin.navigation')
            @endif
            
            {{-- Main Content Box --}}
            <div class="lang-content-box">
                <div class="row">
                    
                    {{-- Left Column (Form Content) --}}
                    <div class="col-md-9">
                        @include('Popup::admin.popup.content')
                        @if (is_default_lang())
                            @include('Popup::admin.popup.conditions')

                            @include('Popup::admin.popup.schedule')
                        @endif
                    </div>
                    {{-- End Left Column --}}
                    
                    {{-- Right Column (Publish Panel) --}}
                    <div class="col-md-3">
                        <div class="panel">
                            <div class="panel-body">
                                @if (is_default_lang())
                                    <div class="publish-controls">
                                        <div class="publish-left">
                                            <label>{{ __('Active') }}</label>
                                            <label for="publish_toggle" class="toggle-switch">
                                                <input type="checkbox" id="publish_toggle" name="status_toggle" @if ($row->status == 'publish') checked @endif>
                                                <span class="toggle-switch-slider">
                                                    <span class="toggle-text toggle-text-yes">YES</span>
                                                    <span class="toggle-text toggle-text-no">NO</span>
                                                </span>
                                            </label>
                                        </div>
                                        <button class="btn-mint-outline" type="submit" style="margin-left: auto;display:inline-flex;align-items:center;gap:8px;font-weight:600;font-size:15px;">
                                            <i class="fa fa-save" style="font-size:18px;"></i>
                                            {{ __('save changes') }}
                                        </button>
                                    </div>
                                    <input type="hidden" id="status_hidden" name="status" value="{{ $row->status }}">
                                @endif
                            </div> {{-- End panel-body --}}
                        </div> {{-- End panel --}}
                    </div> {{-- End col-md-3 --}}
                    {{-- End Right Column --}}
                    
                </div> {{-- End row --}}
            </div> {{-- End lang-content-box --}}
            
        </div> {{-- End container-fluid --}}
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('publish_toggle');
            const statusHidden = document.getElementById('status_hidden');

            if (toggle && statusHidden) {
                toggle.addEventListener('change', function() {
                    statusHidden.value = this.checked ? 'publish' : 'draft';
                });
            }
        });
    </script>


@endsection

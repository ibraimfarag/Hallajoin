@extends ('admin.layouts.app')

@push('head')
    <style>

        .widget-user .user-name,
        .widget-user .user-role {
            font-weight: normal;
        }

        body.dark-mode .container-fluid {
            background: #0f1c2e
        }

        .main-breadcrumb {
            display: none !important;
        }

        body {
            font-weight: bold;
        }

        /* Light Mode (Default) */
        .enquiry-filter-container {
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .enquiry-table-container {
            background-color: #ffffff;
            border-radius: 20px;
            overflow: hidden;
        }

        .enquiry-filter-bar {
            background-color: #f8f9fa;
            padding: 20px 28px;
            border-bottom: 1px solid #dee2e6;
        }

        .enquiry-filter-bar .form-control {
            background-color: #ffffff;
            border: 1px solid #ced4da;
            color: #495057;
            height: 40px;
            border-radius: 13px !important;
            padding: 0 16px;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .enquiry-filter-bar .form-control::placeholder {
            color: #6c757d;
        }

        .enquiry-filter-bar .form-control:focus {
            /* background-color: #ffffff; */
            border-color: #80bdff;
            color: #495057;
            outline: none;
        }

        .enquiry-filter-bar select.form-control {
            appearance: none;
            background-repeat: no-repeat;
            background-position: right 16px center;
            padding-right: 40px;
        }

        .enquiry-filter-bar .btn {
            height: 35px;
            padding: 0 24px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.8px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .enquiry-filter-bar .btn-primary {
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
            border: none;
            color: white;
        }

        .enquiry-filter-bar .btn-primary:hover {
            background: linear-gradient(135deg, #6d28d9 0%, #5b21b6 100%);
            transform: translateY(-2px);
        }

        .enquiry-filter-bar .btn-secondary {
            background-color: #6c757d;
            border: 1px solid #6c757d;
            color: #ffffff;
        }

        .enquiry-filter-bar .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
            color: #ffffff;
        }

        .enquiry-table-header {
            background-color: #ffffff;
            color: #495057;
            padding: 16px 28px;
            border-bottom: 1px solid #dee2e6;
            font-weight: 600;
            font-size: large;
            letter-spacing: 0.4px;
        }

        .enquiry-table {
            background-color: #ffffff !important;
            color: #495057 !important;
            margin-bottom: 0;
        }

        .enquiry-table thead tr {
            border-bottom: 1px solid #dee2e6;
            background-color: #ffffff;
        }

        .enquiry-table thead th {
            color: #6c757d !important;

            font-size: 11px;

            border: none;
            white-space: nowrap;
            background-color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 22px 18px;
            font-weight: 500 !important;

        }

        .enquiry-table thead th:first-child {
            padding-left: 28px;
        }

        .enquiry-table tbody tr {
            border-bottom: 1px solid #dee2e6;
            background-color: #ffffff;
            transition: background-color 0.2s ease;
        }

        .enquiry-table tbody tr:hover {
            background-color: #f8f9fa !important;
        }

        .enquiry-table tbody td {
            color: #495057 !important;
            padding: 12px 23px !important;
            border: none;
            vertical-align: middle;
            font-size: medium;
        }

        .enquiry-table tbody td:first-child {
            padding-left: 28px;
            color: #6c757d !important;
            font-weight: 600;
        }

        .enquiry-activity-link {
            color: #6366f1 !important;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.2s ease;
        }

        .enquiry-activity-link:hover {
            color: #818cf8 !important;
            text-decoration: underline;
        }

        .enquiry-table .badge {
            padding: 6px 14px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 5px;
            letter-spacing: 0.4px;
        }

        .enquiry-table .badge-info {
            background-color: #17a2b8;
            color: white;
        }

        .enquiry-table .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .enquiry-table .badge-secondary {
            background-color: #6c757d;
            color: white;
        }

        .enquiry-operation-btns {
            display: flex;
            gap: 8px;
        }

        .enquiry-operation-btns .btn {
            width: 36px;
            height: 36px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            position: relative;
            transition: all 0.2s ease;
        }

        .enquiry-operation-btns .btn-primary {
            background-color: #e9ecef;
            border: none;
            color: #495057;
        }

        .enquiry-operation-btns .btn-primary:hover {
            background-color: transparent;
            /* color: #212529; */
            transform: translateY(-2px);
        }

        .enquiry-operation-btns .btn-info {
            background-color: #e9ecef;
            border: none;
            color: #495057;
        }

        .enquiry-operation-btns .btn-info:hover {
            background-color: #dee2e6;
            color: #212529;
            transform: translateY(-2px);
        }

        .enquiry-operation-btns .badge-light {
            position: absolute;
            top: -4px;
            right: -2px;
            background-color: #7c3aed;
            color: white;
            font-size: 10px;
            font-weight: 700;
            padding: 3px 6px;
            border-radius: 12px;
            min-width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .enquiry-table-container .enquiry-table-container .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            transition: opacity 0.2s ease;
        }

        .table-responsive {

            overflow: hidden !important;
        }

        .enquiry-table-container .user-info:hover {
            opacity: 0.8;
        }

        .enquiry-table-container .enquiry-table-container .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #dee2e6;
            transition: border-color 0.2s ease;
        }

        .enquiry-table-container.enquiry-table-container .user-avatar-letter {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            border: 2px solid #dee2e6;
            transition: border-color 0.2s ease;
            flex-shrink: 0;
        }

        .enquiry-table-container.user-info:hover .user-avatar {
            border-color: #7c3aed;
        }

        .enquiry-table-container .user-info:hover .user-avatar-letter {
            border-color: #7c3aed;
        }

        .enquiry-table tbody td .user-info>div>div:last-child {
            color: #6c757d !important;
        }

        .enquiry-table tbody td>div>div:last-child {
            color: #6c757d !important;
        }

        .enquiry-table-container .user-name {
            font-weight: 600;
            color: inherit;
        }

       .enquiry-table-container .user-phone {
            font-size: small;
            color: #6c757d;
            margin-top: 2px;
        }

        /* Dark Mode */
        body.dark-mode .enquiry-filter-container,
        body.dark-mode-instant .enquiry-filter-container {
            background-color: #122438;
        }

        body.dark-mode .enquiry-table-container,
        body.dark-mode-instant .enquiry-table-container {
            background-color: #122438;
            border-radius: 20px;
        }

        body.dark-mode .enquiry-filter-bar,
        body.dark-mode-instant .enquiry-filter-bar {
            background-color: #132438;
            border-bottom: 0;
        }

        body.dark-mode .enquiry-filter-bar .form-control,
        body.dark-mode-instant .enquiry-filter-bar .form-control {
            background-color: transparent;
            border: 1px solid #264b77;
            color: #ffffff;
        }

        body.dark-mode .enquiry-filter-bar .form-control::placeholder,
        body.dark-mode-instant .enquiry-filter-bar .form-control::placeholder {
            color: #4a5a6d;
        }

        body.dark-mode .enquiry-filter-bar .form-control:focus,
        body.dark-mode-instant .enquiry-filter-bar .form-control:focus {
            background-color: transparent;
            border-color: #3a4a5d;
            color: #ffffff;
        }

        body.dark-mode .enquiry-filter-bar select.form-control option,
        body.dark-mode-instant .enquiry-filter-bar select.form-control option {
            background-color: #0f1a2b;
            color: #ffffff;
        }



        body.dark-mode .enquiry-filter-bar .btn-secondary,
        body.dark-mode-instant .enquiry-filter-bar .btn-secondary {
            background-color: #374151;
            border: 1px solid #4b5563;
            color: #e5e7eb;
        }

        body.dark-mode .enquiry-filter-bar .btn-secondary:hover,
        body.dark-mode-instant .enquiry-filter-bar .btn-secondary:hover {
            background-color: #4b5563;
            border-color: #6b7280;
            color: #ffffff;
        }

        body.dark-mode .enquiry-table-header,
        body.dark-mode-instant .enquiry-table-header {
            background-color: #122438;
            color: #ffffff;
            border-bottom: 1px solid #2a3a4d;
        }

        body.dark-mode .enquiry-table,
        body.dark-mode-instant .enquiry-table {
            background-color: #122438 !important;
            color: #ffffff !important;
        }

        body.dark-mode .enquiry-table thead tr,
        body.dark-mode-instant .enquiry-table thead tr {
            border-bottom: 1px solid #2a3a4d;
            background-color: #122438;
        }

        body.dark-mode .enquiry-table thead th,
        body.dark-mode-instant .enquiry-table thead th {
            color: #8b92a7 !important;
            background-color: #ffffff08;

            padding: 22px 18px;
            font-weight: 500 !important;

        }

        body.dark-mode .enquiry-table tbody tr,
        body.dark-mode-instant .enquiry-table tbody tr {
            border-bottom: 1px solid #2a3a4d;
            background-color: #122438;
        }

        body.dark-mode .enquiry-table tbody tr:hover,
        body.dark-mode-instant .enquiry-table tbody tr:hover {
            background-color: #242f3d !important;
        }

        body.dark-mode .enquiry-table tbody td,
        body.dark-mode-instant .enquiry-table tbody td {
            color: #ffffff !important;

        }

        body.dark-mode .enquiry-table tbody td:first-child,
        body.dark-mode-instant .enquiry-table tbody td:first-child {
            color: #ffffff !important;
        }

        body.dark-mode .enquiry-activity-link,
        body.dark-mode-instant .enquiry-activity-link {
            color: #818cf8 !important;
        }

        body.dark-mode .enquiry-activity-link:hover,
        body.dark-mode-instant .enquiry-activity-link:hover {
            color: #a5b4fc !important;
        }

        body.dark-mode .enquiry-table .badge-info,
        body.dark-mode-instant .enquiry-table .badge-info {
            background-color: #3b82f6;
            color: white;
        }

        body.dark-mode .enquiry-table .badge-warning,
        body.dark-mode-instant .enquiry-table .badge-warning {
            background-color: #f59e0b;
            color: white;
        }

        body.dark-mode .enquiry-table .badge-secondary,
        body.dark-mode-instant .enquiry-table .badge-secondary {
            background-color: #374151;
            color: #9ca3af;
        }

        body.dark-mode .enquiry-operation-btns .btn-primary,
        body.dark-mode-instant .enquiry-operation-btns .btn-primary {
            background-color: transparent;
            color: #ffffffa3;
        }

        body.dark-mode .enquiry-operation-btns .btn-primary:hover,
        body.dark-mode-instant .enquiry-operation-btns .btn-primary:hover,
        body.dark-mode .btn-primary:hover {
            background-color: transparent;
            background: transparent;
            color: #c5d5e8;

            box-shadow: none !important;
        }

        body.dark-mode .enquiry-operation-btns .btn-info,
        body.dark-mode-instant .enquiry-operation-btns .btn-info {
            background-color: #2a3a4d;
            color: #ffffff;
        }

        body.dark-mode .enquiry-operation-btns .btn-info:hover,
        body.dark-mode-instant .enquiry-operation-btns .btn-info:hover {
            background-color: #3a4a5d;
            color: #c5d5e8;
        }

        body.dark-mode .enquiry-operation-btns .badge-light,
        body.dark-mode-instant .enquiry-operation-btns .badge-light {
            background-color: #e74c3c;
        }

        body.dark-mode .close {
            color: #ffffff;
        }

        body.dark-mode .enquiry-table-container .user-avatar,
        body.dark-mode-instant .enquiry-table-container .user-avatar {
            border: 2px solid #2a3a4d;
        }

        body.dark-mode .enquiry-table-container .user-avatar-letter,
        body.dark-mode-instant .enquiry-table-container .user-avatar-letter {
            border: 2px solid #2a3a4d;
            background: linear-gradient(135deg, #818cf8 0%, #6366f1 100%);
        }

        body.dark-mode .enquiry-table-container .user-info:hover .user-avatar,
        body.dark-mode-instant .enquiry-table-container .user-info:hover .user-avatar {
            border-color: #818cf8;
        }

        body.dark-mode .enquiry-table-container .user-info:hover .user-avatar-letter,
        body.dark-mode-instant .enquiry-table-container .user-info:hover .user-avatar-letter {
            border-color: #a5b4fc;
        }

        body.dark-mode .enquiry-table tbody td .user-info>div>div:last-child,
        body.dark-mode-instant .enquiry-table tbody td .user-info>div>div:last-child {
            color: #5a6a7d !important;
        }

        body.dark-mode .enquiry-table tbody td>div>div:last-child,
        body.dark-mode-instant .enquiry-table tbody td>div>div:last-child {
            color: #5a6a7d !important;
        }

        body.dark-mode .enquiry-table-container .user-name,
        body.dark-mode-instant .enquiry-table-container .user-name {
            color: #ffffff !important;
        }

        body.dark-mode .enquiry-table-container .user-phone,
        body.dark-mode-instant .enquiry-table-container .user-phone {
            color: #60a5fa !important;
        }


        .table thead th {
            text-transform: none !important;
            font-size: 18px !important;
            font-weight: 900 !important;
        }

        /* Modal Styles */
        .enquiry-modal .modal-dialog {
            max-width: 500px;
        }

        .enquiry-modal .modal-content {
            border-radius: 12px;
            border: none;
        }

        .enquiry-modal .modal-header {
            background: transparent;
            color: #495057;
            border-radius: 12px 12px 0 0;
            padding: 16px 24px;
            border-bottom: 1px solid #dee2e6;
        }

        .enquiry-modal .modal-header .modal-title {
            font-size: 16px;
            font-weight: 700;
        }

        .enquiry-modal .modal-header .close {
            color: #495057;
            opacity: 0.7;
            font-size: 24px;
            font-weight: 300;
            text-shadow: none;
        }

        .enquiry-modal .modal-header .close:hover {
            opacity: 1;
        }

        /* Dark Mode for Modal */
        body.dark-mode .enquiry-modal .modal-content,
        body.dark-mode-instant .enquiry-modal .modal-content {
            background-color: #122438;
            color: #ffffff;
        }

        body.dark-mode .enquiry-modal .modal-header,
        body.dark-mode-instant .enquiry-modal .modal-header {
            background: transparent;
            color: #ffffff;
            border-bottom: 1px solid #2a3a4d;
        }

        body.dark-mode .enquiry-modal .modal-header .close,
        body.dark-mode-instant .enquiry-modal .modal-header .close {
            color: #ffffff;
        }

        body.dark-mode .enquiry-modal .modal-body,
        body.dark-mode-instant .enquiry-modal .modal-body {
            color: #ffffff;
        }

        /* Notes Modal Styles */
        .notes-modal-header {
            background-color: #f8f9fa;
            padding: 20px 24px;
            border-bottom: 1px solid #dee2e6;
        }

        .notes-modal-title {
            font-size: 18px;
            font-weight: 600;
            color: #1a202c;
            margin: 0;
        }

        .notes-timeline {
            padding: 24px;
            max-height: 500px;
            overflow-y: auto;
        }

        .timeline-item {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
            position: relative;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 40px;
            bottom: -24px;
            width: 2px;
            background: linear-gradient(180deg, #818cf8 0%, #6366f1 100%);
        }

        .timeline-item:last-child::before {
            display: none;
        }

        .timeline-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #818cf8 0%, #6366f1 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
            flex-shrink: 0;
            z-index: 1;
        }

        .timeline-content {
            flex: 1;
            background-color: #f8f9fa;
            border-radius: 12px;
            padding: 16px;
        }

        .timeline-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .timeline-author {
            font-weight: 600;
            font-size: 14px;
            color: #1a202c;
        }

        .timeline-date {
            font-size: 12px;
            color: #6c757d;
        }

        .timeline-text {
            font-size: 14px;
            line-height: 1.6;
            color: #495057;
            white-space: pre-wrap;
        }

        .timeline-attachment {
            margin-top: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
            color: #4a5568;
            text-decoration: none;
            transition: all 0.2s;
        }

        .timeline-attachment:hover {
            background-color: #f7fafc;
            border-color: #818cf8;
            color: #6366f1;
            text-decoration: none;
        }

        .timeline-attachment i {
            font-size: 16px;
        }

        /* Note Input Area */
        .note-input-container {
            padding: 24px;
            background-color: #ffffff;
            border-top: 1px solid #e2e8f0;
        }

        .mention-btn:hover {
            background: #6366f1 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(129, 140, 248, 0.4);
        }

        .file-select-btn:hover {
            /* background: #f7fafc !important; */
            border-color: #6366f1 !important;
            transform: translateY(-1px);
        }

        .create-note-btn:hover {
            background: #3b82f6 !important;
            transform: translateY(-2px);

            padding: 8px 24px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .create-note-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .note-textarea:focus {
            outline: none;
            border-color: #818cf8 !important;
        }

        .note-input-wrapper {
            background-color: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            transition: border-color 0.2s;
        }

        .note-input-wrapper:focus-within {
            border-color: #818cf8;
        }

        .note-textarea {
            width: 100%;
            border: none;
            outline: none;
            resize: none;
            font-size: 14px;
            line-height: 1.6;
            color: #1a202c;
            min-height: 80px;
        }

        .note-textarea::placeholder {
            color: #a0aec0;
        }

        .note-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
        }

        .note-tools {
            display: flex;
            gap: 8px;
        }

        .note-tool-btn {
            width: 36px;
            height: 36px;
            border: none;
            background-color: #f7fafc;
            color: #4a5568;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .note-tool-btn:hover {
            background-color: #edf2f7;
            color: #6366f1;
        }

        .note-tool-btn input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .note-submit-btn {
            background: linear-gradient(135deg, #818cf8 0%, #6366f1 100%);
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .note-submit-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        }

        .note-submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .mention-dropdown {
            position: absolute;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }

        .mention-item {
            padding: 10px 12px;
            cursor: pointer;
            transition: all 0.2s;
            border-radius: 6px;
            margin: 4px 8px;
        }

        .mention-item:hover {
            background-color: #f7fafc;
            transform: translateX(2px);
        }

        .mention-highlight {
            background-color: #ddd6fe;
            color: #6366f1;
            padding: 2px 4px;
            border-radius: 4px;
            font-weight: 500;
        }

        /* Dark Mode */
        body.dark-mode .notes-modal-header,
        body.dark-mode-instant .notes-modal-header {
            background-color: transparent;
            border-bottom-color: #2a3a4d;
        }

        body.dark-mode .notes-modal-title,
        body.dark-mode-instant .notes-modal-title {
            color: #ffffff;
        }

        body.dark-mode .timeline-content,
        body.dark-mode-instant .timeline-content {
            background-color: #ffffff05;
        }

        body.dark-mode .timeline-author,
        body.dark-mode-instant .timeline-author {
            color: #ffffff;
        }

        body.dark-mode .timeline-text,
        body.dark-mode-instant .timeline-text {
            color: #e2e8f0;
        }

        body.dark-mode .timeline-date,
        body.dark-mode-instant .timeline-date {
            color: #9ca3af;
        }

        body.dark-mode .timeline-attachment,
        body.dark-mode-instant .timeline-attachment {
            background-color: #0f1a2b;
            border-color: #2a3a4d;
            color: #cbd5e0;
        }

        body.dark-mode .timeline-attachment:hover,
        body.dark-mode-instant .timeline-attachment:hover {
            background-color: #1a2938;
            border-color: #818cf8;
        }

        body.dark-mode .note-input-container,
        body.dark-mode-instant .note-input-container {
            background-color: transparent;
            border-top-color: #2a3a4d;
        }

        body.dark-mode .note-textarea,
        body.dark-mode-instant .note-textarea {
            background-color: #1a2332;
            color: #ffffff;
            border-color: #2a3a4d;
        }

        body.dark-mode .note-textarea:focus,
        body.dark-mode-instant .note-textarea:focus {
            border-color: #818cf8 !important;
        }

        body.dark-mode .mention-btn,
        body.dark-mode-instant .mention-btn {
            background: #6366f1 !important;
        }

        body.dark-mode .file-select-btn,
        body.dark-mode-instant .file-select-btn {
            color: #fff !important;

        }

        body.dark-mode div[id^="selectedFile"],
        body.dark-mode-instant div[id^="selectedFile"] {
            background: #1a2332 !important;
            color: #e2e8f0 !important;
        }

        body.dark-mode .note-input-wrapper,
        body.dark-mode-instant .note-input-wrapper {
            background-color: #0f1a2b;
            border-color: #2a3a4d;
        }

        body.dark-mode .note-input-wrapper:focus-within,
        body.dark-mode-instant .note-input-wrapper:focus-within {
            border-color: #818cf8;
        }

        body.dark-mode .note-textarea,
        body.dark-mode-instant .note-textarea {
            background-color: transparent;
            color: #ffffff;
        }

        body.dark-mode .note-textarea::placeholder,
        body.dark-mode-instant .note-textarea::placeholder {
            color: #6b7280;
        }

        body.dark-mode .note-actions,
        body.dark-mode-instant .note-actions {
            border-top-color: #2a3a4d;
        }

        body.dark-mode .note-tool-btn,
        body.dark-mode-instant .note-tool-btn {
            background-color: #1a2938;
            color: #cbd5e0;
        }

        body.dark-mode .note-tool-btn:hover,
        body.dark-mode-instant .note-tool-btn:hover {
            background-color: #2a3a4d;
            color: #818cf8;
        }

        body.dark-mode .mention-dropdown,
        body.dark-mode-instant .mention-dropdown {
            background-color: #1a2332;
            border-color: #2a3a4d;
        }

        body.dark-mode .mention-item:hover,
        body.dark-mode-instant .mention-item:hover {
            background-color: #0f1a2b;
        }

        body.dark-mode .mention-item div[style*="color: #1a202c"],
        body.dark-mode-instant .mention-item div[style*="color: #1a202c"] {
            color: #ffffff !important;
        }
    </style>
@endpush

@section ('content')
    <div class="container-fluid">
        <h1 style="    font-size: 24px;
        font-weight: 700;    margin: 2px -1px 21px;
    padding-top: 26px;" class="orders-title">{{ __('Inquiries') }}</h1>

        <!-- Filter Container -->
        <div class="enquiry-filter-container">
            <div class="enquiry-filter-bar">
                <form method="get" action="">
                    <div class="d-flex align-items-center" style="gap: 15px; margin-bottom: 12px;">
                        <input type="text" name="s" value="{{ Request()->s }}" placeholder="{{__('Search')}}"
                            class="form-control" style="width: 165px;">

                        <select name="salesman" class="form-control" style="width: 165px;">
                            <option value="" disabled selected hidden>{{__('Salesman')}}</option>
                            <option value="">{{__('All Salesmen')}}</option>
                            <option value="0">{{__('Not Assigned')}}</option>
                            @if(!empty($salesmen))
                                @foreach($salesmen as $userId => $userName)
                                    <option value="{{$userId}}" {{Request()->salesman == $userId ? 'selected' : ''}}>{{$userName}}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="d-flex align-items-center" style="gap: 10px;">
                        <button class="btn btn-primary" type="submit" style="border-radius: 14px;">{{__('FILTER')}}</button>
                        <a href="{{route('report.admin.enquiry.index')}}" class="btn btn-secondary"
                            style="display: flex; justify-content: center; align-items: center;border-radius: 14px;">{{__('RESET')}}</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Container -->
        <div class="enquiry-table-container">


            <!-- Table Content -->
            <div class="table-responsive">
                <table class="table table-hover enquiry-table">
                    <thead>
                        <tr>
                            <th>{{__('ID')}}</th>
                            <th>{{__('Created On')}}</th>
                            <th>{{__('User')}}</th>
                            <th>{{__('Activity Name')}}</th>
                            <th>{{__('Category')}}</th>
                            <th>{{__('Units')}}</th>
                            <th>{{__('From')}}</th>
                            <th>{{__('Salesman')}}</th>
                            <th>{{__('Operation')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($rows->total() > 0)
                            @foreach($rows as $row)
                                            <tr>
                                                <td>{{$row->id}}</td>
                                                <td>
                                                    <div style="display: flex; flex-direction: column; gap: 2px;">
                                                        <div style="font-weight: 600;"></div>{{date('d/M/Y', strtotime($row->created_at))}}
                                                    </div>
                                                    <div style="font-size: medium; color: #6c757d;">{{date('H:i', strtotime($row->created_at))}}
                                                    </div>
                                </div>
                                </td>
                                <td>
                                    @php
                                        $displayName = $row->name ?: 'Unknown';
                                        $firstLetter = mb_substr($displayName, 0, 1);
                                    @endphp
                                    @if($row->create_user)
                                        <a href="{{route('user.admin.profile', ['id' => $row->id])}}"
                                            style="text-decoration: none; color: inherit;">
                                            <div class="user-info" style="flex-direction: column; align-items: flex-start; gap: 4px;">
                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                    <div class="user-avatar-letter">{{$firstLetter}}</div>
                                                    <div>
                                                        <div class="user-name">{{$displayName}}</div>
                                                        <div class="user-phone">{{$row->phone}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @else
                                        <div class="user-info" style="flex-direction: column; align-items: flex-start; gap: 4px;">
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <div class="user-avatar-letter">{{$firstLetter}}</div>
                                                <div>
                                                    <div class="user-name">{{$displayName}}</div>
                                                    <div class="user-phone">{{$row->phone}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($row->activity_name)
                                        <a href="#" class="enquiry-activity-link">{{$row->activity_name}}</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $categoryName = '-';
                                        if ($row->object_id && $row->object_model) {
                                            $service = $row->service;
                                            if ($service && $service->category_id) {
                                                $category = \Modules\Tour\Models\TourCategory::find($service->category_id);
                                                if ($category) {
                                                    $categoryName = $category->name;
                                                }
                                            }
                                        }
                                    @endphp
                                    {{$categoryName}}
                                </td>
                                <td>{{$row->units ?? '-'}}</td>
                                <td>
                                    <span>{{$row->from_type ?? 'B2C'}}</span>
                                </td>
                                <td>
                                    @if($row->salesman)
                                        @php
                                            $salesmanUser = \App\User::find($row->salesman);
                                            $salesmanName = $salesmanUser ? $salesmanUser->getDisplayName(true) : 'User #' . $row->salesman;
                                        @endphp
                                        <span>{{$salesmanName}}</span>
                                    @else
                                        <span>{{__('Not Assigned')}}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="enquiry-operation-btns">
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#enquiryModal{{$row->id}}" title="{{__('View Details')}}">
                                            <i class="fa fa-file-text"></i>
                                        </button>

                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#notesModal{{$row->id}}"
                                            onclick="loadNotes({{$row->id}})" title="{{__('Notes')}}">
                                            <i class="fa fa-sticky-note"></i>
                                            @if($row->replies_count > 0)
                                                <span class="badge badge-light">{{$row->replies_count}}</span>
                                            @endif
                                        </button>
                                    </div>
                                </td>
                                </tr>

                                <!-- Modal for this enquiry -->
                                <div class="modal fade enquiry-modal" id="enquiryModal{{$row->id}}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">{{__('Description')}}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body" style="padding: 24px;">
                                                <div style="font-size: 15px; line-height: 1.6; margin-bottom: 12px;">
                                                    {{$row->message ?: $row->activity_name ?: __('No description available')}}
                                                </div>
                                                <div style="font-size: 12px; color: #6c757d; margin-top: 8px;">
                                                    {{date('d/M/Y H:i', strtotime($row->created_at))}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Notes Modal -->
                                <div class="modal fade enquiry-modal" id="notesModal{{$row->id}}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
                                            <div class="notes-modal-header">
                                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                                    <h5 class="notes-modal-title"><i class="fa fa-sticky-note"
                                                            style="color: #818cf8; margin-right: 8px;"></i>
                                                        {{__('Notes for Enquiry')}} <span
                                                            style="color: #818cf8; font-weight: 600;">{{$row->id}}</span></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                                        style="margin: 0; padding: 0; opacity: 0.6;">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="note-input-container">
                                                <textarea class="note-textarea" id="noteContent{{$row->id}}" placeholder="{{__('Note')}}"
                                                    rows="4"
                                                    style="width: 100%; border: 2px solid #818cf869; border-radius: 12px; padding: 16px; font-size: 14px; line-height: 1.6; resize: none; margin-bottom: 16px;"></textarea>

                                                <div style="display: flex; align-items: center; gap: 12px;">
                                                    <button type="button" class="mention-btn" onclick="insertMention({{$row->id}})"
                                                        style="background: #818cf8; color: white; border: none; padding: 10px 20px; border-radius: 20px; font-size: 14px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
                                                        <i class="fa fa-at"></i> {{__('Mention')}}
                                                    </button>

                                                    <label for="noteFile{{$row->id}}" class="file-select-btn"
                                                        style="padding: 10px 20px; cursor: pointer; ">
                                                        <i class="fa fa-paperclip"></i>
                                                    </label>
                                                    <input type="file" id="noteFile{{$row->id}}" onchange="handleFileSelect({{$row->id}})"
                                                        accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="display: none;">

                                                    <div style="flex: 1;"></div>

                                                    <button type="button" class="create-note-btn" onclick="addNote({{$row->id}})"
                                                        style="background: #3b82f6;color: #ffffff;border: none;padding: 8px 24px;border-radius: 20px;font-size: 13px;font-weight: 600;cursor: pointer;transition: all 0.2s 
                                                                                                                                ease;margin-left: auto;text-transform: uppercase;letter-spacing: 0.5px;">
                                                        {{__('ADD NOTE')}}
                                                    </button>
                                                </div>

                                                <div id="selectedFile{{$row->id}}"
                                                    style="margin-top: 12px; display: none; padding: 8px 12px; background: #f7fafc; border-radius: 8px;">
                                                    <span style="font-size: 13px; color: #4a5568;">
                                                        <i class="fa fa-file" style="color: #818cf8;"></i>
                                                        <span id="fileName{{$row->id}}" style="margin-left: 8px;"></span>
                                                        <a href="javascript:void(0)" onclick="removeFile({{$row->id}})"
                                                            style="margin-left: 12px; color: #dc3545; text-decoration: none; font-weight: 600;">
                                                            <i class="fa fa-times"></i>
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="notes-timeline" id="notesList{{$row->id}}">
                                                <div class="text-center" style="padding: 40px 20px;">
                                                    <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #818cf8;"></i>
                                                    <p style="margin-top: 12px; color: #6c757d;">{{__('Loading...')}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                <tr>
                    <td colspan="9" class="text-center">{{__("No data")}}</td>
                </tr>
            @endif
            </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end" style="padding: 20px 28px;">
            {{$rows->links()}}
        </div>
    </div>
    </div>

    @push('js')
        <script>
            let selectedFiles = {};
            let allUsers = [];
            let currentEnquiryId = null;

            // Load users for mention on page load
            fetch('/admin/module/report/enquiry/users-for-mention', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        allUsers = data.users;
                    }
                });

            function loadNotes(enquiryId) {
                currentEnquiryId = enquiryId;
                const notesList = document.getElementById('notesList' + enquiryId);
                notesList.innerHTML = '<div class="text-center" style="padding: 40px 20px;"><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #818cf8;"></i><p style="margin-top: 12px; color: #6c757d;">{{__("Loading...")}}</p></div>';

                fetch('/admin/module/report/enquiry/' + enquiryId + '/notes', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (data.notes.length === 0) {
                                notesList.innerHTML = '<div class="text-center" style="padding: 40px 20px;"><i class="fa fa-sticky-note-o" style="font-size: 48px; margin-bottom: 15px; display: block;color: #999999;"></i><p style="margin-top: 16px; color: #6c757d; font-size: 14px;">{{__("No notes yet")}}</p></div>';
                            } else {
                                notesList.innerHTML = data.notes.map(note => {
                                    const firstLetter = note.user_name.charAt(0).toUpperCase();
                                    const formattedContent = note.content.replace(/@(\w+)/g, '<span class="mention-highlight">@$1</span>');

                                    let attachmentHtml = '';
                                    if (note.attachment) {
                                        const fileName = note.attachment.split('/').pop();
                                        const fileExt = fileName.split('.').pop().toLowerCase();
                                        let fileIcon = 'fa-file';
                                        if (fileExt === 'pdf') fileIcon = 'fa-file-pdf-o';
                                        else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExt)) fileIcon = 'fa-file-image-o';
                                        else if (['doc', 'docx'].includes(fileExt)) fileIcon = 'fa-file-word-o';

                                        attachmentHtml = `
                                                                                                                            <a href="${note.attachment}" target="_blank" class="timeline-attachment">
                                                                                                                                <i class="fa ${fileIcon}"></i>
                                                                                                                                <span>${fileName}</span>
                                                                                                                            </a>
                                                                                                                        `;
                                    }

                                    return `
                                                                                                                        <div class="timeline-item">
                                                                                                                            <div class="timeline-avatar">${firstLetter}</div>
                                                                                                                            <div class="timeline-content">
                                                                                                                                <div class="timeline-header">
                                                                                                                                    <span class="timeline-author">${note.user_name}</span>
                                                                                                                                    <span class="timeline-date">${note.created_at}</span>
                                                                                                                                </div>
                                                                                                                                <div class="timeline-text">${formattedContent}</div>
                                                                                                                                ${attachmentHtml}
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    `;
                                }).join('');
                            }
                        }
                    })
                    .catch(error => {
                        notesList.innerHTML = '<div class="alert alert-danger" style="margin: 20px;">{{__("Error loading notes")}}</div>';
                        console.error('Error:', error);
                    });
            }

            function handleFileSelect(enquiryId) {
                const fileInput = document.getElementById('noteFile' + enquiryId);
                const file = fileInput.files[0];

                if (file) {
                    selectedFiles[enquiryId] = file;
                    document.getElementById('fileName' + enquiryId).textContent = file.name;
                    document.getElementById('selectedFile' + enquiryId).style.display = 'block';
                }
            }

            function removeFile(enquiryId) {
                selectedFiles[enquiryId] = null;
                document.getElementById('noteFile' + enquiryId).value = '';
                document.getElementById('selectedFile' + enquiryId).style.display = 'none';
            }

            function insertMention(enquiryId) {
                const textarea = document.getElementById('noteContent' + enquiryId);

                // Show dropdown with users
                showMentionDropdown(enquiryId, textarea);
            }

            function showMentionDropdown(enquiryId, textarea) {
                // Remove existing dropdown
                const existingDropdown = document.getElementById('mentionDropdown' + enquiryId);
                if (existingDropdown) {
                    existingDropdown.remove();
                }

                // Create dropdown
                const dropdown = document.createElement('div');
                dropdown.id = 'mentionDropdown' + enquiryId;
                dropdown.className = 'mention-dropdown';
                dropdown.style.display = 'block';
                dropdown.style.position = 'absolute';
                dropdown.style.bottom = '60px';
                dropdown.style.left = '24px';
                dropdown.style.width = '300px';
                dropdown.style.maxHeight = '250px';

                if (allUsers.length === 0) {
                    dropdown.innerHTML = '<div class="mention-item" style="color: #6c757d; text-align: center;">{{__("No users available")}}</div>';
                } else {
                    dropdown.innerHTML = allUsers.map(user => {
                        const firstLetter = user.name.charAt(0).toUpperCase();
                        const avatarStyle = user.avatar && user.avatar !== ''
                            ? `background-image: url('${user.avatar}'); background-size: cover; background-position: center;`
                            : `background: linear-gradient(135deg, #818cf8 0%, #6366f1 100%);`;

                        return `
                                                                                            <div class="mention-item" onclick="selectMentionUser(${enquiryId}, '${user.name}', ${user.id})" style="display: flex; align-items: center; gap: 12px;">
                                                                                                <div style="width: 36px; height: 36px; border-radius: 50%; ${avatarStyle} display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 14px; flex-shrink: 0;">
                                                                                                    ${user.avatar && user.avatar !== '' ? '' : firstLetter}
                                                                                                </div>
                                                                                                <div style="flex: 1; min-width: 0;">
                                                                                                    <div style="font-weight: 600; font-size: 14px; color: #1a202c; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${user.name}</div>
                                                                                                    <div style="font-size: 11px; color: #9ca3af; margin-top: 2px;">${user.role}</div>
                                                                                                </div>
                                                                                            </div>
                                                                                        `;
                    }).join('');
                }

                // Add close on click outside
                dropdown.addEventListener('click', function (e) {
                    e.stopPropagation();
                });

                textarea.closest('.note-input-container').style.position = 'relative';
                textarea.closest('.note-input-container').appendChild(dropdown);

                // Close dropdown when clicking outside
                setTimeout(() => {
                    document.addEventListener('click', function closeDropdown(e) {
                        if (!dropdown.contains(e.target) && e.target !== dropdown) {
                            dropdown.remove();
                            document.removeEventListener('click', closeDropdown);
                        }
                    });
                }, 100);
            }

            function selectMentionUser(enquiryId, userName, userId) {
                const textarea = document.getElementById('noteContent' + enquiryId);
                const cursorPos = textarea.selectionStart;
                const textBefore = textarea.value.substring(0, cursorPos);
                const textAfter = textarea.value.substring(cursorPos);

                textarea.value = textBefore + '@' + userName + ' ' + textAfter;
                textarea.focus();
                textarea.selectionStart = textarea.selectionEnd = cursorPos + userName.length + 2;

                // Remove dropdown
                const dropdown = document.getElementById('mentionDropdown' + enquiryId);
                if (dropdown) {
                    dropdown.remove();
                }
            } function addNote(enquiryId) {
                const contentInput = document.getElementById('noteContent' + enquiryId);
                const content = contentInput.value.trim();

                if (!content) {
                    alert('{{__("Please enter a note")}}');
                    return;
                }

                const btn = event.target;
                const originalText = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

                const formData = new FormData();
                formData.append('content', content);

                if (selectedFiles[enquiryId]) {
                    formData.append('attachment', selectedFiles[enquiryId]);
                }

                fetch('/admin/module/report/enquiry/' + enquiryId + '/notes/store', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            contentInput.value = '';
                            removeFile(enquiryId);
                            loadNotes(enquiryId);

                            // Update badge count
                            const badge = document.querySelector(`button[data-target="#notesModal${enquiryId}"] .badge`);
                            if (badge) {
                                const count = parseInt(badge.textContent) + 1;
                                badge.textContent = count;
                            }
                        }
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    })
                    .catch(error => {
                        alert('{{__("Error adding note")}}');
                        console.error('Error:', error);
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    });
            }
        </script>
    @endpush
@endsection
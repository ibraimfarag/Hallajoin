@extends('admin.layouts.app')

@section('content')

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loading-overlay">
        <div class="loading-spinner"></div>
    </div>

    <style>
        /* CSS Variables for Dark/Light Mode - Using Admin Panel Colors */
        .sales-container {
            --sales-bg-primary: #ffffff;
            --sales-bg-secondary: #f8f9fa;
            --sales-bg-tertiary: #e9ecef;
            --sales-text-primary: #333333;
            --sales-text-secondary: #6c757d;
            --sales-border-color: #dee2e6;
            --sales-input-bg: #ffffff;
            --sales-input-border: #ced4da;
            --sales-hover-bg: #f1f3f5;
            --sales-success: #28a745;
            --sales-warning: #ffc107;
            --sales-danger: #dc3545;
            --sales-info: #17a2b8;
            --sales-primary: #007bff;
            --sales-expired: #6c757d;
            --sales-shadow: rgba(0, 0, 0, 0.1);
        }

        /* Dark Mode Colors - Matching User Page */
        body.dark-mode .sales-container,
        body.dark-mode-instant .sales-container {
            --sales-bg-primary: transparent;
            --sales-bg-secondary: #132438;
            --sales-bg-tertiary: #122438;
            --sales-text-primary: #ffffff;
            --sales-text-secondary: #c5c5c5;
            --sales-border-color: #1b3d63;
            --sales-input-bg: #122438;
            --sales-input-border: #1b3d63;
            --sales-hover-bg: #1a2f47;
            --sales-success: #42b983;
            --sales-warning: #f39c12;
            --sales-danger: #e74c3c;
            --sales-info: #3498db;
            --sales-primary: #7c3aed;
            --sales-expired: #7f8c8d;
            --sales-shadow: rgba(0, 0, 0, 0.3);
        }

        /* Container */
        .sales-container {
            background: transparent;
            color: var(--sales-text-primary);
            min-height: 100vh;
            padding: 30px;
            transition: all 0.3s ease;
        }

        body.dark-mode .card,
        body.dark-mode .panel,
        body.dark-mode-instant .card,
        body.dark-mode-instant .panel {
            background: transparent;
            box-shadow: none;
        }

        /* Filters Section */
        .sales-filters {
            background: #132438;
            border: none;
            border-radius: 18px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: none;
        }

        body:not(.dark-mode) .sales-filters {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .filter-input,
        .filter-select {
            background: var(--sales-input-bg);
            border: 1.9px solid var(--sales-input-border);
            border-radius: 20px;
            padding: 12px 20px;
            color: var(--sales-text-primary);
            font-size: 15px;
            height: 50px;
            transition: all 0.3s ease;
        }

        .filter-input:focus,
        .filter-select:focus {
            outline: none;
            border-color: var(--sales-primary);
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
        }

        .filter-input::placeholder {
            color: var(--sales-text-secondary);
        }

        .filter-select option {
            background: var(--sales-input-bg);
            color: var(--sales-text-primary);
        }

        body.dark-mode .filter-input,
        body.dark-mode .filter-select,
        body.dark-mode-instant .filter-input,
        body.dark-mode-instant .filter-select {
            background: #122438;
            border: 1.9px solid #1b3d63;
            color: #c5c5c5;
        }

        body:not(.dark-mode) .filter-input,
        body:not(.dark-mode) .filter-select {
            background: #fff;
            border: 1.9px solid #d1d5db;
            color: #374151;
        }

        .filter-actions {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .btn-filter {
            background: #7c3aed;
            color: #fff;
            border: none;
            padding: 8px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            min-width: 90px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-filter:hover {
            background: #6d28d9;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-reset {
            background: #374151;
            color: #b5b5b5;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            min-width: 90px;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-left: 12px;
        }

        .btn-reset:hover {
            background: #4b5563;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Orders Header */
        .orders-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .orders-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--sales-text-primary);
            margin: 0;
        }

        body.dark-mode .orders-title,
        body.dark-mode-instant .orders-title {
            color: #ffffff;
        }

        body:not(.dark-mode) .orders-title {
            color: #374151;
        }

        .btn-create-order {
            background: var(--sales-success);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-create-order:hover {
            background: #218838;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px var(--sales-shadow);
        }

        .btn-create-order i {
            font-size: 16px;
        }

        /* Table */
        .sales-table-container {
            background: #132438;
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: none;
            padding: 20px;
        }

        body:not(.dark-mode) .sales-table-container {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
        }

        .sales-table {
            width: 100%;
            border-collapse: collapse;
        }

        .sales-table thead {
            background: transparent;
        }

        .sales-table th {
            padding: 12px 16px;
            text-align: left;
            font-weight: 600;
            color: var(--sales-text-primary);
            border-bottom: 2px solid var(--sales-border-color);
            white-space: nowrap;
            font-size: 18px;
        }

        body.dark-mode .sales-table th,
        body.dark-mode-instant .sales-table th {
            color: #ffffff;
        }

        body:not(.dark-mode) .sales-table th {
            color: #374151;
        }

        .sales-table th.sortable {
            cursor: pointer;
            user-select: none;
            transition: background 0.2s ease;
        }

        .sales-table th.sortable:hover {
            background: var(--sales-hover-bg);
        }

        .sales-table tbody tr {
            border-bottom: 1px solid var(--sales-border-color);
            transition: all 0.2s ease;
        }

        .sales-table tbody tr:hover {
            background: var(--sales-hover-bg);
        }

        body.dark-mode .sales-table tbody tr:hover,
        body.dark-mode-instant .sales-table tbody tr:hover {
            background: transparent;
        }

        body:not(.dark-mode) .sales-table tbody tr:hover {
            background: #f9fafb;
        }

        .sales-table td {
            padding: 12px 16px;
            color: var(--sales-text-primary);
            font-size: 19px;
        }

        body.dark-mode .sales-table td,
        body.dark-mode-instant .sales-table td {
            color: #ffffff;
        }

        body:not(.dark-mode) .sales-table td {
            color: #374151;
        }

        /* Order Number */
        .order-number {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .expand-icon {
            cursor: pointer;
            transition: transform 0.3s ease;
            color: var(--sales-text-secondary);
        }

        .expand-icon.expanded {
            transform: rotate(90deg);
        }

        .order-link {
            color: #60a5fa;
            text-decoration: none;
            font-weight: 600;
        }

        .order-link:hover {
            text-decoration: underline;
            color: #3b82f6;
        }

        body:not(.dark-mode) .order-link {
            color: #007bff;
        }

        body:not(.dark-mode) .order-link:hover {
            color: #0056b3;
        }

        /* Status Badges */
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-badge.expired {
            background-color: #f8f9fa;
            color: var(--sales-expired);
            border: 1px solid var(--sales-border-color);
        }

        body.dark-mode .status-badge.expired,
        body.dark-mode-instant .status-badge.expired {
            background-color: #2a2d35;
            border-color: #3e4042;
        }

        .status-badge.success,
        .status-badge.paid,
        .status-badge.completed {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        body.dark-mode .status-badge.success,
        body.dark-mode-instant .status-badge.success,
        body.dark-mode .status-badge.paid,
        body.dark-mode-instant .status-badge.paid,
        body.dark-mode .status-badge.completed,
        body.dark-mode-instant .status-badge.completed {
            background-color: rgba(66, 185, 131, 0.2);
            color: #42b983;
            border-color: rgba(66, 185, 131, 0.3);
        }

        .status-badge.cancelled {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        body.dark-mode .status-badge.cancelled,
        body.dark-mode-instant .status-badge.cancelled {
            background-color: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
            border-color: rgba(231, 76, 60, 0.3);
        }

        .status-badge.processing,
        .status-badge.confirmed {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        body.dark-mode .status-badge.processing,
        body.dark-mode-instant .status-badge.processing,
        body.dark-mode .status-badge.confirmed,
        body.dark-mode-instant .status-badge.confirmed {
            background-color: rgba(52, 152, 219, 0.2);
            color: #3498db;
            border-color: rgba(52, 152, 219, 0.3);
        }

        .status-badge.partial_payment,
        .status-badge.unpaid {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        body.dark-mode .status-badge.partial_payment,
        body.dark-mode-instant .status-badge.partial_payment,
        body.dark-mode .status-badge.unpaid,
        body.dark-mode-instant .status-badge.unpaid {
            background-color: rgba(243, 156, 18, 0.2);
            color: #f39c12;
            border-color: rgba(243, 156, 18, 0.3);
        }

        /* User Info */
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--sales-border-color);
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-id {
            color: var(--sales-text-secondary);
            font-size: 12px;
        }

        .user-phone {
            color: #60a5fa;
            text-decoration: none;
            font-size: 13px;
        }

        .user-phone:hover {
            text-decoration: underline;
            color: #3b82f6;
        }

        body:not(.dark-mode) .user-phone {
            color: #007bff;
        }

        body:not(.dark-mode) .user-phone:hover {
            color: #0056b3;
        }

        /* Note Icon */
        .note-icon-wrapper {
            position: relative;
            display: inline-block;
            transition: transform 0.2s ease;
        }

        .note-icon-wrapper:hover {
            transform: scale(1.1);
        }

        .note-icon {
            color: var(--sales-text-secondary);
            font-size: 18px;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .note-icon:hover {
            color: var(--sales-text-primary);
        }

        .note-counter {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--sales-danger);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 600;
        }

        /* Expandable Details */
        .detail-row {
            background: var(--sales-bg-primary);
            display: none;
        }

        .detail-row.show {
            display: table-row;
        }

        .detail-content {
            padding: 25px;
            border-top: 1px solid var(--sales-border-color);
        }

        body.dark-mode .detail-content,
        body.dark-mode-instant .detail-content {
            background: transparent;
            border-top: none;

        }

        body:not(.dark-mode) .detail-content {
            background: #ffffff;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .detail-section {
            background: var(--sales-bg-secondary);
            padding: 20px;
            border-radius: 8px;
            border: 1px solid var(--sales-border-color);
        }

        body.dark-mode .detail-section,
        body.dark-mode-instant .detail-section {
            background: transparent;
            border-color: transparent;
        }

        body:not(.dark-mode) .detail-section {
            background: #f8f9fa;
            border-color: #e2e8f0;
        }

        .detail-section h4 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--sales-text-primary);
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid var(--sales-border-color);
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-item label {
            color: var(--sales-text-secondary);
            font-size: 13px;
            font-weight: 500;
            min-width: 120px;
        }

        .detail-item>div {
            color: var(--sales-text-primary);
            font-size: 14px;
            text-align: right;
            flex: 1;
        }

        /* Notes Timeline */
        .notes-timeline {
            position: relative;
            padding-left: 50px;
        }

        .note-item {
            position: relative;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--sales-border-color);
        }

        .note-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .note-avatar {
            position: absolute;
            left: -50px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
        }

        .note-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .note-avatar-letter {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 600;
            font-size: 16px;
        }

        .note-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .note-author {
            font-weight: 600;
            font-size: 14px;
            color: var(--sales-text-primary);
        }

        .note-time {
            font-size: 13px;
            color: var(--sales-text-secondary);
        }

        .note-content {
            font-size: 14px;
            line-height: 1.6;
            color: var(--sales-text-primary);
            white-space: pre-wrap;
        }

        .add-note-btn {
            background: #6366f1;
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
        }

        .add-note-btn:hover {
            background: #4f46e5;
        }

        .empty-notes {
            text-align: center;
            padding: 40px 20px;
            color: var(--sales-text-secondary);
        }

        .empty-notes i {
            font-size: 48px;
            margin-bottom: 10px;
            opacity: 0.3;
        }

        .detail-label {
            color: var(--sales-text-secondary);
            font-size: 14px;
        }

        .detail-value {
            color: var(--sales-text-primary);
            font-weight: 500;
            font-size: 14px;
        }

        /* Pagination */
        .sales-pagination {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        /* Loading State */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loading-overlay.show {
            display: flex;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Info Badge */
        .info-badge {
            background: var(--sales-bg-tertiary);
            color: var(--sales-text-secondary);
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 13px;
            margin-bottom: 15px;
            display: inline-block;
            border: 1px solid var(--sales-border-color);
        }

        .info-badge i {
            margin-right: 5px;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .filter-row {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .sales-container {
                padding: 15px;
            }

            .filter-row {
                grid-template-columns: 1fr;
            }

            .sales-table-container {
                overflow-x: auto;
            }

            .sales-table {
                font-size: 12px;
                min-width: 1200px;
            }

            .sales-table th,
            .sales-table td {
                padding: 8px;
            }

            .orders-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .btn-create-order {
                width: 100%;
                justify-content: center;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }

            .filter-actions {
                flex-direction: column;
            }

            .btn-filter,
            .btn-reset {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .user-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }

            .sales-table {
                font-size: 11px;
            }
        }

        /* Modal Dark Mode Styles */
        body.dark-mode .modal-content,
        body.dark-mode-instant .modal-content {
            background: #132438;
            border: 1px solid #1b3d63;
            color: #ffffff;
        }

        body.dark-mode .modal-header,
        body.dark-mode-instant .modal-header {
            background: #122438;
            border-bottom: 1px solid #1b3d63;
            color: #ffffff;
        }

        body.dark-mode .modal-header .close,
        body.dark-mode-instant .modal-header .close {
            color: #ffffff;
            opacity: 0.8;
            text-shadow: none;
        }

        body.dark-mode .modal-header .close:hover,
        body.dark-mode-instant .modal-header .close:hover {
            opacity: 1;
        }

        body.dark-mode .modal-body,
        body.dark-mode-instant .modal-body {
            background: #132438;
            color: #ffffff;
        }

        body.dark-mode .modal-footer,
        body.dark-mode-instant .modal-footer {
            background: #122438;
            border-top: 1px solid #1b3d63;
        }

        body.dark-mode .modal-title,
        body.dark-mode-instant .modal-title {
            color: #ffffff;
        }

        /* Action Buttons Dark Mode */
        .btn-action {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-action.btn-primary {
            background: #7c3aed;
            color: #ffffff;
        }

        .btn-action.btn-primary:hover {
            background: #6d28d9;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(124, 58, 237, 0.3);
        }

        .btn-action.btn-info {
            background: #3498db;
            color: #ffffff;
        }

        .btn-action.btn-info:hover {
            background: #2980b9;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
        }

        body.dark-mode .btn-action.btn-primary,
        body.dark-mode-instant .btn-action.btn-primary {
            background: #7c3aed;
            color: #ffffff;
        }

        body.dark-mode .btn-action.btn-info,
        body.dark-mode-instant .btn-action.btn-info {
            background: #3498db;
            color: #ffffff;
        }

        body:not(.dark-mode) .btn-action.btn-primary {
            background: #007bff;
            color: #ffffff;
        }

        body:not(.dark-mode) .btn-action.btn-info {
            background: #17a2b8;
            color: #ffffff;
        }

        /* Notes Sidebar Styles */
        .notes-sidebar {
            position: fixed;
            top: 0;
            right: -100%;
            width: 100%;
            height: 100%;
            z-index: 9999;
            transition: right 0.3s ease;
        }

        .notes-sidebar.open {
            right: 0;
        }

        .notes-sidebar-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            cursor: pointer;
        }

        .notes-sidebar-content {
            position: absolute;
            top: 0;
            right: 0;
            width: 500px;
            max-width: 90%;
            height: 100%;
            background: var(--sales-bg-primary);
            box-shadow: -4px 0 20px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }

        .notes-sidebar.open .notes-sidebar-content {
            transform: translateX(0);
        }

        body.dark-mode .notes-sidebar-content,
        body.dark-mode-instant .notes-sidebar-content {
            background: #132438;
            border-left: 1px solid #1b3d63;
        }

        body:not(.dark-mode) .notes-sidebar-content {
            background: #ffffff;
            border-left: 1px solid #e2e8f0;
        }

        .notes-sidebar-header {
            padding: 24px;
            border-bottom: 1px solid var(--sales-border-color);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            background: var(--sales-bg-secondary);
        }

        body.dark-mode .notes-sidebar-header,
        body.dark-mode-instant .notes-sidebar-header {
            background: #122438;
            border-bottom: 1px solid #1b3d63;
        }

        body:not(.dark-mode) .notes-sidebar-header {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .notes-sidebar-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--sales-text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .notes-sidebar-title i {
            color: #6366f1;
        }

        #orderNoteNumber {
            color: #6366f1;
            font-weight: 700;
        }

        .notes-close-btn {
            background: transparent;
            border: none;
            color: var(--sales-text-secondary);
            font-size: 24px;
            cursor: pointer;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .notes-close-btn:hover {
            background: var(--sales-hover-bg);
            color: var(--sales-text-primary);
        }

        .notes-sidebar-body {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
        }

        .notes-sidebar-body::-webkit-scrollbar {
            width: 8px;
        }

        .notes-sidebar-body::-webkit-scrollbar-track {
            background: var(--sales-bg-secondary);
        }

        .notes-sidebar-body::-webkit-scrollbar-thumb {
            background: var(--sales-border-color);
            border-radius: 4px;
        }

        .notes-sidebar-body::-webkit-scrollbar-thumb:hover {
            background: var(--sales-text-secondary);
        }

        .notes-sidebar-footer {
            padding: 20px 24px;
            border-top: 1px solid var(--sales-border-color);
            background: var(--sales-bg-secondary);
        }

        body.dark-mode .notes-sidebar-footer,
        body.dark-mode-instant .notes-sidebar-footer {
            background: #122438;
            border-top: 1px solid #1b3d63;
        }

        body:not(.dark-mode) .notes-sidebar-footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }

        .btn-add-note {
            width: 100%;
            background: #6366f1;
            color: #ffffff;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .btn-add-note:hover {
            background: #4f46e5;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        /* Add Note Section in Sidebar */
        .notes-add-section {
            padding: 20px 24px;
            border-bottom: 2px solid var(--sales-border-color);
            background: var(--sales-bg-secondary);
        }

        body.dark-mode .notes-add-section,
        body.dark-mode-instant .notes-add-section {
            background: #0f1f31;
            border-bottom-color: #1b3d63;
        }

        body:not(.dark-mode) .notes-add-section {
            background: #f8fafc;
            border-bottom-color: #e2e8f0;
        }

        .sidebar-note-input {
            background: var(--sales-bg-primary) !important;
            border: 1.5px solid var(--sales-border-color) !important;
            border-radius: 8px;
            padding: 12px;
            color: var(--sales-text-primary) !important;
            font-size: 14px;
            resize: none;
            width: 100%;
            transition: all 0.2s ease;
        }

        .sidebar-note-input:focus {
            outline: none;
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .sidebar-note-input::placeholder {
            color: var(--sales-text-secondary);
        }

        body.dark-mode .sidebar-note-input,
        body.dark-mode-instant .sidebar-note-input {
            background: #1a2d42 !important;
            border-color: #1b3d63 !important;
            color: #ffffff !important;
        }

        body:not(.dark-mode) .sidebar-note-input {
            background: #ffffff !important;
            border-color: #d1d5db !important;
            color: #374151 !important;
        }

        .btn-mention {
            background: #6366f1;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-mention:hover {
            background: #4f46e5;
        }

        .btn-attach {
            background: transparent;
            border: 1.5px solid var(--sales-border-color);
            color: var(--sales-text-secondary);
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-attach:hover {
            background: var(--sales-hover-bg);
            color: var(--sales-text-primary);
        }

        .btn-submit-note {
            background: #3b82f6;
            color: #ffffff;
            border: none;
            padding: 8px 24px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-left: auto;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-submit-note:hover {
            background: #2563eb;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }

        /* Mention Dropdown Styles */
        .mention-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            width: 380px;
            max-height: 450px;
            background: var(--sales-bg-primary);
            border: 1px solid var(--sales-border-color);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            overflow: hidden;
        }

        body.dark-mode .mention-dropdown,
        body.dark-mode-instant .mention-dropdown {
            background: #132438;
            border-color: #1b3d63;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }

        body:not(.dark-mode) .mention-dropdown {
            background: #ffffff;
            border-color: #e2e8f0;
        }

        .mention-dropdown-header {
            padding: 14px;
            border-bottom: 1px solid var(--sales-border-color);
            background: var(--sales-bg-secondary);
        }

        body.dark-mode .mention-dropdown-header,
        body.dark-mode-instant .mention-dropdown-header {
            background: #0f1f31;
            border-bottom-color: #1b3d63;
        }

        body:not(.dark-mode) .mention-dropdown-header {
            background: #f8fafc;
            border-bottom-color: #e2e8f0;
        }

        .mention-search {
            width: 100%;
            background: var(--sales-bg-primary);
            border: 1px solid var(--sales-border-color);
            border-radius: 8px;
            padding: 10px 14px;
            color: var(--sales-text-primary);
            font-size: 14px;
            font-size: 13px;
        }

        .mention-search:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1);
        }

        .mention-search::placeholder {
            color: var(--sales-text-secondary);
        }

        body.dark-mode .mention-search,
        body.dark-mode-instant .mention-search {
            background: #1a2d42;
            border-color: #1b3d63;
        }

        body:not(.dark-mode) .mention-search {
            background: #ffffff;
            border-color: #d1d5db;
        }

        .mention-dropdown-body {
            max-height: 300px;
            overflow-y: auto;
        }

        .mention-dropdown-body::-webkit-scrollbar {
            width: 6px;
        }

        .mention-dropdown-body::-webkit-scrollbar-track {
            background: var(--sales-bg-secondary);
        }

        .mention-dropdown-body::-webkit-scrollbar-thumb {
            background: var(--sales-border-color);
            border-radius: 3px;
        }

        .mention-user-item {
            padding: 12px 14px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid var(--sales-border-color);
        }

        .mention-user-item:last-child {
            border-bottom: none;
        }

        .mention-user-item:hover {
            background: var(--sales-hover-bg);
            transform: translateX(2px);
        }

        body.dark-mode .mention-user-item:hover,
        body.dark-mode-instant .mention-user-item:hover {
            background: #1a2d42;
        }

        body:not(.dark-mode) .mention-user-item:hover {
            background: #f1f5f9;
        }

        .mention-user-item:active {
            transform: scale(0.98);
        }

        .mention-user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
            border: 2px solid var(--sales-border-color);
        }

        .mention-user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .mention-user-avatar-letter {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 600;
            font-size: 14px;
        }

        .mention-user-info {
            flex: 1;
            min-width: 0;
        }

        .mention-user-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--sales-text-primary);
            margin-bottom: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .mention-user-role {
            font-size: 11px;
            color: var(--sales-text-secondary);
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 2px;
        }

        .mention-user-role i {
            font-size: 10px;
        }

        .mention-user-phone {
            font-size: 12px;
            color: #60a5fa;
            margin-top: 3px;
            display: flex;
            align-items: center;
            gap: 5px;
            font-weight: 500;
        }

        .mention-user-phone i {
            font-size: 11px;
        }

        body:not(.dark-mode) .mention-user-phone {
            color: #3b82f6;
        }

        .mention-empty {
            padding: 30px 20px;
            text-align: center;
            color: var(--sales-text-secondary);
            font-size: 14px;
        }

        .mention-empty i {
            font-size: 32px;
            margin-bottom: 10px;
            display: block;
            opacity: 0.5;
        }

        /* Notes Timeline Styles */
        .notes-timeline {
            padding: 0;
            position: relative;
        }

        .notes-timeline::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--sales-border-color);
        }

        body.dark-mode .notes-timeline::before,
        body.dark-mode-instant .notes-timeline::before {
            background: #1b3d63;
        }

        body:not(.dark-mode) .notes-timeline::before {
            background: #e2e8f0;
        }

        .note-item {
            position: relative;
            padding-left: 60px;
            margin-bottom: 24px;
            padding-bottom: 0;
        }

        .note-item:last-child {
            margin-bottom: 0;
        }

        .note-avatar {
            position: absolute;
            left: 0;
            top: 0;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid var(--sales-bg-primary);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 1;
        }

        body.dark-mode .note-avatar,
        body.dark-mode-instant .note-avatar {
            border-color: #132438;
        }

        body:not(.dark-mode) .note-avatar {
            border-color: #ffffff;
        }

        .note-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .note-avatar-letter {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 700;
            font-size: 16px;
        }

        .note-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .note-author {
            font-weight: 600;
            font-size: 14px;
            color: var(--sales-text-primary);
        }

        .note-time {
            font-size: 12px;
            color: var(--sales-text-secondary);
        }

        .note-content {
            font-size: 14px;
            line-height: 1.6;
            color: var(--sales-text-primary);
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .empty-notes {
            text-align: center;
            padding: 80px 20px;
            color: var(--sales-text-secondary);
        }

        .empty-notes i {
            font-size: 64px;
            margin-bottom: 16px;
            opacity: 0.3;
        }

        .empty-notes p {
            font-size: 16px;
            margin: 0;
        }

        @media (max-width: 768px) {
            .notes-sidebar-content {
                width: 100%;
                max-width: 100%;
            }
        }

        /* File Attachment Styles */
        .attachment-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .attachment-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            background: var(--sales-hover-bg);
            border: 1px solid var(--sales-border-color);
            border-radius: 10px;
            font-size: 13px;
            color: var(--sales-text-primary);
            position: relative;
            max-width: 380px;
        }

        .attachment-item .attachment-thumbnail {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
            border: 2px solid var(--sales-border-color);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .attachment-item .attachment-icon-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 28px;
        }

        .attachment-item .attachment-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
            min-width: 0;
            flex: 1;
        }

        .attachment-item .attachment-name {
            font-weight: 600;
            font-size: 13px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: var(--sales-text-primary);
        }

        .attachment-item .attachment-size {
            font-size: 12px;
            color: var(--sales-text-secondary);
            font-weight: 500;
        }

        .attachment-item-remove {
            margin-left: auto;
            cursor: pointer;
            color: #ef4444;
            font-size: 16px;
            opacity: 0.7;
            transition: opacity 0.2s;
            flex-shrink: 0;
        }

        .attachment-item-remove:hover {
            opacity: 1;
        }

        .note-attachments {
            margin-top: 12px;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .note-attachment {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            background: var(--sales-hover-bg);
            border: 1px solid var(--sales-border-color);
            border-radius: 10px;
            font-size: 13px;
            color: var(--sales-text-primary);
            text-decoration: none;
            transition: all 0.2s;
            max-width: 350px;
        }

        .note-attachment:hover {
            background: var(--sales-bg-primary);
            border-color: #3b82f6;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
        }

        .attachment-thumbnail {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
            border: 2px solid var(--sales-border-color);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .attachment-icon-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 28px;
        }

        .attachment-icon-pdf-wrapper {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .attachment-icon-doc-wrapper {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .attachment-icon-text-wrapper {
            background: rgba(168, 85, 247, 0.1);
            color: #a855f7;
        }

        body.dark-mode .attachment-icon-pdf-wrapper,
        body.dark-mode-instant .attachment-icon-pdf-wrapper {
            background: rgba(239, 68, 68, 0.2);
        }

        body.dark-mode .attachment-icon-doc-wrapper,
        body.dark-mode-instant .attachment-icon-doc-wrapper {
            background: rgba(59, 130, 246, 0.2);
        }

        body.dark-mode .attachment-icon-text-wrapper,
        body.dark-mode-instant .attachment-icon-text-wrapper {
            background: rgba(168, 85, 247, 0.2);
        }

        .attachment-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
            min-width: 0;
            flex: 1;
        }

        .attachment-name {
            font-weight: 600;
            font-size: 13px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: var(--sales-text-primary);
        }

        .attachment-size {
            font-size: 12px;
            color: var(--sales-text-secondary);
            font-weight: 500;
        }

        .attachment-icon-image {
            color: #10b981;
        }

        .attachment-icon-pdf {
            color: #ef4444;
        }

        .attachment-icon-doc {
            color: #3b82f6;
        }

        body.dark-mode .attachment-item,
        body.dark-mode-instant .attachment-item,
        body.dark-mode .note-attachment,
        body.dark-mode-instant .note-attachment {
            background: #1a2d42;
            border-color: #1b3d63;
        }

        body:not(.dark-mode) .attachment-item,
        body:not(.dark-mode) .note-attachment {
            background: #f3f4f6;
            border-color: #e5e7eb;
        }
    </style>

    <div class="sales-container">
        <!-- Page Title -->
        <div class="orders-header">
            <h1 class="orders-title">{{ __('Sales') }}</h1>
            @if(!empty($booking_update))
                <button class="btn-create-order" onclick="alert('Create Order functionality coming soon!')">
                    <i class="fa fa-plus"></i>
                    {{ __('CREATE ORDER') }}
                </button>
            @endif
        </div>

        @include('admin.message')

        <!-- Advanced Filters -->
        <form method="get" action="{{ route('report.admin.booking') }}" id="sales-filter-form">
            <div class="sales-filters">
                <!-- Row 1 -->
                <div class="filter-row">
                    <input type="text" name="order_number" class="filter-input" placeholder="{{ __('Order Number') }}"
                        value="{{ $filters['order_number'] ?? '' }}">

                    <input type="date" name="from_date" class="filter-input" placeholder="{{ __('From') }}"
                        value="{{ $filters['from_date'] ?? '' }}">

                    <input type="date" name="to_date" class="filter-input" placeholder="{{ __('To') }}"
                        value="{{ $filters['to_date'] ?? '' }}">

                    <select name="order_status" class="filter-select">
                        <option value="">{{ __('Order Status') }}</option>
                        @if(!empty($statues))
                            @foreach($statues as $status)
                                <option value="{{ $status }}" {{ ($filters['order_status'] ?? '') == $status ? 'selected' : '' }}>
                                    {{ booking_status_to_text($status) }}
                                </option>
                            @endforeach
                        @endif
                    </select>

                    <input type="date" name="schedule_from" class="filter-input" placeholder="{{ __('Schedule From') }}"
                        value="{{ $filters['schedule_from'] ?? '' }}">

                    <input type="date" name="schedule_to" class="filter-input" placeholder="{{ __('Schedule To') }}"
                        value="{{ $filters['schedule_to'] ?? '' }}">
                </div>

                <!-- Row 2 -->
                <div class="filter-row">
                    <select name="gateway" class="filter-select">
                        <option value="">{{ __('Gateway') }}</option>
                        @foreach($gateways as $key => $name)
                            <option value="{{ $key }}" {{ ($filters['gateway'] ?? '') == $key ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>

                    <input type="text" name="phone_number" class="filter-input" placeholder="{{ __('Phone Number') }}"
                        value="{{ $filters['phone_number'] ?? '' }}">

                    <input type="text" name="reservation" class="filter-input" placeholder="{{ __('Reservation') }}"
                        value="{{ $filters['reservation'] ?? '' }}">

                    <input type="text" name="activity" class="filter-input" placeholder="{{ __('Activity') }}"
                        value="{{ $filters['activity'] ?? '' }}">

                    <select name="confirm_type" class="filter-select">
                        <option value="">{{ __('Confirm Status') }}</option>
                        @foreach($confirmTypes as $key => $name)
                            <option value="{{ $key }}" {{ ($filters['confirm_type'] ?? '') == $key ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="salesman_id" class="filter-select">
                        <option value="">{{ __('Salesman') }}</option>
                        <option value="0" {{ ($filters['salesman_id'] ?? '') === '0' ? 'selected' : '' }}>
                            {{ __('Not Assigned') }}
                        </option>
                        @foreach($salesmen as $id => $name)
                            <option value="{{ $id }}" {{ ($filters['salesman_id'] ?? '') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Actions -->
                <div class="filter-actions">
                    <button type="submit" class="btn-filter">{{ __('FILTER') }}</button>
                    <button type="button" class="btn-reset"
                        onclick="window.location.href='{{ route('report.admin.booking') }}'">
                        {{ __('RESET') }}
                    </button>
                </div>
            </div>
        </form>


        <!-- Orders Table -->
        <div class="sales-table-container">
            <table class="sales-table">
                <thead>
                    <tr>
                        <th style="width: 50px;"></th>
                        <th class="sortable" data-sort="code">{{ __('Order') }}</th>
                        <th>{{ __('Activity') }}</th>
                        <th class="sortable" data-sort="total">{{ __('Amount') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th class="sortable" data-sort="created_at">
                            {{ __('Created On') }}
                            <i class="fa fa-sort-down"></i>
                        </th>
                        <th>{{ __('Confirm Status') }}</th>
                        <th>{{ __('Salesman') }}</th>
                        <th>{{ __('User') }}</th>
                        <th style="width: 80px; text-align: center;">{{ __('Notes') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $booking)
                        <tr class="booking-row" data-booking-id="{{ $booking->id }}">
                            <td>
                                <i class="fa fa-angle-right expand-icon" onclick="toggleDetails({{ $booking->id }})"></i>
                            </td>
                            <td>
                                <a href="#" class="order-link" onclick="toggleDetails({{ $booking->id }}); return false;">
                                    {{ $booking->code ?? $booking->id }}
                                </a>
                            </td>
                            <td>
                                @if($service = $booking->service)
                                    {{ Str::limit($service->title, 30) }}
                                @else
                                    <span style="color: var(--sales-text-secondary);">{{ __('[Deleted]') }}</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    // Calculate cart total (all bookings with same payment_id)
                                    $cartTotal = $booking->total;
                                    if ($booking->payment_id) {
                                        $cartTotal = \Modules\Booking\Models\Booking::where('payment_id', $booking->payment_id)->sum('total');
                                    }
                                @endphp
                                <div style="display: flex; align-items: center;">
                                    <strong style="font-size: 20px;">{{ $cartTotal }}</strong>
                                    <span style="opacity: 0.8; font-size: 16px;">{!! get_current_currency_svg() !!}</span>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge {{ $booking->status }}">
                                    {{ $booking->statusName }}
                                </span>
                            </td>
                            <td>{{ display_datetime($booking->created_at) }}</td>
                            <td>
                                @php
                                    $confirmType = $booking->confirm_type ?: 'pending';
                                    $confirmTypeLabels = [
                                        'confirmed' => __('Confirmed'),
                                        'not_confirmed' => __('Not Confirmed'),
                                        'pending' => __('Pending')
                                    ];
                                    $confirmIcon = [
                                        'confirmed' => 'fa-check-circle',
                                        'not_confirmed' => 'fa-times-circle',
                                        'pending' => 'fa-clock-o'
                                    ];
                                    $confirmColor = [
                                        'confirmed' => '#10b981',
                                        'not_confirmed' => '#ef4444',
                                        'pending' => '#f59e0b'
                                    ];
                                    $confirmBg = [
                                        'confirmed' => 'rgba(16, 185, 129, 0.1)',
                                        'not_confirmed' => 'rgba(239, 68, 68, 0.1)',
                                        'pending' => 'rgba(245, 158, 11, 0.1)'
                                    ];
                                @endphp
                                <span
                                    style="display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 500; background: {{ $confirmBg[$confirmType] ?? $confirmBg['pending'] }}; color: {{ $confirmColor[$confirmType] ?? $confirmColor['pending'] }};"
                                    title="{{ $confirmTypeLabels[$confirmType] ?? __('Pending') }}">
                                    <i class="fa {{ $confirmIcon[$confirmType] ?? 'fa-clock-o' }}"></i>
                                    {{ $confirmTypeLabels[$confirmType] ?? __('Pending') }}
                                </span>
                            </td>
                            <td>
                                @if($booking->salesman_id && $booking->salesman_id > 0)
                                    @php
                                        $salesman = \App\User::find($booking->salesman_id);
                                    @endphp
                                    @if($salesman)
                                        <span style="display: inline-flex; align-items: center; gap: 5px; font-size: 13px;"
                                            title="{{ __('Salesman') }}: {{ $salesman->first_name }} {{ $salesman->last_name }}">
                                            <i class="fa fa-user-tie" style="opacity: 0.6;"></i>
                                            {{ $salesman->first_name }} {{ $salesman->last_name }}
                                        </span>
                                    @else
                                        <span style="color: var(--sales-text-secondary); font-size: 12px; font-style: italic;">
                                            {{ __('Not Assigned') }}
                                        </span>
                                    @endif
                                @else
                                    <span style="color: var(--sales-text-secondary); font-size: 12px; font-style: italic;">
                                        {{ __('Not Assigned') }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="user-info">
                                    @if($booking->customer)
                                        <a href="{{ route('user.admin.detail', ['id' => $booking->customer_id]) }}"
                                            style="text-decoration: none;">
                                            @if($booking->customer->getAvatarUrl())
                                                <img src="{{ $booking->customer->getAvatarUrl() }}" class="user-avatar" alt="User">
                                            @else
                                                @php
                                                    $userName = $booking->first_name ?? $booking->email ?? 'U';
                                                    $firstLetter = strtoupper(mb_substr($userName, 0, 1));
                                                    // Generate color based on first letter
                                                    $colors = ['#6366f1', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#3b82f6', '#14b8a6', '#f97316'];
                                                    $colorIndex = ord($firstLetter) % count($colors);
                                                    $bgColor = $colors[$colorIndex];
                                                @endphp
                                                <div class="user-avatar"
                                                    style="background: {{ $bgColor }}; display: flex; align-items: center; justify-content: center; color: #ffffff; font-weight: 600; font-size: 16px;">
                                                    {{ $firstLetter }}
                                                </div>
                                            @endif
                                        </a>
                                    @else
                                        @php
                                            $userName = $booking->first_name ?? $booking->email ?? 'U';
                                            $firstLetter = strtoupper(mb_substr($userName, 0, 1));
                                            $colors = ['#6366f1', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#3b82f6', '#14b8a6', '#f97316'];
                                            $colorIndex = ord($firstLetter) % count($colors);
                                            $bgColor = $colors[$colorIndex];
                                        @endphp
                                        <div class="user-avatar"
                                            style="background: {{ $bgColor }}; display: flex; align-items: center; justify-content: center; color: #ffffff; font-weight: 600; font-size: 16px;">
                                            {{ $firstLetter }}
                                        </div>
                                    @endif
                                    <div class="user-details">
                                        @if($booking->customer)
                                            <a href="{{ route('user.admin.detail', ['id' => $booking->customer_id]) }}"
                                                style="color: var(--sales-text-primary); text-decoration: none; font-weight: 500; font-size: 14px;">
                                                {{ $booking->first_name }} {{ $booking->last_name }}
                                            </a>
                                        @else
                                            <span style="font-weight: 500; font-size: 14px;">
                                                {{ $booking->first_name }} {{ $booking->last_name }}
                                            </span>
                                        @endif
                                        @if($booking->phone)
                                            <a href="{{ route('user.admin.detail', ['id' => $booking->customer_id]) }}"
                                                class="user-phone">
                                                {{ $booking->phone }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <div class="note-icon-wrapper" onclick="openNotesModal({{ $booking->id }})"
                                    style="cursor: pointer;">
                                    <i class="fa fa-sticky-note note-icon"></i>
                                    @php
                                        $notesCount = $booking->notes()->count();
                                    @endphp
                                    @if($notesCount > 0)
                                        <span class="note-counter">{{ $notesCount }}</span>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Expandable Details Row -->
                        <tr class="detail-row" id="detail-{{ $booking->id }}">
                            <td colspan="10">
                                <div class="detail-content">
                                    @php
                                        // Get all bookings with same payment_id (cart items)
                                        $relatedBookings = \Modules\Booking\Models\Booking::where('payment_id', $booking->payment_id)
                                            ->where('payment_id', '!=', null)
                                            ->orderBy('id')
                                            ->get();
                                    @endphp

                                    @if($relatedBookings->count() > 1)
                                        <!-- Cart Items Section -->
                                        <div class="detail-section" style="margin-bottom: 25px;">
                                            <h4>{{ __('Order Items') }} ({{ $relatedBookings->count() }} {{ __('Bookings') }})</h4>
                                            <div style="overflow-x: auto;">
                                                <table style="width: 100%; border-collapse: collapse;">
                                                    <thead>
                                                        <tr style="border-bottom: 2px solid var(--sales-border-color);">
                                                            <th style="padding: 10px; text-align: left; font-size: 13px;">
                                                                {{ __('Activity') }}
                                                            </th>
                                                            <th style="padding: 10px; text-align: left; font-size: 13px;">
                                                                {{ __('Guests') }}
                                                            </th>
                                                            <th style="padding: 10px; text-align: left; font-size: 13px;">
                                                                {{ __('Amount') }}
                                                            </th>
                                                            <th style="padding: 10px; text-align: center; font-size: 13px;">
                                                                {{ __('Status') }}
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($relatedBookings as $relBooking)
                                                            <tr style="border-bottom: 1px solid var(--sales-border-color);">
                                                                <td style="padding: 12px;">
                                                                    @if($relService = $relBooking->service)
                                                                        <strong style="font-size: 14px;">{{ $relService->title }}</strong>
                                                                        @if($relBooking->start_date)
                                                                            <div
                                                                                style="font-size: 12px; color: var(--sales-text-secondary); margin-top: 4px;">
                                                                                <i class="fa fa-calendar"></i>
                                                                                {{ display_date($relBooking->start_date) }}
                                                                            </div>
                                                                        @endif
                                                                    @else
                                                                        <span
                                                                            style="color: var(--sales-text-secondary);">{{ __('[Deleted Service]') }}</span>
                                                                    @endif
                                                                </td>
                                                                <td style="padding: 12px;">
                                                                    @php
                                                                        $personTypes = $relBooking->getMeta('person_types');
                                                                        if ($personTypes) {
                                                                            $personTypes = json_decode($personTypes, true);
                                                                        }
                                                                    @endphp
                                                                    @if(!empty($personTypes) && is_array($personTypes))
                                                                        @foreach($personTypes as $type)
                                                                            <div style="font-size: 13px; margin-bottom: 3px;">
                                                                                <i class="fa fa-user"></i> {{ $type['number'] ?? 0 }} ×
                                                                                {{ $type['name'] ?? 'Guest' }}
                                                                            </div>
                                                                        @endforeach
                                                                    @else
                                                                        <div style="font-size: 13px;">
                                                                            <i class="fa fa-users"></i> {{ $relBooking->total_guests ?? 1 }}
                                                                            {{ __('Guests') }}
                                                                        </div>
                                                                    @endif
                                                                </td>
                                                                <td style="padding: 12px;">
                                                                    <strong style="font-size: 15px;">{{ $relBooking->total }}</strong>
                                                                    <span
                                                                        style="opacity: 0.8; font-size: 13px;">{!! get_current_currency_svg() !!}</span>
                                                                </td>
                                                                <td style="padding: 12px; text-align: center;">
                                                                    <span class="status-badge {{ $relBooking->status }}"
                                                                        style="font-size: 11px;">
                                                                        {{ $relBooking->statusName }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr
                                                            style="border-top: 2px solid var(--sales-border-color); font-weight: bold;">
                                                            <td colspan="2" style="padding: 12px; text-align: right;">
                                                                {{ __('Total') }}:
                                                            </td>
                                                            <td style="padding: 12px;">
                                                                <strong
                                                                    style="font-size: 16px;">{{ $relatedBookings->sum('total') }}</strong>
                                                                <span
                                                                    style="opacity: 0.8; font-size: 14px;">{!! get_current_currency_svg() !!}</span>
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    @endif



                                    <!-- Actions -->
                                    @if($booking_update)
                                        <div style="margin-top: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
                                            <button class="btn-action btn-info" onclick="setPaidModal({{ $booking->id }})">
                                                <i class="fa fa-money"></i> {{ __('Set Paid') }}
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" style="text-align: center; padding: 40px; color: var(--sales-text-secondary);">
                                <i class="fa fa-inbox" style="font-size: 48px; margin-bottom: 15px;"></i>
                                <p>{{ __('No bookings found') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="sales-pagination">
            @if(!empty($rows) && is_object($rows) && method_exists($rows, 'links'))
                {{ $rows->appends(request()->query())->links() }}
            @endif
        </div>
    </div>

    <!-- Set Paid Modals -->
    @if(!empty($rows) && method_exists($rows, 'items'))
        @foreach($rows->items() as $booking)
            @if($service = $booking->service)
                @if(!empty($service->set_paid_modal_file) && view()->exists($service->set_paid_modal_file))
                    @include($service->set_paid_modal_file)
                @endif
            @endif
        @endforeach
    @endif

    <!-- Bulk Edit Form (Hidden) -->
    @if(!empty($booking_update))
        <form method="post" action="{{route('report.admin.booking.bulkEdit')}}" id="bulk-edit-form" style="display: none;">
            @csrf
            <input type="hidden" name="action" id="bulk-action">
            <input type="hidden" name="ids[]" id="bulk-ids">
        </form>
    @endif

    <script>
        // Global Variables
        let currentBookingId = null;
        let mentionUsers = [];

        // Toggle expandable details
        function toggleDetails(bookingId) {
            const detailRow = document.getElementById('detail-' + bookingId);
            const icon = document.querySelector(`[data-booking-id="${bookingId}"] .expand-icon`);

            if (detailRow.classList.contains('show')) {
                detailRow.classList.remove('show');
                icon.classList.remove('expanded');
            } else {
                // Close all other details
                document.querySelectorAll('.detail-row.show').forEach(row => {
                    row.classList.remove('show');
                });
                document.querySelectorAll('.expand-icon.expanded').forEach(i => {
                    i.classList.remove('expanded');
                });

                // Open this detail
                detailRow.classList.add('show');
                icon.classList.add('expanded');
            }
        }

        // Modal functions
        function setPaidModal(bookingId) {
            $('#modal-paid-' + bookingId).modal('show');
        }

        // Set Paid functionality
        $(document).on('click', '#set_paid_btn', function (e) {
            var id = $(this).data('id');
            $.ajax({
                url: '{{ url('/') }}/booking/setPaidAmount',
                data: {
                    id: id,
                    remain: $('#modal-paid-' + id + ' #set_paid_input').val(),
                },
                dataType: 'json',
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (res) {
                    alert(res.message);
                    window.location.reload();
                },
                error: function (xhr) {
                    alert('Error: ' + (xhr.responseJSON?.message || 'Something went wrong'));
                }
            });
        });

        // Show/Hide Loading
        function showLoading() {
            document.getElementById('loading-overlay').classList.add('show');
        }

        function hideLoading() {
            document.getElementById('loading-overlay').classList.remove('show');
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function () {
            // Show loading on form submit
            document.getElementById('sales-filter-form').addEventListener('submit', function () {
                showLoading();
            });

            // Auto-hide loading after page load
            setTimeout(hideLoading, 500);
        });

        // Table sorting
        document.querySelectorAll('.sortable').forEach(th => {
            th.addEventListener('click', function () {
                const sortBy = this.dataset.sort;
                const currentUrl = new URL(window.location.href);
                const currentSort = currentUrl.searchParams.get('sort_by');
                const currentOrder = currentUrl.searchParams.get('sort_order') || 'desc';

                // Toggle order if same column
                const newOrder = (currentSort === sortBy && currentOrder === 'desc') ? 'asc' : 'desc';

                currentUrl.searchParams.set('sort_by', sortBy);
                currentUrl.searchParams.set('sort_order', newOrder);

                window.location.href = currentUrl.toString();
            });
        });

        // Notes Sidebar Functions
        function openNotesModal(bookingId) {
            currentBookingId = bookingId;

            // Get booking order number for display
            const orderNumber = $('[data-booking-id="' + bookingId + '"]').closest('tr').find('.order-link').text().trim();
            $('#orderNoteNumber').text(orderNumber);

            // Clear textarea
            $('#sidebarNoteTextarea').val('');

            // Open sidebar
            $('#notesSidebar').addClass('open');
            $('body').css('overflow', 'hidden');

            loadNotes(bookingId);

            // Focus on textarea
            setTimeout(() => {
                $('#sidebarNoteTextarea').focus();
            }, 400);
        }

        function closeNotesSidebar() {
            $('#notesSidebar').removeClass('open');
            $('body').css('overflow', '');
        }

        // Keyboard shortcut: Ctrl+Enter to submit note
        $(document).on('keydown', '#sidebarNoteTextarea', function (e) {
            if ((e.ctrlKey || e.metaKey) && e.keyCode === 13) {
                e.preventDefault();
                saveNoteSidebar();
            }
        });

        // Mention Functions
        function toggleMentionDropdown() {
            const dropdown = document.getElementById('mentionDropdown');
            if (dropdown.style.display === 'none' || dropdown.style.display === '') {
                // Load all users when opening dropdown
                loadMentionUsers('');
                dropdown.style.display = 'block';
                setTimeout(() => {
                    document.getElementById('mentionSearch').focus();
                }, 100);
            } else {
                dropdown.style.display = 'none';
                // Clear search input when closing
                document.getElementById('mentionSearch').value = '';
            }
        }

        function loadMentionUsers(searchTerm = '') {
            showLoading();

            $.ajax({
                url: '{{ route("user.admin.getForSelect2") }}',
                method: 'GET',
                data: {
                    exclude_roles: ['customer', 'vendor'], // Exclude customer and vendor roles
                    q: searchTerm // Search by name or phone
                },
                success: function (response) {
                    hideLoading();
                    if (response.results) {
                        mentionUsers = response.results;
                        displayMentionUsers(mentionUsers);
                    }
                },
                error: function () {
                    hideLoading();
                    $('#mentionUsersList').html('<div class="mention-empty"><i class="fa fa-exclamation-triangle"></i>{{ __("Failed to load users") }}</div>');
                }
            });
        }

        function displayMentionUsers(users) {
            if (!users || users.length === 0) {
                $('#mentionUsersList').html('<div class="mention-empty"><i class="fa fa-users"></i>{{ __("No users found") }}</div>');
                $('#mentionUsersCount').text('0 {{ __("users") }}');
                return;
            }

            // Update counter
            $('#mentionUsersCount').text(users.length + ' {{ __("users") }}');

            let html = '';
            users.forEach(user => {
                const userName = user.text || user.first_name || 'Unknown';
                const userRole = user.role_name || 'User';
                const userId = user.id;
                const userAvatar = user.avatar_url || null;
                const userPhone = user.phone || '';
                const userEmail = user.email || '';
                const firstLetter = userName.charAt(0).toUpperCase();
                const bgColor = getRandomColor();

                // Escape single quotes in username for onclick
                const safeUserName = userName.replace(/'/g, "\\'");

                const avatarHtml = userAvatar
                    ? `<img src="${userAvatar}" alt="${userName}">`
                    : `<div class="mention-user-avatar-letter" style="background: ${bgColor};">${firstLetter}</div>`;

                const phoneHtml = userPhone ? `<div class="mention-user-phone"><i class="fa fa-phone"></i> ${userPhone}</div>` : '';

                html += `
                                        <div class="mention-user-item" onclick="insertMention('${safeUserName}', ${userId})" data-user-id="${userId}">
                                            <div class="mention-user-avatar">
                                                ${avatarHtml}
                                            </div>
                                            <div class="mention-user-info">
                                                <div class="mention-user-name">${userName}</div>
                                                ${phoneHtml}
                                                <div class="mention-user-role"><i class="fa fa-user-tag"></i> ${userRole}</div>
                                            </div>
                                        </div>
                                    `;
            });

            $('#mentionUsersList').html(html);
        } function filterMentionUsers() {
            const searchTerm = $('#mentionSearch').val().trim();
            // Load users from server with search term
            loadMentionUsers(searchTerm);
        }

        function insertMention(userName, userId) {
            const textarea = document.getElementById('sidebarNoteTextarea');
            const mention = `@${userName} `;

            // Get current cursor position
            const cursorPos = textarea.selectionStart;
            const textBefore = textarea.value.substring(0, cursorPos);
            const textAfter = textarea.value.substring(cursorPos);

            // Insert mention at cursor position
            textarea.value = textBefore + mention + textAfter;

            // Set cursor position after mention
            const newPos = cursorPos + mention.length;
            textarea.setSelectionRange(newPos, newPos);
            textarea.focus();

            // Close dropdown
            document.getElementById('mentionDropdown').style.display = 'none';
        }

        // Close mention dropdown when clicking outside
        $(document).on('click', function (e) {
            const dropdown = document.getElementById('mentionDropdown');
            const button = $('.btn-mention')[0];

            if (dropdown && dropdown.style.display !== 'none') {
                if (!dropdown.contains(e.target) && e.target !== button && !button.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            }
        });

        function loadNotes(bookingId) {
            showLoading();

            $.ajax({
                url: '{{ route("report.admin.booking.get-notes") }}',
                method: 'GET',
                data: {
                    booking_id: bookingId
                },
                success: function (response) {
                    hideLoading();
                    if (response.success) {
                        displayNotes(response.notes);
                    } else {
                        $('#notesContent').html('<div class="empty-notes"><i class="fa fa-exclamation-circle"></i><p>' + (response.message || '{{ __("Failed to load notes") }}') + '</p></div>');
                    }
                },
                error: function () {
                    hideLoading();
                    $('#notesContent').html('<div class="empty-notes"><i class="fa fa-exclamation-circle"></i><p>{{ __("An error occurred while loading notes") }}</p></div>');
                }
            });
        }

        function displayNotes(notes) {
            if (!notes || notes.length === 0) {
                $('#notesContent').html(`
                                                        <div class="empty-notes">
                                                            <i class="fa fa-sticky-note"></i>
                                                            <p>{{ __("No Notes Yet!") }}</p>
                                                        </div>
                                                    `);
                return;
            }

            let html = '<div class="notes-timeline">';
            notes.forEach(note => {
                const avatarContent = note.user_avatar
                    ? `<img src="${note.user_avatar}" alt="${note.user_name}">`
                    : `<div class="note-avatar-letter" style="background: ${getRandomColor()};">${note.user_name.charAt(0).toUpperCase()}</div>`;

                // Build attachments HTML
                let attachmentsHtml = '';
                if (note.attachments && note.attachments.length > 0) {
                    attachmentsHtml = '<div class="note-attachments">';
                    note.attachments.forEach(attachment => {
                        const ext = attachment.extension.toLowerCase();
                        const fileSize = formatFileSize(attachment.size);
                        const fileName = attachment.original_name;

                        let thumbnailHtml = '';

                        // Check if image
                        if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
                            thumbnailHtml = `<img src="${attachment.url}" alt="${fileName}" class="attachment-thumbnail">`;
                        }
                        // PDF icon
                        else if (ext === 'pdf') {
                            thumbnailHtml = `<div class="attachment-icon-wrapper attachment-icon-pdf-wrapper"><i class="fa fa-file-pdf"></i></div>`;
                        }
                        // Document icon
                        else if (['doc', 'docx'].includes(ext)) {
                            thumbnailHtml = `<div class="attachment-icon-wrapper attachment-icon-doc-wrapper"><i class="fa fa-file-word"></i></div>`;
                        }
                        // Text file icon
                        else if (ext === 'txt') {
                            thumbnailHtml = `<div class="attachment-icon-wrapper attachment-icon-text-wrapper"><i class="fa fa-file-alt"></i></div>`;
                        }
                        // Default file icon
                        else {
                            thumbnailHtml = `<div class="attachment-icon-wrapper attachment-icon-doc-wrapper"><i class="fa fa-file"></i></div>`;
                        }

                        attachmentsHtml += `
                                    <a href="${attachment.url}" class="note-attachment" download="${fileName}" target="_blank">
                                        ${thumbnailHtml}
                                        <div class="attachment-info">
                                            <span class="attachment-name">${fileName}</span>
                                            <span class="attachment-size">${fileSize}</span>
                                        </div>
                                    </a>
                                `;
                    });
                    attachmentsHtml += '</div>';
                }

                html += `
                                                        <div class="note-item">
                                                            <div class="note-avatar">
                                                                ${avatarContent}
                                                            </div>
                                                            <div class="note-header">
                                                                <span class="note-author">${note.user_name}</span>
                                                                <span class="note-time">${note.created_at}</span>
                                                            </div>
                                                            <div class="note-content">${note.content}</div>
                                                            ${attachmentsHtml}
                                                        </div>
                                                    `;
            });
            html += '</div>';

            $('#notesContent').html(html);
        }

        function getRandomColor() {
            const colors = ['#6366f1', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#3b82f6', '#14b8a6', '#f97316'];
            return colors[Math.floor(Math.random() * colors.length)];
        }

        function openAddNoteFromView() {
            closeNotesSidebar();
            setTimeout(() => {
                openAddNoteModal(currentBookingId);
            }, 300);
        }

        // Handle file selection
        function handleFileSelect(event) {
            const files = event.target.files;
            const previewContainer = $('#attachmentPreview');
            previewContainer.empty();

            if (files.length > 0) {
                previewContainer.show();
                Array.from(files).forEach((file, index) => {
                    const ext = file.name.split('.').pop().toLowerCase();
                    const fileSize = formatFileSize(file.size);

                    const attachmentItem = $('<div class="attachment-item" data-index="' + index + '"></div>');

                    // Check if image to show thumbnail
                    if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const thumbnail = $('<img class="attachment-thumbnail" src="' + e.target.result + '" alt="' + file.name + '">');
                            attachmentItem.append(thumbnail);
                        };
                        reader.readAsDataURL(file);
                    }
                    // PDF icon
                    else if (ext === 'pdf') {
                        attachmentItem.append('<div class="attachment-icon-wrapper attachment-icon-pdf-wrapper"><i class="fa fa-file-pdf"></i></div>');
                    }
                    // Document icon
                    else if (['doc', 'docx'].includes(ext)) {
                        attachmentItem.append('<div class="attachment-icon-wrapper attachment-icon-doc-wrapper"><i class="fa fa-file-word"></i></div>');
                    }
                    // Text file icon
                    else if (ext === 'txt') {
                        attachmentItem.append('<div class="attachment-icon-wrapper attachment-icon-text-wrapper"><i class="fa fa-file-alt"></i></div>');
                    }
                    // Default file icon
                    else {
                        attachmentItem.append('<div class="attachment-icon-wrapper attachment-icon-doc-wrapper"><i class="fa fa-file"></i></div>');
                    }

                    const fileInfo = $(`
                                <div class="attachment-info">
                                    <span class="attachment-name">${file.name}</span>
                                    <span class="attachment-size">${fileSize}</span>
                                </div>
                            `);

                    const removeBtn = $('<i class="fa fa-times attachment-item-remove" onclick="removeAttachment(' + index + ')"></i>');

                    attachmentItem.append(fileInfo);
                    attachmentItem.append(removeBtn);
                    previewContainer.append(attachmentItem);
                });
            } else {
                previewContainer.hide();
            }
        }

        // Remove attachment from preview
        function removeAttachment(index) {
            const fileInput = document.getElementById('noteAttachments');
            const dt = new DataTransfer();
            const files = fileInput.files;

            for (let i = 0; i < files.length; i++) {
                if (i !== index) {
                    dt.items.add(files[i]);
                }
            }

            fileInput.files = dt.files;
            handleFileSelect({ target: fileInput });
        }

        // Get file icon based on extension
        function getFileIcon(filename) {
            const ext = filename.split('.').pop().toLowerCase();
            if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
                return 'fa-file-image attachment-icon-image';
            } else if (ext === 'pdf') {
                return 'fa-file-pdf attachment-icon-pdf';
            } else if (['doc', 'docx'].includes(ext)) {
                return 'fa-file-word attachment-icon-doc';
            } else {
                return 'fa-file attachment-icon-doc';
            }
        }

        // Format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        // Save Note from Sidebar
        function saveNoteSidebar() {
            const noteText = $('#sidebarNoteTextarea').val().trim();

            if (!noteText) {
                alert('{{ __("Please enter a note") }}');
                return;
            }

            if (!currentBookingId) {
                alert('{{ __("Booking ID not found") }}');
                return;
            }

            showLoading();

            // Create FormData for file upload
            const formData = new FormData();
            formData.append('booking_id', currentBookingId);
            formData.append('note', noteText);
            formData.append('_token', '{{ csrf_token() }}');

            // Add attachments if any
            const fileInput = document.getElementById('noteAttachments');
            if (fileInput.files.length > 0) {
                Array.from(fileInput.files).forEach((file) => {
                    formData.append('attachments[]', file);
                });
            }

            $.ajax({
                url: '{{ route("report.admin.booking.add-note") }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success) {
                        $('#sidebarNoteTextarea').val('');
                        fileInput.value = '';
                        $('#attachmentPreview').empty().hide();
                        hideLoading();
                        loadNotes(currentBookingId);
                    } else {
                        hideLoading();
                        alert(response.message || '{{ __("Failed to save note") }}');
                    }
                },
                error: function (xhr) {
                    hideLoading();
                    alert('Error: ' + (xhr.responseJSON?.message || '{{ __("Something went wrong") }}'));
                }
            });
        }

        // Add Note Modal & Functions
        function openAddNoteModal(bookingId) {
            currentBookingId = bookingId;
            $('#addNoteModal').data('booking-id', bookingId);
            $('#addNoteModal').modal('show');
            $('#noteTextarea').val('');
        }

        function saveNote() {
            const bookingId = $('#addNoteModal').data('booking-id');
            const noteText = $('#noteTextarea').val().trim();

            if (!noteText) {
                alert('{{ __("Please enter a note") }}');
                return;
            }

            showLoading();

            $.ajax({
                url: '{{ route("report.admin.booking.add-note") }}',
                method: 'POST',
                data: {
                    booking_id: bookingId,
                    note: noteText,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        $('#addNoteModal').modal('hide');
                        hideLoading();

                        // Open sidebar again and reload notes
                        setTimeout(() => {
                            openNotesModal(bookingId);
                        }, 300);
                    } else {
                        hideLoading();
                        alert(response.message || '{{ __("Failed to save note") }}');
                    }
                },
                error: function (xhr) {
                    hideLoading();
                    alert('Error: ' + (xhr.responseJSON?.message || '{{ __("Something went wrong") }}'));
                }
            });
        }
    </script>

    <!-- Add Note Modal -->
    <div class="modal fade" id="addNoteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content"
                style="background: var(--sales-bg-secondary); color: var(--sales-text-primary); border: 1px solid var(--sales-border-color);">
                <div class="modal-header" style="border-bottom: 1px solid var(--sales-border-color);">
                    <h5 class="modal-title">{{ __('Add Note') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="color: var(--sales-text-primary);">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="noteTextarea">{{ __('Note') }}</label>
                        <textarea class="form-control" id="noteTextarea" rows="5"
                            placeholder="{{ __('Enter your note here...') }}"
                            style="background: var(--sales-bg-primary); color: var(--sales-text-primary); border: 1px solid var(--sales-border-color);">
                                                                                </textarea>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <small class="text-muted" style="color: var(--sales-text-secondary) !important;">
                            <i class="fa fa-info-circle"></i> {{ __('This note will be visible to all staff members') }}
                        </small>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--sales-border-color);">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-primary" onclick="saveNote()"
                        style="background: #6366f1; border-color: #6366f1;">
                        <i class="fa fa-save"></i> {{ __('Save Note') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes Sidebar -->
    <div id="notesSidebar" class="notes-sidebar">
        <div class="notes-sidebar-overlay" onclick="closeNotesSidebar()"></div>
        <div class="notes-sidebar-content">
            <div class="notes-sidebar-header">
                <div>
                    <h5 class="notes-sidebar-title">
                        <i class="fa fa-sticky-note"></i> {{ __('Order Note:') }} <span id="orderNoteNumber"></span>
                    </h5>
                </div>
                <button type="button" class="notes-close-btn" onclick="closeNotesSidebar()">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <!-- Add Note Section -->
            <div class="notes-add-section">
                <div class="form-group" style="margin-bottom: 12px;">
                    <label for="sidebarNoteTextarea"
                        style="color: var(--sales-text-primary); font-weight: 500; font-size: 14px; margin-bottom: 8px; display: block;">
                        {{ __('Note') }}
                    </label>
                    <textarea class="form-control sidebar-note-input" id="sidebarNoteTextarea" rows="3"
                        placeholder="{{ __('Enter your note here...') }}"></textarea>
                </div>

                <!-- Attachment Preview -->
                <div id="attachmentPreview" class="attachment-preview" style="display: none;"></div>

                <div style="display: flex; gap: 10px; align-items: center; position: relative;">
                    <button type="button" class="btn-mention" onclick="toggleMentionDropdown()">
                        <i class="fa fa-at"></i> {{ __('Mention') }}
                    </button>

                    <!-- Mention Dropdown -->
                    <div id="mentionDropdown" class="mention-dropdown" style="display: none;">
                        <div class="mention-dropdown-header">
                            <div
                                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                <span style="font-size: 12px; color: var(--sales-text-secondary); font-weight: 600;">
                                    <i class="fa fa-users"></i> {{ __('Select User') }}
                                </span>
                                <span id="mentionUsersCount"
                                    style="font-size: 11px; color: var(--sales-text-secondary);"></span>
                            </div>
                            <input type="text" id="mentionSearch" class="mention-search"
                                placeholder="{{ __('Search by name or phone...') }}" onkeyup="filterMentionUsers()">
                        </div>
                        <div class="mention-dropdown-body" id="mentionUsersList">
                            <!-- Users will be loaded here -->
                        </div>
                    </div>

                    <input type="file" id="noteAttachments" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt"
                        style="display: none;" onchange="handleFileSelect(event)">
                    <button type="button" class="btn-attach" onclick="document.getElementById('noteAttachments').click()">
                        <i class="fa fa-paperclip"></i>
                    </button>
                    <button type="button" class="btn-submit-note" onclick="saveNoteSidebar()">
                        {{ __('SUBMIT') }}
                    </button>
                </div>
            </div>

            <div class="notes-sidebar-body" id="notesContent">
                <!-- Notes timeline will be loaded here -->
            </div>
        </div>
    </div>
@endsection
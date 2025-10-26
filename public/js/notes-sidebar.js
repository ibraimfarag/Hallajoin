// Notes Sidebar JavaScript - Clean Version
function openNotesModal(bookingId) {
    console.log('Opening notes for booking:', bookingId);

    if (!bookingId) {
        alert('لا يوجد رقم طلب');
        return;
    }

    window.notesBookingId = bookingId;

    // Get booking order number for display
    const orderNumber = $('[data-booking-id="' + bookingId + '"]').closest('tr').find('.order-link').text().trim();
    $('#orderNoteNumber').text(orderNumber || bookingId);

    // Clear textarea
    $('#sidebarNoteTextarea').val('');
    $('#attachmentPreview').empty();

    // Open sidebar
    $('#notesSidebar').addClass('open');
    $('body').css('overflow', 'hidden');

    // Load notes
    loadNotes(bookingId);

    // Focus on textarea
    setTimeout(() => {
        $('#sidebarNoteTextarea').focus();
    }, 400);
}

function closeNotesSidebar() {
    $('#notesSidebar').removeClass('open');
    $('body').css('overflow', '');
    window.notesBookingId = null;
}

function loadNotes(bookingId) {
    console.log('Loading notes for booking ID:', bookingId);

    if (!bookingId) {
        console.error('No booking ID provided');
        return;
    }

    // Show loading in notes area
    $('#notesTimeline').html('<div style="text-align: center; padding: 40px; color: #666;"><i class="fa fa-spinner fa-spin"></i> loading...</div>');

    // First try to get the correct booking ID from the row
    const actualBookingId = $('[data-booking-id="' + bookingId + '"]').data('booking-id');
    console.log('Actual booking ID from data attribute:', actualBookingId);

    $.ajax({
        url: '/admin/module/report/booking/get-notes',
        method: 'GET',
        data: {
            booking_id: actualBookingId || bookingId
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            console.log('Notes API response:', response);

            if (response && response.success) {
                if (response.notes && response.notes.length > 0) {
                    displayNotes(response.notes);
                } else {
                    $('#notesTimeline').html('<div style="text-align: center; padding: 40px; color: #999;"><i class="fa fa-sticky-note-o" style="font-size: 48px; margin-bottom: 15px; display: block;"></i>لا توجد ملاحظات حتى الآن</div>');
                }
            } else {
                console.error('API returned error:', response ? response.message : 'Unknown error');
                $('#notesTimeline').html('<div style="text-align: center; padding: 40px; color: #e74c3c;"><i class="fa fa-exclamation-triangle"></i> خطأ في تحميل الملاحظات: ' + (response ? response.message : 'خطأ غير معروف') + '</div>');
            }
        },
        error: function (xhr, status, error) {
            console.error('AJAX Error:', {
                status: status,
                error: error,
                responseText: xhr.responseText,
                url: '/admin/module/report/booking/get-notes',
                data: { booking_id: actualBookingId || bookingId }
            });

            let errorMsg = 'فشل في الاتصال بالخادم';
            if (xhr.status === 404) {
                errorMsg = 'الصفحة غير موجودة (404)';
            } else if (xhr.status === 403) {
                errorMsg = 'غير مسموح (403)';
            } else if (xhr.status === 500) {
                errorMsg = 'خطأ في الخادم (500)';
            }

            $('#notesTimeline').html('<div style="text-align: center; padding: 40px; color: #e74c3c;"><i class="fa fa-times-circle"></i> ' + errorMsg + '</div>');
        }
    });
}

function displayNotes(notes) {
    console.log('Displaying notes:', notes);

    if (!notes || notes.length === 0) {
        $('#notesTimeline').html('<div style="text-align: center; padding: 40px; color: #999;"><i class="fa fa-sticky-note-o" style="font-size: 48px; margin-bottom: 15px; display: block;"></i>لا توجد ملاحظات</div>');
        return;
    }

    let html = '';
    notes.forEach(note => {
        const userName = note.user_name || 'مستخدم غير معروف';
        const userAvatar = note.user_avatar;
        const firstLetter = userName.charAt(0).toUpperCase();
        const colors = ['#6366f1', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#3b82f6', '#14b8a6', '#f97316'];
        const colorIndex = firstLetter.charCodeAt(0) % colors.length;
        const bgColor = colors[colorIndex];

        const avatarHtml = userAvatar
            ? `<img src="${userAvatar}" alt="${userName}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">`
            : `<div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: ${bgColor}; color: white; font-weight: 600; font-size: 16px; border-radius: 50%;">${firstLetter}</div>`;

        // Handle attachments
        let attachmentsHtml = '';
        if (note.attachments && note.attachments.length > 0) {
            attachmentsHtml = '<div style="margin-top: 12px; display: flex; flex-wrap: wrap; gap: 12px;">';
            note.attachments.forEach(attachment => {
                const extension = attachment.extension ? attachment.extension.toLowerCase() : '';
                const isImage = ['jpg', 'jpeg', 'png', 'gif'].includes(extension);
                const isPdf = extension === 'pdf';
                const isDoc = ['doc', 'docx'].includes(extension);

                let iconHtml = '';
                if (isImage) {
                    iconHtml = `<img src="${attachment.url}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 2px solid var(--sales-border-color);" alt="${attachment.original_name}">`;
                } else if (isPdf) {
                    iconHtml = `<div style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; background: rgba(239, 68, 68, 0.1); color: #ef4444; border-radius: 8px; font-size: 28px;"><i class="fa fa-file-pdf-o"></i></div>`;
                } else if (isDoc) {
                    iconHtml = `<div style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; background: rgba(59, 130, 246, 0.1); color: #3b82f6; border-radius: 8px; font-size: 28px;"><i class="fa fa-file-word-o"></i></div>`;
                } else {
                    iconHtml = `<div style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; background: rgba(168, 85, 247, 0.1); color: #a855f7; border-radius: 8px; font-size: 28px;"><i class="fa fa-file-text-o"></i></div>`;
                }

                const sizeFormatted = formatFileSize(attachment.size || 0);

                attachmentsHtml += `
                    <a href="${attachment.url}" target="_blank" style="display: inline-flex; align-items: center; gap: 12px; padding: 10px 14px; background: var(--sales-hover-bg); border: 1px solid var(--sales-border-color); border-radius: 10px; text-decoration: none; color: var(--sales-text-primary); transition: all 0.2s; max-width: 350px;">
                        ${iconHtml}
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-weight: 600; font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${attachment.original_name}</div>
                            <div style="font-size: 12px; color: var(--sales-text-secondary); margin-top: 4px;">${sizeFormatted}</div>
                        </div>
                    </a>
                `;
            });
            attachmentsHtml += '</div>';
        }

        html += `
            <div style="position: relative; padding-left: 60px; margin-bottom: 24px;">
                <div style="position: absolute; left: 0; top: 0; width: 40px; height: 40px; border-radius: 50%; overflow: hidden; border: 3px solid var(--sales-bg-primary); box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                    ${avatarHtml}
                </div>
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="font-weight: 600; font-size: 14px; color: var(--sales-text-primary);">${userName}</span>
                        <span style="font-size: 12px; color: var(--sales-text-secondary);">${note.created_at}</span>
                    </div>
                    <div style="font-size: 14px; line-height: 1.6; color: var(--sales-text-primary); white-space: pre-wrap; word-wrap: break-word;">${note.content}</div>
                    ${attachmentsHtml}
                </div>
            </div>
        `;
    });

    $('#notesTimeline').html(html);
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function saveNoteSidebar() {
    console.log('Saving note for booking:', window.notesBookingId);

    if (!window.notesBookingId) {
        alert('Booking ID not found');
        return;
    }

    const noteText = $('#sidebarNoteTextarea').val().trim();
    if (!noteText) {
        alert('Please enter a note');
        return;
    }

    // Create FormData for file upload
    const formData = new FormData();
    formData.append('booking_id', window.notesBookingId);
    formData.append('note', noteText);
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

    // Add attachments if any
    const fileInput = document.getElementById('noteAttachments');
    if (fileInput && fileInput.files.length > 0) {
        Array.from(fileInput.files).forEach((file) => {
            formData.append('attachments[]', file);
        });
    }

    console.log('Sending FormData with booking_id:', window.notesBookingId);

    $.ajax({
        url: '/admin/module/report/booking/add-note',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log('Add note response:', response);

            if (response.success) {
                $('#sidebarNoteTextarea').val('');
                if (fileInput) fileInput.value = '';

                // Reload notes
                loadNotes(window.notesBookingId);

                // Show success message
                alert('Note added successfully');
            } else {
                alert('Error: ' + (response.message || 'Failed to add note'));
            }
        },
        error: function (xhr, status, error) {
            console.error('Error adding note:', {
                status: status,
                error: error,
                responseText: xhr.responseText,
                booking_id: window.notesBookingId
            });

            let errorMsg = 'Failed to add note';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            } else if (xhr.status === 422) {
                errorMsg = 'Validation error - check booking ID';
            }

            alert(errorMsg);
        }
    });
}

// Initialize when document is ready
$(document).ready(function () {
    console.log('Notes sidebar JavaScript loaded');

    // Add CSRF token to all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});
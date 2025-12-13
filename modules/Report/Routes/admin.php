<?php

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/1/2019
 * Time: 10:02 AM
 */
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'booking'], function () {
    Route::get('/', 'BookingController@index')->name('report.admin.booking');
    Route::get('/email_preview/{id}', 'BookingController@email_preview')->name('report.admin.booking.email_preview');
    Route::post('/bulkEdit', 'BookingController@bulkEdit')->name('report.admin.booking.bulkEdit');
    Route::post('/add-note', 'BookingController@addNote')->name('report.admin.booking.add-note');
    Route::get('/get-notes', 'BookingController@getNotes')->name('report.admin.booking.get-notes');
    Route::post('/confirm-order', 'BookingController@confirmOrder')->name('report.admin.booking.confirm-order');
    Route::post('/make-pending', 'BookingController@makePending')->name('report.admin.booking.make-pending');
    Route::get('/customer-info', 'BookingController@getCustomerInfo')->name('report.admin.booking.customer-info');
    Route::get('/get-booking-details', 'BookingController@getBookingDetails')->name('report.admin.booking.get-details');

    // Create Order Routes
    Route::get('/create', 'BookingController@createOrder')->name('report.admin.booking.create');
    Route::post('/search-activities', 'BookingController@searchActivities')->name('report.admin.booking.search-activities');
    Route::post('/search-customer', 'BookingController@searchCustomer')->name('report.admin.booking.search-customer');
    Route::post('/get-activity-details', 'BookingController@getActivityDetails')->name('report.admin.booking.activity-details');
    Route::post('/get-available-times', 'BookingController@getAvailableTimes')->name('report.admin.booking.available-times');
    Route::post('/create-order', 'BookingController@storeOrder')->name('report.admin.booking.store-order');
});
Route::get('/enquiry', 'EnquiryController@index')->name('report.admin.enquiry.index');

Route::post('/enquiry/bulkEdit', 'EnquiryController@bulkEdit')->name('report.admin.enquiry.bulkEdit');

Route::get('/enquiry/{enquiry}/reply', 'EnquiryController@reply')->name('report.admin.enquiry.reply');
Route::post('/enquiry/{enquiry}/reply/store', 'EnquiryController@replyStore')->name('report.admin.enquiry.replyStore');
Route::get('/enquiry/users-for-mention', 'EnquiryController@getUsersForMention')->name('report.admin.enquiry.getUsersForMention');
Route::get('/enquiry/{enquiry}/notes', 'EnquiryController@getNotes')->name('report.admin.enquiry.getNotes');
Route::post('/enquiry/{enquiry}/notes/store', 'EnquiryController@storeNote')->name('report.admin.enquiry.storeNote');
Route::get('/statistic', 'StatisticController@index')->name('report.admin.statistic.index');
Route::match(['get', 'post'], '/statistic/reloadChart', 'StatisticController@reloadChart')->name('report.admin.statistic.reloadChart');
Route::get('/sale-rate', 'SaleRateController@index')->name('report.admin.sale-rate.index');

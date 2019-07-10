<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::post('stripe-webhook', 'WebhookController@handle')->name('webhook.handle');
Route::get('home', 'HomeController@index')->name('home');
Route::get('plans', 'SubscriptionPlansController@index');
Route::post('register', 'Auth\RegisterController@register')->name('register')->middleware('guest');

Route::middleware('auth')->group(function () {
    Route::post('plans/{plan}/checkout', 'SubscriptionsController@checkout');
    Route::get('success', 'SubscriptionsController@success');
    Route::post('workspaces/{workspace}/add-slot', 'AddMemberSlotController@store');

    Route::post('workspaces/{workspace}/renew', 'RenewSubscriptionsController@store');
    Route::get('workspaces/{workspace}/subscription-expired', 'RenewSubscriptionsController@show')
        ->name('subscription-expired.show');

    Route::get('/dashboard', 'AdminDashboardController@show')->name('dashboard');
    Route::patch('/workspaces/{workspace}/members/{memberId}', 'WorkspaceMembersController@update')
        ->name('members.update');
    Route::post('accept-invitation', 'AcceptInvitationsController@store')
        ->name('accept-invitation.store');

    Route::get('/profiles/{user}/notifications', 'UserNotificationsController@index')
        ->name('notifications.index');
    Route::delete('/profiles/{user}/notifications/{notificationId}', 'UserNotificationsController@destroy')
        ->name('notifications.delete');

    Route::delete('/profiles/{user}/notifications/', 'ClearAllNotificationsController@destroy')
        ->name('notifications.delete-all');
});

Route::group(['prefix' => 'workspace-setup', 'middleware' => 'auth', 'namespace' => 'AccountSetup'], function () {
    Route::get('{authorization}', 'InitialSetupController@show')->name('workspace-setup.show');
    Route::post('workspace', 'WorkspacesController@store')->name('store-workspace');
    Route::post('{workspace}/members', 'InviteMembersController@store')->name('invite-members');
});

Route::get('invitations/{code}', 'InvitationsController@show')->name('invitations.show');

Route::post('register-invitees', 'Auth\RegisterController@registerInvitees')
    ->name('invitees.register')
    ->middleware('guest');

Route::group(['middleware' => 'auth', 'prefix' => 'workspaces'], function () {
    Route::get('{workspace}', 'WorkspacesController@show')->name('workspaces.show');
    Route::get('{workspace}/tasks', 'WorkspaceTasksController@index')->name('tasks.index');
    Route::get('{workspace}/tasks/create', 'WorkspaceTasksController@create')->name('tasks.create');
    Route::post('{workspace}/tasks', 'WorkspaceTasksController@store')->name('tasks.store');

    Route::patch('{workspace}/tasks/{task}', 'WorkspaceTasksController@update')->name('tasks.update')
        ->middleware('throttle:5,1');

    Route::delete('{workspace}/tasks/{task}', 'WorkspaceTasksController@destroy')->name('tasks.delete');

    Route::get('{workspace}/members', 'WorkspaceMembersController@index')->name('workspace-members.index');
});


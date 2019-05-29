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

Route::post('register', 'Auth\RegisterController@register')->name('register')->middleware('guest');
Route::get('home', 'HomeController@index')->name('home');

Route::get('plans', 'SubscriptionPlansController@index');
Route::post('plans/{plan}/checkout', 'SubscriptionsController@checkout')->middleware('auth');
Route::get('success', 'SubscriptionsController@success')->middleware('auth');

Route::post('checkout-webhook', 'WebhookController@checkout')->name('checkout');
	
Route::group(['prefix' => 'workspace-setup', 'middleware' => 'auth', 'namespace' => 'AccountSetup'], function () {
	Route::get('{authorization}', 'InitialSetupController@show')->name('workspace-setup.show');
	Route::post('workspace', 'WorkspacesController@store')->name('store-workspace');
	Route::post('{workspace}/members', 'InviteMembersController@store')->name('invite-members');
});

Route::get('invitations/{code}', 'InvitationsController@show')->name('invitations.show')->middleware('guest');
Route::post('register-invitees', 'Auth\RegisterController@registerInvitees')->name('invitees.register')->middleware('guest');

Route::group(['middleware' => 'auth', 'prefix' => 'workspaces'], function () {
	Route::get('{workspace}', 'WorkspacesController@show')->name('workspaces.show');
	Route::get('{workspace}/tasks', 'WorkspaceTasksController@index')->name('tasks.index');
	Route::get('{workspace}/tasks/create', 'WorkspaceTasksController@create')->name('tasks.create');
	Route::post('{workspace}/tasks', 'WorkspaceTasksController@store')->name('tasks.store');
	Route::delete('{workspace}/tasks/{task}', 'WorkspaceTasksController@destroy')->name('tasks.delete');

	Route::get('{workspace}/members', 'WorkspaceMembersController@index')->name('workspace-members.index');
});

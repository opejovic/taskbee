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

Route::get('home', 'HomeController@index')->name('home');

Route::get('bundles', 'SubscriptionPlansController@index');
Route::post('bundles/{plan}/purchase', 'SubscriptionsController@store');

Route::group(['prefix' => 'workspace-setup', 'namespace' => 'AccountSetup'], function () {
	Route::get('{authorization}', 'InitialSetupController@show')->name('workspace-setup.show');
	Route::post('admin', 'AdminsController@store')->name('store-admin');
	Route::post('workspace', 'WorkspacesController@store')->name('store-workspace')->middleware('auth');
	Route::post('{workspace}/members', 'InviteMembersController@store')->name('invite-members')->middleware('auth');
});

Route::get('invitations/{code}', 'InvitationsController@show')->name('invitations.show');
Route::post('register', 'Auth\RegisterController@register')->name('register');

Route::group(['middleware' => 'auth', 'prefix' => 'workspaces'], function () {
	Route::get('{workspace}', 'WorkspacesController@show')->name('workspaces.show');
	Route::get('{workspace}/tasks/create', 'WorkspaceTasksController@create');
	Route::post('{workspace}/tasks', 'WorkspaceTasksController@store')->name('tasks.store');
	Route::delete('{workspace}/tasks/{task}', 'WorkspaceTasksController@destroy');
});

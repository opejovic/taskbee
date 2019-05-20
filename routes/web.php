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

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/bundles', 'SubscriptionPlansController@index');
Route::post('/bundles/{plan}/purchase', 'SubscriptionsController@store');

Route::get('/workspace-setup/{authorization}', 'AccountSetup\InitialSetupController@show')->name('workspace-setup.show');
Route::post('/workspace-setup/admin', 'AccountSetup\AdminsController@store')->name('store-admin');
Route::post('/workspace-setup/workspace', 'AccountSetup\WorkspacesController@store')->name('store-workspace');
Route::post('/workspace-setup/{workspace}/members', 'AccountSetup\InviteMembersController@store')->name('invite-members');

Route::get('/workspaces/{workspace}', 'WorkspaceController@show')->name('workspaces.show');
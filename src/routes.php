<?php

// Use 'web' middleware if you using laravel version since 5.2

Route::group(['prefix' => 'admin', 'middleware' => 'web'], function () {
    /*
    |-------------------------------------------------------
    | Authentication
    |-------------------------------------------------------
    */
    Route::get('login', 'Keyhunter\Administrator\AuthController@getLogin');
    Route::post('login', 'Keyhunter\Administrator\AuthController@postLogin');
    Route::get('logout', 'Keyhunter\Administrator\AuthController@logout');
});

Route::group(['prefix' => 'admin'
    , 'middleware' => ['web', '\Keyhunter\Administrator\Middleware\Authenticate']
], function () {
    Route::get('/', function () {
        $homepage = config('administrator.home_page', '/members');

        return \Redirect::to($homepage);
    });

    /*
    |-------------------------------------------------------
    | Settings
    |-------------------------------------------------------
    */
    Route::group(['middleware' => '\Keyhunter\Administrator\Middleware\Settings'], function () {
        Route::get('settings/{page}',
            [
                'as' => 'admin_settings_edit',
                'uses' => 'Keyhunter\Administrator\Controller@listSettings'
            ]);

        Route::post('settings/{page}',
            [
                'as' => 'admin_settings_update',
                'uses' => 'Keyhunter\Administrator\Controller@saveSettings'
            ]);
    });

    /*
    |-------------------------------------------------------
    | Main Scaffolding routes
    |-------------------------------------------------------
    */
    Route::group(['middleware' => '\Keyhunter\Administrator\Middleware\Module'], function () {
        /*
        |-------------------------------------------------------
        | Custom routes
        |-------------------------------------------------------
        |
        | Controllers that shouldn't be handled by Scaffolding controller
        | goes here.
        |
        */
//        Route::controllers([
//            'test' => 'App\Http\Controllers\Admin\TestController'
//        ]);


        /*
        |-------------------------------------------------------
        | Scaffolding routes
        |-------------------------------------------------------
        */
        // Dashboard
        Route::get('dashboard',
            [
                'as' => 'admin_dashboard',
                'uses' => 'Keyhunter\Administrator\Controller@dashboard'
            ]);

        // Index
        Route::get('{page}',
            [
                'as' => 'admin_model_index',
                'uses' => 'Keyhunter\Administrator\Controller@index'
            ]);

        // Create new Item
        Route::get('{page}/create',
            [
                'as' => 'admin_model_create',
                'uses' => 'Keyhunter\Administrator\Controller@create'
            ]);

        // Save new item
        Route::post('{page}/create', 'Keyhunter\Administrator\Controller@update');

        // View Item
        Route::get('{page}/{id}',
            [
                'as' => 'admin_model_view',
                'uses' => 'Keyhunter\Administrator\Controller@view'
            ]);

        // Edit Item
        Route::get('{page}/{id?}/edit',
            [
                'as' => 'admin_model_edit',
                'uses' => 'Keyhunter\Administrator\Controller@edit'
            ]);

        // Save Item
        Route::post('{page}/{id?}/edit',
            [
                'as' => 'admin_model_save',
                'uses' => 'Keyhunter\Administrator\Controller@update'
            ]);

        // Delete Item
        Route::get('{page}/{id}/delete',
            [
                'as' => 'admin_model_delete',
                'uses' => 'Keyhunter\Administrator\Controller@delete'
            ]);

        // Custom Item Action
        Route::get('{page}/{id}/do-{action}',
            [
                'as' => 'admin_model_custom_action',
                'uses' => 'Keyhunter\Administrator\Controller@custom'
            ]);

        // Custom Global Action
        Route::post('{page}/do-custom',
            [
                'as' => 'admin_model_global_action',
                'uses' => 'Keyhunter\Administrator\Controller@customGlobal'
            ]);
    });
});
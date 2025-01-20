<?php

use App\Http\Controllers\DataSnapController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UploadDataController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function () {

    /**
     * Login With Cookie
     *
     * - url      : /api/v1/login_cookie
     * - form-data: email, password
     */
    Route::post('login_cookie', [LoginController::class, 'loginWithCookie']);

    /**
     * Forgot Password
     *
     * - url      : /api/v1/forgot_pass
     * - form-data: email
     */
    Route::post('forgot_pass', [NotificationController::class, 'forgotPassword']);

    /**
     *  Edit Profile
     *
     * - url      : /api/v1/edit_profile
     * - form-data: email, name, new_password
     */
    Route::put('edit_profile', [ProfileController::class, 'editProfile'])->middleware('ApiGuard');

    /**
     * User Endpoint
     *
     * - CRUD master data user
     */
    Route::group(['prefix' => 'user', 'middleware' => ['ApiGuard', 'ApiForSuperadmin']], function () {
        /**
         * Get With Datatable
         *
         * - url    : /api/v1/user/datatable
         * - params : none
         */
        Route::get('/datatable', [UserController::class, 'getUserDataTable']);

        /**
         * Create New User
         *
         * - url    : /api/v1/user/create
         * - form-data : name, email, password, level_id, site_id
         */
        Route::post('/create', [UserController::class, 'createUser']);

        /**
         * Update Site
         *
         * - url    : /api/v1/site/update
         * - form-data : name, email, new_password, level_id, site_id
         */
        Route::put('/update', [UserController::class, 'updateUser']);

        /**
         * Delete User
         *
         * - url    : /api/v1/User/delete/{id}
         * - query-param: id
         */
        Route::delete('/delete/{id}', [UserController::class, 'deleteUser']);
    });
    
    /**
     * Progress Upload
     *
     */
    Route::group(['prefix' => 'upload_data', 'middleware' => ['ApiGuard', 'ApiForSuperadmin']], function () {
        /**
         * Set Active Tab
         *
         * - url    : /api/v1/upload_data/set_active_tab
         * - form-data : tab_name
         */
        Route::put('/set_active_tab', [UploadDataController::class, 'setActiveTab']);

        /**
         * Update Tab
         *
         * - url    : /api/v1/upload_data/update_tab
         * - form-data : tab_name, tab_status
         */
        Route::put('/update_tab', [UploadDataController::class, 'updateTab']);
        
        /**
         * Update Progress Status
         *
         * - url    : /api/v1/upload_data/update_progress_status
         * - form-data : progress_status
         */
        Route::put('/update_progress_status', [UploadDataController::class, 'updateProgressStatus']);
        
        /**
         * Import Followers Following
         *
         * - url    : /api/v1/upload_data/import_followers_following
         * - form-data : file_followers, file_following
         */
        Route::post('/import_followers_following', [UploadDataController::class, 'importFollowersAndFollowing']);

        /**
         * Generate Mutual
         *
         * - url    : /api/v1/upload_data/generate_mutual
         */
        Route::get('/generate_mutual', [UploadDataController::class, 'generateMutual']);
        
        /**
         * Scrape Mutual Detail
         *
         * - url    : /api/v1/upload_data/scrape_mutual_detail/{username}
         */
        Route::get('/scrape_mutual_detail/{username}', [UploadDataController::class, 'scrapeMutualDetail']);
        
        /**
         * Scrape Mutual Followers
         *
         * - url    : /api/v1/upload_data/scrape_mutual_followers/{username}
         */
        Route::get('/scrape_mutual_followers/{username}', [UploadDataController::class, 'scrapeMutualFollowers']);

        /**
         * Generate Nodes & Edges
         *
         * - url    : /api/v1/upload_data/generate_nodes_edges
         */
        Route::get('/generate_nodes_edges', [UploadDataController::class, 'generateNodeEdge']);
    });

    /**
     * Data Snap
     *
     */
    Route::group(['prefix' => 'datasnap', 'middleware' => ['ApiGuard', 'ApiForSuperadmin']], function () {
        /**
         * List Followers
         *
         * - url    : /api/v1/datasnap/list_followers
         */
        Route::post('/list_followers', [DataSnapController::class, 'listFollowers']);
        
        /**
         * List Following
         *
         * - url    : /api/v1/datasnap/list_following
         */
        Route::post('/list_following', [DataSnapController::class, 'listFollowing']);
        
        /**
         * List Mutual
         *
         * - url    : /api/v1/datasnap/list_mutual
         */
        Route::post('/list_mutual', [DataSnapController::class, 'listMutual']);
        
        /**
         * List Mutual Followers
         *
         * - url    : /api/v1/datasnap/list_mutual_followers
         */
        Route::post('/list_mutual_followers', [DataSnapController::class, 'listMutualFollowers']);
        
        /**
         * Get Nodes & Edges
         *
         * - url    : /api/v1/datasnap/nodes_edges
         */
        Route::get('/nodes_edges', [DataSnapController::class, 'getNodesEdges']);
        
        /**
         * Get Centrality Measure
         *
         * - url    : /api/v1/datasnap/centrality_measure
         */
        Route::post('/centrality_measure', [DataSnapController::class, 'getCentralityMeasure']);
    });
});

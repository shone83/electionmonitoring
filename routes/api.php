<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResources(['user' => 'API\UsersController']);
Route::apiResources(['role' => 'API\RolesController']);
Route::apiResources(['district' => 'API\DistrictsController']);
Route::apiResources(['town' => 'API\TownsController']);
Route::apiResources(['settlement' => 'API\SettlementsController']);
Route::apiResources(['electoral_unit' => 'API\ElectoralUnitsController']);
Route::apiResources(['local_list' => 'API\LocalListsController']);
Route::apiResources(['republic_list' => 'API\RepublicListsController']);
Route::apiResources(['presidential_list' => 'API\PresidentialListsController']);
Route::apiResources(['turnout' => 'API\TurnoutsController']);
Route::get('all_settlements', 'API\SettlementsController@allData');
Route::get('all_electoral_units', 'API\ElectoralUnitsController@allData');
Route::get('all_local_lists', 'API\LocalListsController@allData');
Route::get('all_republic_lists', 'API\RepublicListsController@allData');
Route::get('all_presidential_lists', 'API\PresidentialListsController@allData');
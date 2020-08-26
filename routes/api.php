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

Route::middleware('auth:api')->get('picking-list/{picking_no}', 'API\PickinglistController@getPickingList');
Route::middleware('auth:api')->post('picking-to-lmb', 'API\PickingToLMBController@storeScan');

Route::middleware('auth:api')->get('/user', function (Request $request) {
  return $request->user();
});

Route::get('branch', function(){
  return \App\Models\MasterCabang::select('kode_customer', 'long_description')->get();
});
Route::post('login', 'API\UserController@login');

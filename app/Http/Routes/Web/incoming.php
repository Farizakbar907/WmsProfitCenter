<?php

Route::group(['middleware' => 'auth'], function () {

  // Route::view('finish-good-production', 'web.incoming.finish-good-production.index');
  // Route::view('finish-good-production/create', 'web.incoming.finish-good-production.create');
  // Route::view('finish-good-production/{id}', 'web.incoming.finish-good-production.view');
  Route::resource('finish-good-production', 'Web\FinishGoodController');

  // Route::view('incoming-import-oem', 'web.incoming.incoming-import-oem.index');
  // Route::view('incoming-import-oem/create', 'web.incoming.incoming-import-oem.create');
  // Route::view('incoming-import-oem/{id}', 'web.incoming.incoming-import-oem.view');
  Route::post('incoming-import-oem/{id}/submit-to-inventory', 'Web\IncomingImportOEMController@submitToInventory');
  Route::post('incoming-import-oem/{id}/detail', 'Web\IncomingImportOEMDetailController@store');
  Route::delete('incoming-import-oem/{incoming_manual_id}/detail/{detail_id}', 'Web\IncomingImportOEMDetailController@destroy');
  Route::resource('incoming-import-oem', 'Web\IncomingImportOEMController');

  Route::view('conform-manifest', 'web.incoming.conform-manifest.index');
  Route::view('conform-manifest/{id}', 'web.incoming.conform-manifest.view');

  Route::view('billing-return', 'web.incoming.billing-return.index');
  Route::view('billing-return/{id}', 'web.incoming.billing-return.view');

});

<?php

Route::get('/', function () {
    return redirect()->route('website.index');
});

Route::get('/websites', 'WebsiteController@index')->name('website.index');
Route::get('/websites/{id}', 'WebsiteController@show')->name('website.show');
Route::delete('/websites/{id}', 'WebsiteController@delete')->name('website.delete');
Route::post('/websites/new', 'WebsiteController@create')->name('website.create');
Route::post('/websites/crawl/{id}', 'WebsiteController@crawl')->name('website.crawl');

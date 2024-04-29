<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $indexJSON = File::get('./downtime.json');
    $decodeJSON = json_decode($indexJSON, true);
    return view('home',["decodeJSON" => $decodeJSON, "indexJSON" => $indexJSON]);
});

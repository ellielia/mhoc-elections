<?php

use Illuminate\Http\Request;

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

Route::get('/parties', function () {
    return \App\Party::all();
});

Route::get('/candidates', function () {
    return \App\Candidate::all();
});

Route::get('/candidates/candidate', function (Request $request) {
    return \App\Candidate::where('name', strtolower($request->query('name')))->with(['party', 'constituency', 'endorsements'])->firstOrFail();
});

Route::get('/constituencies', function () {
    return \App\Constituency::all()->with(['candidates', 'incumbentParty']);
});

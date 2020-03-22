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
//Public views

use App\User;

Route::get('/', 'PageController@index')->name('index');
Route::get('/stateoftheparties', 'PageController@stateOfTheParties')->name('stateoftheparties');
Route::get('/constituencies', 'PageController@constituencies')->name('constituencies');
Route::get('/constituencies/{code}', 'PageController@constituencyView')->name('constituencies.view');
Route::get('/constituencies/{code}/stream', 'PageController@constituencyViewStream')->name('constituencies.view.stream');
Route::view('/coalitionmaker', 'coalitionmaker')->name('coalitionmaker');
Route::get('/candidates', 'PageController@candidates')->name('candidates');

//Admin
Route::prefix('admin')->group(function () {
    //Index
    Route::get('/', 'AdminController@index')->name('admin.index')->middleware('admin');
    //Parties
    Route::get('/party/{code}', 'AdminController@partyView')->name('admin.party')->middleware('admin');
    Route::post('/party/{code}/updateseats', 'AdminController@updatePartySeats')->name('admin.party.updateseats')->middleware('admin');
    //Constituencies
    Route::get('/constituency/{code}', 'AdminController@constituencyView')->name('admin.constituency')->middleware('admin');
    Route::post('/constituency/{code}/updatestats', 'AdminController@updateConstituencyStats')->name('admin.constituency.updatestats')->middleware('admin');
    Route::get('/constituency/{code}/publish', 'AdminController@publishConstituency')->name('admin.constituency.publish')->middleware('admin');
    //Candidates
    Route::post('/candidates/{id}/updatevotes', 'AdminController@updateCandidateVotes')->name('admin.candidate.updatevotes')->middleware('admin');
    Route::post('/constituency/{code}/addsix', 'AdminController@addSixCandidates')->name('admin.candidate.addsix')->middleware('admin');
    Route::post('/constituency/{code}/addendorsement', 'AdminController@addEndorsement')->name('admin.candidate.addendorsement')->middleware('admin');
    Route::get('/candidates/{id}/delete', 'AdminController@deleteCandidate')->name('admin.candidate.delete')->middleware('admin');
    //Admin auth stuff
    Route::get('auth/login', 'AdminController@redditLogin')->name('admin.auth.login');
    Route::get('auth/callback', 'AdminController@redditCallback')->name('admin.auth.callback');
    //Temporary
    Route::get('auth', function() {
        Auth::login(User::find(1));
    });
    //Load results
    Route::post('/loadresults', 'AdminController@loadResultsFromFile')->name('admin.loadresults');
    Route::post('/loadfacesteals', 'AdminController@loadFacestealsFromFile')->name('admin.loadfacesteals');
});

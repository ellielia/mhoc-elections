<?php

namespace App\Http\Controllers;

use App\Candidate;
use App\Constituency;
use App\Party;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        //Get constituencies
        $declaredseats = Constituency::where('published', true)->get()->sortByDesc('published_at');
        $sotpLabels = array();
        $sotpColours = array();
        $sotpSeats = array();
        $parties = Party::all();
        foreach ($parties as $p) {
            array_push($sotpLabels, $p->code);
            array_push($sotpColours, $p->hex);
            $seatcount = 0;
            foreach ($declaredseats as $s) {
                if ($s->winner()->party->id == $p->id) {
                    $seatcount++;
                }
            }
            $seatcount = $seatcount + $p->list_seats;
            array_push($sotpSeats, $seatcount);
        }
        return view('index', compact('declaredseats', 'sotpColours', 'sotpLabels', 'sotpSeats'));
    }

    public function stateoftheparties()
    {
        $parties = Party::all();
        $declaredseats = Constituency::where('published', true)->get()->sortByDesc('published_at');
        $sotpLabels = array();
        $sotpColours = array();
        $sotpSeats = array();
        $sotpVotes = array();
        foreach ($parties as $p) {
            array_push($sotpLabels, $p->code);
            array_push($sotpColours, $p->hex);
            $seatcount = 0;
            foreach ($declaredseats as $s) {
                if ($s->winner()->party->id == $p->id) {
                    $seatcount++;
                }
            }
            $seatcount = $seatcount + $p->list_seats;
            array_push($sotpSeats, $seatcount);
            $votecount = $p->list_votes;
            foreach($p->constituencySeats() as $s) {
                $votecount += $s->winner()->constituency_votes;
            }
            array_push($sotpVotes, $votecount);
        }
        return view('stateoftheparties', compact('parties', 'declaredseats', 'sotpColours', 'sotpLabels', 'sotpSeats', 'sotpVotes'));
    }

    public function constituencies()
    {
        $constituencies = Constituency::all()->sortBy('name');
        return view('constituencies', compact('constituencies'));
    }

    public function constituencyView($code)
    {
        $constituency = Constituency::where('code', $code)->firstOrFail();
        return view('constituency', compact('constituency'));
    }

    public function constituencyViewStream($code)
    {
        $constituency = Constituency::where('code', $code)->firstOrFail();
        return view('streamconstituency', compact('constituency'));
    }

    public function candidates()
    {
        $candidates = Candidate::all()->sortby('name');
        return view ('candidates', compact('candidates'));
    }
}

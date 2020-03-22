<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    public function seats()
    {
        $declaredseats = Constituency::where('published', true)->get()->sortByDesc('published_at');
        $seatcount = 0;
        foreach ($declaredseats as $s) {
            if ($s->winner()->party->id == $this->id) {
                $seatcount++;
            }
        }
        return $this->list_seats + $seatcount;
    }

    public function constituencySeatCount()
    {
        $declaredseats = Constituency::where('published', true)->get()->sortByDesc('published_at');
        $seatcount = 0;
        foreach ($declaredseats as $s) {
            if ($s->winner()->party->id == $this->id) {
                $seatcount++;
            }
        }
        return $seatcount;
    }

    public function constituencySeats()
    {
        $declaredseats = Constituency::where('published', true)->get()->sortByDesc('published_at');
        $seats = array();
        foreach ($declaredseats as $s) {
            if ($s->winner()->party->id == $this->id) {
                array_push($seats, $s);
            }
        }
        return $seats;
    }
}

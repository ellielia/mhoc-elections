<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Constituency extends Model
{
    public function incumbentParty()
    {
        return $this->hasOne('App\Party');
    }

    public function candidates()
    {
        return $this->hasMany('App\Candidate');
    }

    public function winner()
    {
        return $this->candidates->sortByDesc('constituency_votes')->first();
    }

    public function runnerUp()
    {
        return $this->candidates->sortByDesc('constituency_votes')->slice(1)->first();
    }

    public function totalVotes()
    {
        $candidates = $this->candidates;
        $number = 0;
        foreach ($candidates as $c) {
            $number += $c->constituency_votes;
        }
        return $number;
    }

    public function declaredAtPretty()
    {
        return Carbon::create($this->declared_at)->toDateTimeString();
    }

    public function declaredAtHuman()
    {
        return Carbon::create($this->declared_at)->diffForHumans();
    }
}

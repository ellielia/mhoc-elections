<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    public function constituency()
    {
        return $this->belongsTo('App\Constituency');
    }

    public function party()
    {
        return $this->belongsTo('App\Party');
    }

    public function endorsements()
    {
        return $this->hasMany('App\Endorsement');
    }
}

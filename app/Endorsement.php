<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Endorsement extends Model
{
    public function party()
    {
        return $this->belongsTo('App\Party');
    }
}

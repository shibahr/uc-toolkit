<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Itl extends Model
{
    public function Phone()
    {
        return $this->belongsTo('App\Phone');
    }
}

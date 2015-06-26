<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    public function Itl()
    {
        return $this->hasMany('App\Itl');
    }
}

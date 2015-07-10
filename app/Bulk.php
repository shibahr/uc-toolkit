<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bulk extends Model
{
    public function erasers()
    {
        return $this->belongsToMany('App\Eraser');
    }
}

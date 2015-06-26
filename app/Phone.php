<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['mac', 'description'];

    public function Itl()
    {
        return $this->hasMany('App\Itl');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Itl extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['phone_id', 'ip_address','result'];

    public function Phone()
    {
        return $this->belongsTo('App\Phone');
    }
}

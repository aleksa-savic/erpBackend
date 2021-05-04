<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    protected $fillable = [
        'client_id',
        'driver_id',
        'end_loc_latitude',
        'end_loc_longitude',
        'start_loc_latitude',
        'start_loc_longitude',
    
    ];
    
    
    protected $dates = [
        'created_at',
        'updated_at',
    
    ];
    
    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/rides/'.$this->getKey());
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'list_name', 'address', 'longitude', 'latitude', 'submitter_id'
    ];
  
    protected $table = 'listing';

    const DEGREE_TO_KM = 111.13384;

    public function submitter()
    {
    	return $this->belongsTo('App\User','submitter_id','id');
    }

    public function scopeCalculateDistance($query,$latitude,$longitude)
    {
    	$distance = "ROUND(DEGREES(ACOS(COS(RADIANS($latitude))
	                * COS(RADIANS(latitude))
	                * COS(RADIANS($longitude - longitude))
	                + SIN(RADIANS($latitude))
	                * SIN(RADIANS(latitude)))*111.13384),3)";

     	return $query->select('id','list_name')->selectRaw("{$distance} AS distance");
    }

}

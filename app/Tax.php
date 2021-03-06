<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model {

	//Define the one to many relationship with project
	public function project()
	{
		return $this->belongsTo('App\Project');
	}

}

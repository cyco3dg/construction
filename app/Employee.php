<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model {

	//Define the one to many relationship with projects
	public function projects()
	{
		return $this->belongsToMany('App\Project');
	}
}

<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
// Route::controllers([
// 	'auth' => 'Auth\AuthController',
// 	'password' => 'Auth\PasswordController',
// ]);


//Retrieve Login Page and Post Login 
Route::get('/login', [
	'uses'=>'Auth\AuthController@getLogin',
	'as'=>'getLogin'
]);
Route::post('/login', [
	'uses'=>'Auth\AuthController@authenticate',
	'as'=>'postLogin'
]);


//Authenticated Routes
Route::group(['middleware' => 'auth'], function() {
    //
    Route::get('dashboard',[
		'as'=>'dashboard',
		function(){
			return view('home',['active'=>'home']);
		}
	]);
	Route::get('logout',[
		'uses'=>'Auth\AuthController@getLogout',
		'as'=>'logout'
	]);
 
	//organization manipulations
	Route::group(['prefix' => 'organization'], function() {
	  
	    //Add Organization to the db
	    Route::get('add',[
	    	'uses'=>'OrganizationController@create',
	    	'as'=>'addorganization'
	    ]);
	    Route::post('add',[
	    	'uses'=>'OrganizationController@store',
	    	'as'=>'addorganization'
	    ]);

	    //Update Organization in the db
	    Route::put('update/{id}',[
	    	'uses'=>'OrganizationController@update',
	    	'as'=>'updateorganization'
	    ])->where('id', '[0-9]+');
	    Route::get('update/{id}',[
	    	'uses'=>'OrganizationController@edit',
	    	'as'=>'updateorganization'
	    ])->where('id', '[0-9]+');

	    //Delete Organization from the db
	    Route::delete('delete/{id}',[
	    	'uses'=>'OrganizationController@destroy',
	    	'as'=>'deleteorganization'
	    ])->where('id', '[0-9]+');

	    //Show All Organizations
	    Route::get('all',[
	    	'uses'=>'OrganizationController@index',
	    	'as'=>'allorganization'
	    ]);

	    //Show All clients
	    Route::get('all/clients',[
	    	'uses'=>'OrganizationController@getClients',
	    	'as'=>'allclients'
	    ]);

	    //Show All contract clients
	    Route::get('all/contractor',[
	    	'uses'=>'OrganizationController@getContractClients',
	    	'as'=>'allcontractclients'
	    ]);


	    //find by id
	    Route::get('/{id}',[
	    	'uses'=>'OrganizationController@show',
	    	'as'=>'showorganization'
	    ])->where('id', '[0-9]+');
	});	

	//Project Manipulations	
	Route::group(['prefix' => 'project'], function() {
	  
	    //Add Project to the db
	    Route::get('add/{id?}',[
	    	'uses'=>'ProjectController@create',
	    	'as'=>'addproject'
	    ]);
	    Route::post('add',[
	    	'uses'=>'ProjectController@store',
	    	'as'=>'addproject'
	    ]);

	    //Update Project in the db
	    Route::put('update/{id}',[
	    	'uses'=>'ProjectController@update',
	    	'as'=>'updateproject'
	    ])->where('id', '[0-9]+');
	    Route::get('update/{id}',[
	    	'uses'=>'ProjectController@edit',
	    	'as'=>'updateproject'
	    ])->where('id', '[0-9]+');

	    //Delete Project from the db
	    Route::delete('delete/{id}',[
	    	'uses'=>'ProjectController@destroy',
	    	'as'=>'deleteproject'
	    ])->where('id', '[0-9]+');

	    //Show All Projects
	    Route::get('all',[
	    	'uses'=>'ProjectController@index',
	    	'as'=>'allproject'
	    ]);
	    //Show allstartedproject
	    Route::get('current',[
	    	'uses'=>'ProjectController@allStarted',
	    	'as'=>'allstartedproject'
	    ]);
	    
	    //Show allstartedproject
	    Route::get('done',[
	    	'uses'=>'ProjectController@allDone',
	    	'as'=>'alldoneproject'
	   	]);

	    //Show allstartedproject
	    Route::get('notstarted',[
	    	'uses'=>'ProjectController@allNotStarted',
	    	'as'=>'allnotstartedproject'
	   	]);

	   	//start project
	    Route::put('start/{id}',[
	    	'uses'=>'ProjectController@startProject',
	    	'as'=>'startproject'
	   	])->where('id','[0-9]+');

	   	//start project
	    Route::put('end/{id}',[
	    	'uses'=>'ProjectController@endProject',
	    	'as'=>'endproject'
	   	])->where('id','[0-9]+');

	    //find by id
	    Route::get('/{id}',[
	    	'uses'=>'ProjectController@show',
	    	'as'=>'showproject'
	    ])->where('id', '[0-9]+');

	    //Non-Organization Payment

	    Route::get('addNonOrg',[
	    	'uses'=>'ProjectController@checkNonOrgCost',
	    	'as'=>'nonorg'
    	]);

    	Route::put('add/non_org_cost/{id}',[
	    	'uses'=>'ProjectController@addNonOrgCost',
	    	'as'=>'addnonorg'
    	])->where('id','[0-9]+');
	});	

	//User Manipulations	
	Route::group(['prefix' => 'user'], function() {
	  
	    //Add User to the db
	    Route::get('add/{id?}',[
	    	'uses'=>'UserController@create',
	    	'as'=>'adduser'
	    ])->where('id','[0-9]+');

	    Route::post('add',[
	    	'uses'=>'UserController@store',
	    	'as'=>'adduser'
	    ]);

	    //Update User in the db
	    Route::put('update/{id}',[
	    	'uses'=>'UserController@update',
	    	'as'=>'updateuser'
	    ])->where('id', '[0-9]+');
	    
	    Route::get('update/{id}',[
	    	'uses'=>'UserController@edit',
	    	'as'=>'updateuser'
	    ])->where('id', '[0-9]+');

	    //Delete User from the db
	    Route::delete('delete/{id}',[
	    	'uses'=>'UserController@destroy',
	    	'as'=>'deleteuser'
	    ])->where('id', '[0-9]+');

	    //Show All Users
	    Route::get('all',[
	    	'uses'=>'UserController@index',
	    	'as'=>'alluser'
	    ]);

	    //Show All Users
	    Route::get('all/contractors',[
	    	'uses'=>'UserController@contractor',
	    	'as'=>'allcontractor'
	    ]);

	    //Show All Admin Users
	    Route::get('all/admins',[
	    	'uses'=>'UserController@admin',
	    	'as'=>'alladmin'
	    ]);

	    //find User by id
	    Route::get('/{id}',[
	    	'uses'=>'UserController@show',
	    	'as'=>'showuser'
	    ])->where('id', '[0-9]+');
	});	
	
	//Term Manipulations
	Route::group(['prefix' => 'term'], function() {
	  
	    //Add Term to the db
	    Route::get('add/{id?}',[
	    	'uses'=>'TermController@create',
	    	'as'=>'addterm'
	    ])->where('id','[0-9]+');
	    Route::post('add',[
	    	'uses'=>'TermController@store',
	    	'as'=>'addterm'
	    ]);

	    //Update Term in the db
	    Route::put('update/{id}',[
	    	'uses'=>'TermController@update',
	    	'as'=>'updateterm'
	    ])->where('id', '[0-9]+');

	    Route::get('update/{id}',[
	    	'uses'=>'TermController@edit',
	    	'as'=>'updateterm'
	    ])->where('id', '[0-9]+');

	    //Delete Term from the db
	    Route::delete('delete/{id}',[
	    	'uses'=>'TermController@destroy',
	    	'as'=>'deleteterm'
	    ])->where('id', '[0-9]+');

	    //Show All Terms
	    Route::get('all/{id?}',[
	    	'uses'=>'TermController@index',
	    	'as'=>'allterm'
	    ])->where('id','[0-9]+');

	    //find Term by id
	    Route::get('/{id}',[
	    	'uses'=>'TermController@show',
	    	'as'=>'showterm'
	    ])->where('id', '[0-9]+');

	   	//Start Term by id
	    Route::get('start/{id}',[
	    	'uses'=>'TermController@getStart',
	    	'as'=>'startterm'
	    ])->where('id', '[0-9]+');

	    //contract term
	    Route::get('contract/{id}',[
	    	'uses'=>'TermController@getTermContract',
	    	'as'=>'termcontract'
	    ])->where('id','[0-9]+');

	    Route::put('contract/{id}',[
	    	'uses'=>'TermController@postTermContract',
	    	'as'=>'termcontract'
	    ])->where('id','[0-9]+');

	    //Add Term Type
	    Route::get('type',[
	    	'uses'=>'TermTypeController@create',
	    	'as'=>'addtermtype'
	    ]);
	    Route::post('type',[
	    	'uses'=>'TermTypeController@store',
	    	'as'=>'addtermtype'
	    ]);
	    //Delete Term Type
	    Route::delete('type/delete/{id}',[
	    	'uses'=>'TermTypeController@destroy',
	    	'as'=>'deletetermtype'
	    ])->where('id','[0-9]+');
	    //show all Term Types
	    Route::get('type/all',[
	    	'uses'=>'TermTypeController@index',
	    	'as'=>'alltermtype'
	    ]); 
	});

	//Contractors Manipulations
	Route::group(['prefix' => 'contractor'], function() {
	    //Add Contractor
	    Route::get('add',[
	    	'uses'=>'ContractorController@create',
	    	'as'=>'addcontractor'
	    ]);
	    Route::post('add',[
	    	'uses'=>'ContractorController@store',
	    	'as'=>'addcontractor'
	    ]);
	    //update Contractor
	    Route::get('update/{id}',[
	    	'uses'=>'ContractorController@edit',
	    	'as'=>'updatecontractor'
	    ])->where('id','[0-9]+');
	    Route::put('update/{id}',[
	    	'uses'=>'ContractorController@update',
	    	'as'=>'updatecontractor'
	    ])->where('id','[0-9]+');

	    //Delete Contractor
	    Route::delete('delete/{id}',[
	    	'uses'=>'ContractorController@create',
	    	'as'=>'deletecontractor'
	    ])->where('id','[0-9]+');

	    //find By ID
	    Route::get('show/{id}',[
	    	'uses'=>'ContractorController@show',
	    	'as'=>'showcontractor'
	    ])->where('id','[0-9]+');

	    //ALL CONTRACTORS
	    Route::get('all',[
	    	'uses'=>'ContractorController@index',
	    	'as'=>'allcontractor'
	    ]);


	    //ALL Raw Suppliers
	    Route::get('all/RawSuppliers',[
	    	'uses'=>'ContractorController@getRaw',
	    	'as'=>'allraw'
	    ]);

	    //ALL Labor Suppliers
	    Route::get('all/LaborSuppliers',[
	    	'uses'=>'ContractorController@getLabor',
	    	'as'=>'alllabor'
	    ]);

	    //ALL CONTRACTED TERMS
	    Route::get('{id}/all/terms',[
	    	'uses'=>'ContractorController@getAllTerms',
	    	'as'=>'ContractedTerms'
	    ])->where('id','[0-9]+');

	    //ALL CONTRACTED STORES
	    Route::get('{id}/all/stores',[
	    	'uses'=>'ContractorController@getAllStores',
	    	'as'=>'ContractedStores'
	    ])->where('id','[0-9]+');
	});

	//Store Manipulations
	Route::group(['prefix' => 'store'], function() {
	    //add store
		Route::get('add/{cid?}/{pid?}',[
			'uses'=>'StoreController@create',
			'as'=>'addstore'
		])->where(['cid'=>'[0-9]+','pid'=>'[0-9]+']);

		Route::post('add',[
			'uses'=>'StoreController@store',
			'as'=>'addstore'
		]);

		//all stores for a project
		Route::get('all/{id}',[
			'uses'=>'StoreController@index',
			'as'=>'allstores'
		])->where('id','[0-9]+');

		Route::get('all',[
			'uses'=>'StoreController@findStore',
			'as'=>'findstores'
		]);

		Route::post('all/',[
			'uses'=>'StoreController@getAll',
			'as'=>'allstores'
		]);

		//show store
		Route::get('show/{type}',[
			'uses'=>'StoreController@show',
			'as'=>'showstore'
		]);
	});
});



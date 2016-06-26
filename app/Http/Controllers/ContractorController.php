<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Contractor;
use App\TermType;
use Validator;
use Auth;
use Carbon\Carbon;

class ContractorController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if(Auth::user()->type=='admin'){
			$contractors=Contractor::all();
			$array=['active'=>'cont','contractors'=>$contractors];
			return view('contractor.all',$array);
		}
		else
			abort('404');
	}

	/**
	 * Display a listing of Raw Suppliers.
	 *
	 * @return Response
	 */
	public function getRaw()
	{
		if(Auth::user()->type=='admin'){
			$contractors=Contractor::where('role','raw')->orWhere('role','both')->get();
			$array=['active'=>'cont','contractors'=>$contractors];
			return view('contractor.all',$array);
		}
		else
			abort('404');
	}

	/**
	 * Display a listing of Labor Suppliers.
	 *
	 * @return Response
	 */
	public function getLabor()
	{
		if(Auth::user()->type=='admin'){
			$contractors=Contractor::where('role','labor')->orWhere('role','both')->get();
			$array=['active'=>'cont','contractors'=>$contractors];
			return view('contractor.all',$array);
		}
		else
			abort('404');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		if(Auth::user()->type=='admin'){
			$term_types=TermType::all();
			$array=['active'=>'cont','term_types'=>$term_types];
			return view('contractor.add',$array);
		}else{
			abort('404');
		}	
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $req)
	{
		if(Auth::user()->type=='admin'){
			//Validation Rules
			$rules=[
				'name'=>'required|regex:/^[\pL\s]+$/u',
				'type'=>'required|exists:term_types,name',
				'address'=>'regex:/^[\pL\pN\s]+$/u',
				'center'=>'regex:/^[\pL\pN\s]+$/u',
				'city'=>'required|regex:/^[\pL\pN\s]+$/u',
				'phone'=>'required|numeric',
				'role'=>'required|in:both,raw,labor'
			];
			//Error Vaildation Messages
			$error_messages=[
				'name.required'=>'يجب أدخال أسم المقاول',
				'name.regex'=>'أسم المقاول يجب أن يتكون من حروف و مسافات فقط',
				'type.required'=>'يجب أدخال نوع المقاول',
				'type.exists'=>'أنواع المقاول يجب أن تكون مسجلة بقاعدة البيانات فى أنواع البنود',
				'address.regex'=>'الشارع يجب أن يتكون من حروف و أرقام و مسافات فقط',
				'center.regex'=>'المركز يجب أن يتكون من حروف و أرقام و مسافات فقط',
				'city.required'=>'يجب أدخال المدينة',
				'city.regex'=>'المدينة يجب أن تتكون من حروف و أرقام و مسافات فقط',
				'phone.required'=>'يجب أدخال التليفون',
				'phone.numeric'=>'التليفون يجب أن يتكون من أرقام فقط',
				'role.required'=>'يجب أختيار دور المقاول',
				'role.in'=>'دور المقاول يجب أن يكون من الأختيارات المتاحة فقط'
			];
			//validate the request
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails()){
				return redirect()->back()->withErrors($validator)->withInput();
			}
			
			//STORE CONTRACTOR IN DB
			$contractor=new Contractor;
			$contractor->name=$req->input('name');
			$contractor->address=$req->input('address');
			$contractor->center=$req->input('center');
			$contractor->city=$req->input('city');
			$contractor->phone=$req->input('phone');
			$contractor->role=$req->input('role');
			$contractor->type="";
			foreach ($req->input('type') as $type) {
				$contractor->type.=$type." , ";
			}
			$contractor->type=substr($contractor->type, 0,-1);
			$saved=$contractor->save();
			if(!$saved){
				return redirect()->back()->with('insert_error','حدث خطأ خلال أضافة هذا المقاول و يرجى المحاولة فى وقت لاحق');
			}
			return redirect()->route('showcontractor',$contractor->id)->with('success','تم حفظ المقاول بنجاح');
		}else{
			abort('404');
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		if(Auth::user()->type=='admin'){
			$contractor=Contractor::findOrFail($id);
			if($contractor->role=='both')
			{
				$terms=$contractor->terms()->where('started_at','<=',Carbon::today())->orderBy('started_at','desc')->take(3)->get();
				$stores=$contractor->stores()->orderBy('created_at','desc')->take(3)->get();
				$array=['active'=>'cont','contractor'=>$contractor,'terms'=>$terms,'stores'=>$stores];
			}
			elseif ($contractor->role=='labor') 
			{
				$terms=$contractor->terms()->where('started_at','<=',Carbon::today())->orderBy('started_at','desc')->take(3)->get();
				$array=['active'=>'cont','contractor'=>$contractor,'terms'=>$terms];
			}
			elseif ($contractor->role=='raw') 
			{
				$stores=$contractor->stores()->orderBy('created_at','desc')->take(3)->get();
				$array=['active'=>'cont','contractor'=>$contractor,'stores'=>$stores];
			}
			return view('contractor.show',$array);
		}
		else
			abort('404');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		if(Auth::user()->type=='admin'){
			$contractor=Contractor::findOrFail($id);
			$term_types=TermType::all();
			$array=['active'=>'cont','contractor'=>$contractor,'term_types'=>$term_types];
			return view('contractor.edit',$array);
		}else{
			abort('404');
		}	
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $req,$id)
	{
		if(Auth::user()->type=='admin'){
			//Validation Rules
			$rules=[
				'name'=>'required|regex:/^[\pL\s]+$/u',
				'type'=>'required|regex:/^[\pL\pN\s]+$/u',
				'address'=>'regex:/^[\pL\pN\s]+$/u',
				'center'=>'regex:/^[\pL\pN\s]+$/u',
				'city'=>'required|regex:/^[\pL\pN\s]+$/u',
				'phone'=>'required|numeric',
				'role'=>'required|in:both,raw,labor'
			];
			//Error Vaildation Messages
			$error_messages=[
				'name.required'=>'يجب أدخال أسم المقاول',
				'name.regex'=>'أسم المقاول يجب أن يتكون من حروف و مسافات فقط',
				'type.required'=>'يجب أدخال نوع المقاول',
				'type.regex'=>'نوع المقاول يجب أن يتكون من حروف و أرقام و مسافات فقط',
				'address.regex'=>'الشارع يجب أن يتكون من حروف و أرقام و مسافات فقط',
				'center.regex'=>'المركز يجب أن يتكون من حروف و أرقام و مسافات فقط',
				'city.required'=>'يجب أدخال المدينة',
				'city.regex'=>'المدينة يجب أن تتكون من حروف و أرقام و مسافات فقط',
				'phone.required'=>'يجب أدخال التليفون',
				'phone.numeric'=>'التليفون يجب أن يتكون من أرقام فقط',
				'role.required'=>'يجب أختيار دور المقاول',
				'role.in'=>'دور المقاول يجب أن يكون من الأختيارات المتاحة فقط'
			];
			//validate the request
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails()){
				return redirect()->back()->withErrors($validator)->withInput();
			}
			
			//STORE CONTRACTOR IN DB
			$contractor=Contractor::findOrFail($id);
			$contractor->name=$req->input('name');
			$contractor->type=$req->input('type');
			$contractor->address=$req->input('address');
			$contractor->center=$req->input('center');
			$contractor->city=$req->input('city');
			$contractor->phone=$req->input('phone');
			$contractor->role=$req->input('role');

			$saved=$contractor->save();
			if(!$saved){
				return redirect()->back()->with('update_error','حدث خطأ خلال تعديل هذا المقاول و يرجى المحاولة فى وقت لاحق');
			}
			return redirect()->route('showcontractor',$contractor->id)->with('success','تم تعديل المقاول بنجاح');
		}else{
			abort('404');
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if(Auth::user()->type=='admin'){
			$contractor=Contractor::findOrFail($id);
			$deleted=$contractor->delete();
			if(!$deleted){
				return redirect()->back()->with('delete_error','حدث عطل خلال حذف هذا المقاول يرجى المحاولة فى وقت لاحق');
			}
			return redirect()->route('allcontractor')->with('success','تم حذف المقاول بنجاح');
		}else{
			abort('404');
		}	
	}

	/**
	 * Get All Contracted Terms 
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getAllTerms($id)
	{
		if(Auth::user()->type=='admin')
		{
			$contractor=Contractor::findOrFail($id);
			$terms=$contractor->terms;
			$array=['active'=>'cont','contractor'=>$contractor,'terms'=>$terms];
			return view('contractor.terms',$array);
		}
		else
			abort('404');
	}

	/**
	 * Get all imported items from this contractor
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getAllStores($id)
	{
		if(Auth::user()->type=='admin')
		{
			$contractor=Contractor::findOrFail($id);
			$stores=$contractor->stores;
			$array=['active'=>'cont','contractor'=>$contractor,'stores'=>$stores];
			return view('contractor.stores',$array);
		}
		else
			abort('404');
	}
}

<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Supplier;
use App\StoreType;

use Carbon\Carbon;
use Validator;
use Auth;

class SupplierController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		if(Auth::user()->type=="admin"){
			$suppliers=Supplier::all();
			$array=['active'=>'sup','suppliers'=>$suppliers];
			return view('supplier.all',$array);
		}else
			abort('404');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
		if(Auth::user()->type=="admin"){
			$store_types=StoreType::all();
			$array=['active'=>'sup','store_types'=>$store_types];
			return view('supplier.add',$array);
		}else
			abort('404');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $req)
	{
		//
		if(Auth::user()->type=="admin"){
			//Validation Rules
			$rules=[
				'name'=>'required|regex:/^[\pL\s]+$/u',
				'type'=>'required|exists:store_types,name',
				'address'=>'regex:/^[\pL\pN\s]+$/u',
				'center'=>'regex:/^[\pL\pN\s]+$/u',
				'city'=>'required|regex:/^[\pL\pN\s]+$/u',
				'phone'=>'required|numeric',
			];
			//Error Vaildation Messages
			$error_messages=[
				'name.required'=>'يجب أدخال أسم المورد',
				'name.regex'=>'أسم المورد يجب أن يتكون من حروف و مسافات فقط',
				'type.required'=>'يجب أدخال نوع المورد',
				'type.exists'=>'أنواع المورد يجب أن تكون مسجلة بقاعدة البيانات فى أنواع البنود',
				'address.regex'=>'الشارع يجب أن يتكون من حروف و أرقام و مسافات فقط',
				'center.regex'=>'المركز يجب أن يتكون من حروف و أرقام و مسافات فقط',
				'city.required'=>'يجب أدخال المدينة',
				'city.regex'=>'المدينة يجب أن تتكون من حروف و أرقام و مسافات فقط',
				'phone.required'=>'يجب أدخال التليفون',
				'phone.numeric'=>'التليفون يجب أن يتكون من أرقام فقط'
			];
			//validate the request
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails()){
				return redirect()->back()->withErrors($validator)->withInput();
			}

			$supplier=new Supplier;
			$supplier->name=$req->input('name');
			$supplier->address=$req->input('address');
			$supplier->center=$req->input('center');
			$supplier->city=$req->input('city');
			$supplier->phone=$req->input('phone');
			$supplier->type="";
			foreach ($req->input('type') as $type) {
				$supplier->type.=$type.',';
			}
			$supplier->type=substr($supplier->type,0,-1);
			$saved=$supplier->save();
			if(!$saved)
				return redirect()->back()->with('insert_error','حدث عطل خلال أضافة هذا المورد, يرجى المحاولة فى وقت لاحق');
			return redirect()->route('showsupplier',$supplier->id)->with('success','تم أضافة المورد بنجاح');
		}else
			abort('404');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
		if(Auth::user()->type=="admin"){
			$supplier=Supplier::findOrFail($id);
			$stores= $supplier->stores()->orderBy('created_at','desc')->take(3)->get();
			$array=[
				'active'=>'sup',
				'supplier'=>$supplier,
				'stores'=>$stores
			];
			return view('supplier.show',$array);
		}else
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
		//
		if(Auth::user()->type=="admin"){
			$supplier=Supplier::findOrFail($id);
			$store_types=StoreType::all();
			$supplier_types=explode(",", $supplier->type);
			$array=[
				'active'=>'sup',
				'store_types'=>$store_types,
				'supplier'=>$supplier,
				'supplier_types'=>$supplier_types
			];
			return view('supplier.edit',$array);
		}else
			abort('404');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $req,$id)
	{
		//
		if(Auth::user()->type=="admin"){
			//Validation Rules
			$rules=[
				'name'=>'required|regex:/^[\pL\s]+$/u',
				'type'=>'required|exists:store_types,name',
				'address'=>'regex:/^[\pL\pN\s]+$/u',
				'center'=>'regex:/^[\pL\pN\s]+$/u',
				'city'=>'required|regex:/^[\pL\pN\s]+$/u',
				'phone'=>'required|numeric',
			];
			//Error Vaildation Messages
			$error_messages=[
				'name.required'=>'يجب أدخال أسم المورد',
				'name.regex'=>'أسم المورد يجب أن يتكون من حروف و مسافات فقط',
				'type.required'=>'يجب أدخال نوع المورد',
				'type.exists'=>'أنواع المورد يجب أن تكون مسجلة بقاعدة البيانات فى أنواع البنود',
				'address.regex'=>'الشارع يجب أن يتكون من حروف و أرقام و مسافات فقط',
				'center.regex'=>'المركز يجب أن يتكون من حروف و أرقام و مسافات فقط',
				'city.required'=>'يجب أدخال المدينة',
				'city.regex'=>'المدينة يجب أن تتكون من حروف و أرقام و مسافات فقط',
				'phone.required'=>'يجب أدخال التليفون',
				'phone.numeric'=>'التليفون يجب أن يتكون من أرقام فقط'
			];
			//validate the request
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails()){
				return redirect()->back()->withErrors($validator)->withInput();
			}

			$supplier= Supplier::findOrFail($id);
			$supplier->name=$req->input('name');
			$supplier->address=$req->input('address');
			$supplier->center=$req->input('center');
			$supplier->city=$req->input('city');
			$supplier->phone=$req->input('phone');
			$supplier->type="";
			foreach ($req->input('type') as $type) {
				$supplier->type.=$type.',';
			}
			$supplier->type=substr($supplier->type,0,-1);
			$saved=$supplier->save();
			if(!$saved)
				return redirect()->back()->with('update_error','حدث عطل خلال تعديل هذا المورد, يرجى المحاولة فى وقت لاحق');
			return redirect()->route('showsupplier',$supplier->id)->with('success','تم تعديل المورد بنجاح');
		}else
			abort('404');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
		if(Auth::user()->type=="admin"){
			$supplier=Supplier::findOrFail($id);
			$deleted=$supplier->delete();
			if(!$deleted){
				return redirect()->route('allsupplier')->with('delete_error','حدث عطل خلال حذف هذا المورد يرجى المحاولة فى وقت لاحق');
			}
			return redirect()->route('allsupplier')->with('success','تم حذف المورد ('.$supplier->name.') بنجاح');
		}else
			abort('404');
	}

}

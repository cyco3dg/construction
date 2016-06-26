<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Store;
use App\Project;
use App\Contractor;

use Auth;
use DB;
use Validator;

class StoreController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($id)
	{
		if(Auth::user()->type=='admin'){
			$project=Project::findOrFail($id);
			$consumptions=$project->consumptions()
					->selectRaw('consumptions.type as type, sum(consumptions.amount) as amount')
					->groupBy('type')
					->get();
			$stores=Store::where('project_id',$id)
					->selectRaw('type, sum(amount) as amount , unit,sum(amount_paid) as amount_paid')
					->groupBy('type')
					->get();
			$projects=Project::where('done','=',0)->orderBy('name')->get();
			$array=['active'=>'store','stores'=>$stores,'project'=>$project,'consumptions'=>$consumptions,'projects'=>$projects];
			return view('store.all',$array);
		}		
		else
			abort('404');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function findStore()
	{
		if(Auth::user()->type=='admin'){
			$projects=Project::where('done','=',0)->orderBy('name')->get();
			$array=['active'=>'store','projects'=>$projects];
			return view('store.all',$array);
		}		
		else
			abort('404');	
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getAll(Request $req)
	{
		if(Auth::user()->type=='admin'){
			$rule=[
				'project_id'=>'required|exists:projects,id'
			];
			$message=[
				'project_id.required'=>'يجب أختيار مشروع',
				'project_id.exists'=>'من فضلك أختار مشروع صحيح'
			];
			$validator=Validator::make($req->all(),$rule,$message);
			if($validator->fails()){
				return redirect()->back()->withErrors($validator)->withInput();
			}
			return redirect('store/all/'.$req->input('project_id'));
		}		
		else
			abort('404');	
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($cid=null,$pid=null)
	{
		if(Auth::user()->type=='admin'){
			if($cid!=null && $cid!=0){
				$contractors=Contractor::where('id',$cid)->whereIn('role',['raw','both'])->get();
			}else{
				$contractors=Contractor::whereIn('role',['both','raw'])->get();
			}
			if($pid!=null){
				$projects=Project::where('id',$pid)->where('done',0)->get();
			}else{
				$projects=Project::where('done',0)->get();
			}
			$array=['contractors'=>$contractors,'projects'=>$projects,'active'=>'store'];
			return view('store.add',$array);
		}		
		else
			abort('404');	
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $req)
	{
		if(Auth::user()->type=='admin'){
			$rules=[
				'project_id'=>'required|exists:projects,id',
				'contractor_id'=>'required|exists:contractors,id',
				'type'=>'required',
				'amount'=>'required|numeric',
				'unit'=>'required',
				'value'=>'required|numeric',
				'amount_paid'=>'required|numeric'
			];
			$error_messages=[
				'project_id.required'=>'يجب أختيار مشروع',
				'project_id.exists'=>'المشروع يجب أن يكون موجود بقاعدة البيانات',
				'contractor_id.required'=>'يجب أختيار مقاول',
				'contractor_id.exists'=>'المقاول يجب أن يكون موجود بقاعدة البيانات',
				'type.required'=>'يجب أدخال نوع الخام',
				'amount.required'=>'يجب أدخال الكمية',
				'amount.numeric'=>'الكمية يجب أن تتكون من أرقام فقط',
				'unit.required'=>'يجب أدخال الوحدة',
				'value.numeric'=>'القيمة يجب أن تتكون من أرقام فقط',
				'value.required'=>'يجب أدخال القيمة',
				'amount_paid.numeric'=>'المبلغ المدفوع يجب أن يتكون من أرقام فقط',
				'amount_paid.required'=>'يجب أدخال المبلغ المدفوع'
			];
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails()){
				return redirect()->back()->withErrors($validator)->withInput();
			}
			$store=new Store;
			$store->type=$req->input('type');
			$store->value=$req->input('value');
			$store->amount=$req->input('amount');
			$store->amount_paid=$req->input('amount_paid');
			$store->project_id=$req->input('project_id');
			$store->contractor_id=$req->input('contractor_id');
			$store->unit=$req->input('unit');
			$saved=$store->save();
			if(!$saved){
				return redirect()->back()->with('insert_error','حدث عطل خلال أضافة هذه الكمية من الخامو يرجى المحاولة فى وقت لاحق');
			}
			return redirect()->back()->with('success','تم شراء الخام بنجاح و تم التخزين');
		}		
		else
			abort('404');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $type
	 * @return Response
	 */
	public function show($type)
	{
		if(Auth::user()->type=='admin'){
			$store=Store::where('type',$type)->firstOrFail();
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
			$store=Store::findOrFail($id);
			$array=['active'=>'store','store'=>$store];
			return view('store.edit',$array);
		}		
		else
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
		if(Auth::user()->type=='admin'){
			$rules=[
				'project_id'=>'required|exists:projects,id',
				'contractor_id'=>'required|exists:contractors,id',
				'type'=>'required',
				'amount'=>'required|numeric',
				'unit'=>'required',
				'value'=>'numeric',
				'amount_paid'=>'numeric'
			];
			$error_messages=[
				'project_id.required'=>'يجب أختيار مشروع',
				'project_id.exists'=>'المشروع يجب أن يكون موجود بقاعدة البيانات',
				'contractor_id.required'=>'يجب أختيار مقاول',
				'contractor_id.exists'=>'المقاول يجب أن يكون موجود بقاعدة البيانات',
				'type.required'=>'يجب أدخال نوع الخام',
				'amount.required'=>'يجب أدخال الكمية',
				'amount.numeric'=>'الكمية يجب أن تتكون من أرقام فقط',
				'unit.required'=>'يجب أدخال الوحدة',
				'value.numeric'=>'القيمة يجب أن تتكون من أرقام فقط',
				'amount_paid.numeric'=>'المبلغ المدفوع يجب أن يتكون من أرقام فقط'
			];
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails()){
				return redirect()->back()->withErrors($validator)->withInput();
			}
			$store=Store::findOrFail($id);
			$store->type=$req->input('type');
			$store->value=$req->input('value');
			$store->amount=$req->input('amount');
			$store->amount_paid=$req->input('amount_paid');
			$store->project_id=$req->input('project_id');
			$store->contractor_id=$req->input('contractor_id');
			$store->unit=$req->input('unit');
			$saved=$store->save();
			if(!$saved){
				return redirect()->back()->with('update_error','حدث عطل خلال أضافة هذه الكمية من الخامو يرجى المحاولة فى وقت لاحق');
			}
			return redirect()->back()->with('success','تم شراء الخام بنجاح و تم التخزين');
		}		
		else
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
		if(Auth::user()->type=='admin'){

		}		
		else
			abort('404');	
	}

}

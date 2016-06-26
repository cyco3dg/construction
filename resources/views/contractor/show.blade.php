@extends('layouts.master')
@section('title','بيانات المقاول')
@endsection
@section('content')
<div class="content">
<div class="row">
	<div class="col-md-8 col-lg-8 col-sm-8 col-sm-offset-2 col-md-offset-0 col-lg-offset-0">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>المقاول {{$contractor->name}} <span class="badge left">تقييم <br>
			<span class="glyphicon glyphicon-star star"></span>
			<span class="glyphicon glyphicon-star star"></span>
			<span class="glyphicon glyphicon-star star"></span>	
			<span class="glyphicon glyphicon-star star"></span>	
			<span class="glyphicon glyphicon-star star"></span>	
			10</span></h3>
		</div>
		<div class="panel-body">
			<div>
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active">
						<a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">
							بيانات المقاول
						</a>
					</li>
					<li role="presentation">
						<a href="#notes" aria-controls="notes" role="tab" data-toggle="tab">
							ملاحيظ
						</a>
					</li>
				</ul>
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane active" id="profile">
						<div class="table-responsive">
						<table class="table table-striped">
						<tr><th class="min100">نوع المقاول </th><td>{{$contractor->type}}</td></tr>
						@if(!empty($contractor->address))
						<tr><th class="min100">الشارع </th><td>{{$contractor->address}}</td></tr>
						@endif
						@if(!empty($contractor->center))
						<tr><th class="min100">المركز </th><td>{{$contractor->center}}</td></tr>
						@endif
						<tr><th class="min100">المدينة </th><td>{{$contractor->city}}</td></tr>
						<tr><th class="min100">التليفون </th><td>{{$contractor->phone}}</td></tr>
						<tr>
							<th class="min100">الدور </th>
							@if($contractor->role=='raw')
							<td>توريد خامات</td>
							@elseif($contractor->role=='labor')
							<td>توريد عمالة</td>
							@else
							<td>توريد عمالة و توريد خامات</td>
							@endif
						</tr>
						</table>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane" id="notes">
						
					</div>
				</div>
			</div>
			@if(!$contractor->user)
			<a href="{{ url('user/add',$contractor->id) }}" class="m-top btn btn-primary float">أنشاء حساب</a>
			@endif
			@if($contractor->role=='both'||$contractor->role=='labor')
			<a href="" class="m-top btn btn-primary float">أضافة معاملة مالية</a>
			<a href="" class="m-top btn btn-primary float">عقد بند</a>
			@endif
			@if($contractor->role=='both'||$contractor->role=='raw')
			<a href="" class="m-top btn btn-primary float">شراء خامات</a>
			@endif
			<a href="{{ route('updatecontractor',$contractor->id) }}" class="m-top btn btn-default float">تعديل</a>
			<form class="float" method="post" action="{{ route('deletecontractor',$contractor->id) }}">
				<button type="button" data-toggle="modal" data-target="#delete" class="btn m-top btn-danger">حذف</button>
				<div class="modal fade" id="delete" tabindex="-1" role="dialog">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title">هل تريد حذف المقاول {{$contractor->name}}</h4>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">لا
								</button>
								<button class="btn btn-danger">نعم</button>
							</div>
						</div>
					</div>					
				</div>
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<input type="hidden" name="_method" value="DELETE">
			</form>
		</div>
	</div>
	</div>
	<div class="col-md-4 col-lg-4 col-sm-4">
	@if(isset($terms))
	<div class="panel panel-default">
		<div class="panel-heading navy-heading">
			<h3>البنود المتعاقد عليها</h3>
		</div>
		<div class="panel-body">
			@if(count($terms)>0)
			@foreach($terms as $term)
				<div class="bordered-right border-navy" style="padding:0 5px 5px 0"> 
					<a href="{{ route('showterm',$term->id) }}" class="whole">
					<h4>
						أسم المشروع : {{$term->project->name}}<br>
						كود البند	: {{$term->code}}<br>
					 	نوع البند	: {{$term->type}}
					</h4> 
					<p>
						<span class="label label-default">بيان الأعمال</span>
						 {{$term->statement}}
					</p> 
					</a>
				</div>
			@endforeach
			<div class="row item" style="text-align: center;">
				<a href="{{route('ContractedTerms',$contractor->id)}}"class="btn btn-default">
					جميع البنود المتعاقد عليها 
				</a>
			</div>
			@else
				<div class="alert alert-warning">لا يوجد بنود متعاقد عليها مع هذا المقاول</div>
			@endif
		</div>
	</div>
	@endif
	@if(isset($stores))
	<div class="panel panel-default">
		<div class="panel-heading navy-heading">
			<h3>واردات المخازن</h3>
		</div>
		<div class="panel-body">
			@if(count($stores)>0)
			@foreach($stores as $store)
				<div class="bordered-right border-navy" style="padding:0 5px 5px 0"> 
					<a href="" class="whole">
					<h4>
						أسم المشروع : {{$store->project->name}}
					</h4> 
					<p>
						<span class="label label-default">نوع الخام</span>
						 {{$store->type}}<br>
						<span class="label label-default">الكمية</span>
						 {{$store->amount}} {{ $store->unit }}<br>
						 <span class="label label-default">السعر الأجمالى</span>
						 {{ $store->value*$store->amount }}<br>
						 <span class="label label-default">المبلغ المدفوع</span>
						 {{ $store->amount_paid }}
					</p> 
					</a>
				</div>
			@endforeach
			<div class="row item" style="text-align: center;">
				<a href="{{route('ContractedStores',$contractor->id)}}" class="btn btn-default">
					جميع الخامات الموردة
				</a>
			</div>
			@else
				<div class="alert alert-warning">لا يوجد خامات موردة من هذا المقاول</div>
			@endif
		</div>
	</div>
	@endif
	</div>
</div>
</div>
@endsection
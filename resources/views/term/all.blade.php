@extends('layouts.master')
@section('title','جميع البنود')
@endsection
@section('content')
<div class="content">
	@if(session('delete_error'))
	<div class="alert alert-danger">
		<strong>خطأ</strong>
		<br>
		<ul>
			<li>{{ session('delete_error') }}</li>
		</ul>
	</div>
	@endif
	@if(session('success'))
		<div class="alert alert-success">
			<strong>تمت بنجاح</strong>
			<br>
			<ul>
				<li>{{ session('success') }}</li>
			</ul>
		</div>
	@endif
	<div class="panel panel-default">
		<div class="panel-heading">
			@if(isset($project))
			<h3>
			جميع بنود <a href="{{ route('showproject',$project->id) }}">{{$project->name}}</a>
			</h3>
			@else
			<h3>جميع بنود المتعاقد عليها</h3>
			@endif
		</div>
		<div class="panel-body">
		@if(Auth::user()->type=='admin')
			@if(count($terms)>0)
			<div class="table-responsive">
			<table class="table table-bordered">
				<thead>
					<tr>
					<th>#</th>
					<th>نوع البند</th>
					<th>كود البند</th>
					<th>بيان الأعمال</th>
					<th>وحدة</th>
					<th>الكمية</th>
					<th>الفئة</th>
					<th>القيمة</th>
					<th>حالة التعاقد</th>
					</tr>
				</thead>
				<tbody>
				<?php $count=1;?>
				@foreach($terms as $term)
					<tr>
						<td>{{$count++}}</td>
						<td>{{$term->type}}</td>
						<th><a href="{{ route('showterm',$term->id) }}">{{$term->code}}</a></th>
						<td>{{$term->statement}}</td>
						<td>{{$term->unit}}</td>
						<td>{{$term->amount}}</td>
						<td>{{$term->value}}</td>
						<td>{{$term->value*$term->amount}}</td>
						<td>
							@if($term->contractor_id!=null)
								متعاقد
							@else
								لم يتم التعاقد
							@endif
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
			</div>
			@else
			<div class="alert alert-warning">
				<p>لا يوجد بنود</p>
			</div>
			@endif
		@else
			@if(count($terms)>0)
			<table class="table table-bordered">
				<thead>
					<tr>
					<th>#</th>
					<th>نوع البند</th>
					<th>كود البند</th>
					<th>بيان الأعمال</th>
					<th>وحدة</th>
					<th>الكمية</th>
					</tr>
				</thead>
				<tbody>
				<?php $count=1;?>
				@foreach($terms as $term)
					<tr>
						<td>{{$count++}}</td>
						<td>{{$term->type}}</td>
						<th>{{$term->code}}</th>
						<td><a href="{{ route('showterm',$term->id) }}">{{$term->statement}}</a></td>
						<td>{{$term->unit}}</td>
						<td>{{$term->amount}}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
			@else
			<div class="alert alert-warning">
				<p>لا يوجد بنود</p>
			</div>
			@endif
		@endif
		</div>
	</div>
</div>
@endsection
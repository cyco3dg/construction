@extends('layouts.master')
@section('title')
@if(Route::current()->getName()=='allraw')
جميع مقاوليين الخامات
@elseif(Route::current()->getName()=='alllabor')
جميع مقاوليين العمالة
@elseif(Route::current()->getName()=='allcontractor')
جميع المقاوليين
@endif
@endsection
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			@if(Route::current()->getName()=='allraw')
			<h3>جميع مقاوليين الخامات</h3>
			@elseif(Route::current()->getName()=='alllabor')
			<h3>جميع مقاوليين العمالة</h3>
			@elseif(Route::current()->getName()=='allcontractor')
			<h3>جميع المقاوليين</h3>
			@endif
		</div>
		<div class="panel-body">
			@if(session('success'))
				<div class="alert alert-success">
					<strong>تمت بنجاح</strong>
					<br>
					<ul>
						<li>{{ session('success') }}</li>
					</ul>
				</div>
			@endif
			@if(count($contractors)>0)
			<div class="table-responsive">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>#</th>
						<th>أسم المقاول</th>
						<th>نوع المقاولة</th>
						<th>المدينة</th>
						<th>التليفون</th>
						<th>الدور</th>
					</tr>
				</thead>
				<tbody>
				<?php $count=1;?>
				@foreach($contractors as $contractor)
				<tr>
					<th>{{ $count++ }}</th>
					<th>
						<a href="{{ route('showcontractor',$contractor->id) }}">
						{{ $contractor->name }}
						</a>
					</th>
					<th>{{ $contractor->type }}</th>
					<th>{{ $contractor->city }}</th>
					<th>{{ $contractor->phone }}</th>
					@if($contractor->role=='labor')
					<th>توريد عمالة</th>
					@elseif($contractor->role=='raw')
					<th>توريد خامات</th>
					@elseif($contractor->role=='both')
					<th>توريد عمالة و خامات</th>
					@endif
				</tr>
				@endforeach
				</tbody>
			</table>
			</div>
			@else
			<div class="alert alert-warning">
				لا يوجد مقاوليين @if(Route::current()->getName()=='allraw') خامات @elseif(Route::current()->getName()=='alllabor') عمالة@endif
			</div>
			@endif
			<a href="{{ route('addcontractor') }}" class="btn btn-primary">أضافة مقاول</a>
		</div>
	</div>
</div>
@endsection
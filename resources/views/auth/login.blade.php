@extends('layouts.master')
@section('title','تسجيل دخول')
@endsection()
@section('guestcontent')
<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<div class="panel panel-default">
			<div class="panel-heading">تسجيل دخول</div>
			<div class="panel-body">
				@if (count($errors) > 0)
					<div class="alert alert-danger">
						<strong>خطأ</strong>
						<br>
						<ul>
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif

				<form class="form-horizontal" role="form" method="POST" action="{{ route('postLogin') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">

					<div class="form-group">
						<label class="col-md-2 col-md-offset-2 control-label">أسم المستخدم</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="username" value="{{ old('username') }}">
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 col-md-offset-2 control-label">كلمة المرور</label>
						<div class="col-md-6">
							<input type="password" class="form-control" name="password">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-6 col-md-offset-4">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="remember"> تذكرنى
								</label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-6 col-md-offset-4">
							<button type="submit" class="btn btn-primary width-100">
								دخول
							</button>
							<a href="/password/email">هل نسيت كلمة المرور؟</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection()
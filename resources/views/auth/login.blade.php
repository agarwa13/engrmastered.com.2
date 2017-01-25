@extends('app')

@section('content')

	<div class="container">
		<div class="row">
			<div class="col-md-6">
				@include('auth.login_form')
			</div>
			<div class="col-md-6">
				@include('auth.register_form')
			</div>
		</div>

		<br>

		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				@include('auth.social_auth_form')
			</div>
		</div>

	</div>

@endsection
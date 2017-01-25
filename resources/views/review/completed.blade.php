@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if($positive_review)
                    <p>We are glad you are happy with the solution.</p>
                @else
                    <p>We apologize that you did not like the solution you received. Our team will review the issue and get back to you.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
@if(Session::has('alerts'))
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @foreach(Session::pull('alerts') as $alert)
                    <div class="alert alert-{{$alert['type']}} alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {!! $alert['message'] !!}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
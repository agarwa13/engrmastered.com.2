@extends('app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3>History</h3>

                <table class="table table-striped">

                    <thead>
                        <tr>
                            <td>Question</td>
                            <td>Used On</td>
                            {{--<td>Review</td>--}}
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($usage_records as $usage_record)
                        <tr>
                            <td><a href="{{url('usage_record/'.$usage_record->id)}}">{{$usage_record->question->title}}</a></td>
                            <td>{{ date('F d, Y', strtotime($usage_record->created_at)) }}</td>
                            {{--<td>Request Refund</td>--}}
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
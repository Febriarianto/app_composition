@extends('admin.dashboard')
@section('css')
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{route('app.index')}}" class="btn btn-close float-end"></a>
        <h5 class="card-title">{{$title}}</h5>
    </div>
    <div class="card-body">
        <!-- <div class="mb-5">
            <a href="{{route('app.print',$print)}}" target="_blank" class="btn btn-info float-end"><i class="fas fa-print"></i></a>
        </div>
        <hr> -->
        <div class="mt-3 mb-3">
            <h6 class="card-title">Application Name : {{$data->name}}</h6>
        </div>
        <table class="table">
            <thead>
                <th>No</th>
                <th>Module</th>
                <th>Feature</th>
                <th>Duration (Hours)</th>
            </thead>
            <tbody>
                @foreach ($data_module as $key => $value)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$value->name}}</td>
                    <td>
                        @foreach ($data_feature as $f)
                        @if ($f->module==$value->name)
                        {{$f->feature}},
                        @endif
                        @endforeach
                    </td>
                    <td>{{$value->duration}}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"><b>Total</b></td>
                    <td>
                        <b>{{$total}}</b>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
@section('script')
@endsection
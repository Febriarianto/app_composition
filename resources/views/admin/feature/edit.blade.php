@extends('admin.dashboard')
@section('css')
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Form {{$title}}</h5>
    </div>
    <div class="card-body">
        <form action="{{route('feature.update',$data->id)}}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" id="" placeholder="Input Name" name="_name" value="{{$data->name}}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Output</label>
                <input type="text" class="form-control" id="" placeholder="Input Output" name="_output" value="{{$data->output}}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="_description" id="" class="form-control" style="height: 168px" required>{{$data->description}}</textarea>
            </div>
            <div class="mb-3 col-sm-3">
                <label class="form-label">Duration</label>
                <div class="row">
                    <div class="col">
                        <input type="text" class="form-control" id="" name="_duration" value="{{$data->duration}}">
                    </div>
                    <div class="col">
                        <label>*Minutes</label>
                    </div>
                </div>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save</button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('script')
@endsection
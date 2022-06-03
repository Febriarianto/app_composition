@extends('admin.dashboard')
@section('css')
<link rel="stylesheet" href="{{asset ('plugins/select2/css/select2.min.css')}}">
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{route('module.index')}}" class="btn btn-close float-end"></a>
        <h5 class="card-title">Form {{$title}}</h5>
    </div>
    <div class="card-body">
        <form action="{{route('module.update',$data->id)}}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" id="" placeholder="Input Name" name="_name" value="{{$data->name}}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="_description" id="" class="form-control" style="height: 168px" required>{{$data->description}}</textarea>
            </div>
            <label class="form-label">Feature</label>
            <div class="mb-3">
                <select class="form-control js-example-basic-multiple" name="chkfeature[]" multiple="multiple" id="feature">
                </select>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save</button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('script')
<script src="{{asset('plugins/select2/js/select2.min.js')}}"></script>
<script>
    $(document).ready(function() {
        let selectFeature = $('#feature');
        selectFeature.select2({
            placeholder: "Select Feature",
            allowClear: true,
            ajax: {
                url: "{{route('module.select')}}",
                dataType: "json",
                cache: true,
                data: function(e) {
                    return {
                        q: e.term || '',
                        page: e.page || 1
                    }
                }
            }
        });
        let data = '<?= $chkfeature; ?>';
        let datas = JSON.parse(data);
        for (let i = 0; i < data.length; i++) {
            var $f = $("<option selected = 'selected'></option> ").val(datas[i]['id']).text(datas[i]['name']);
            selectFeature.append($f).trigger('change')
        };
    });
</script>
@endsection
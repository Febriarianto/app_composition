@extends('admin.dashboard')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.0/css/jquery.dataTables.min.css">
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title">{{$title}}</h5>
    </div>
    <div class="card-body">
        <a href="{{route('feature.create')}}" class="btn btn-primary float-right"><i class="fas fa-plus"></i> Add</a>
        <hr>
        <table id="example" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Output</th>
                    <th>Description</th>
                    <th>Duration (minutes)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('feature.delete')}}" id="featureForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="featureId" name="id">
                    <label>Are you sure you want to delete <b><label id="featureName"></label></b> ?</label>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger"><i class="fas fa-check"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.0/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.2.3/js/dataTables.fixedHeader.min.js"></script>
<script>
    $(function() {
        var table = $('#example').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('feature.index') }}",
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'output',
                    name: 'output'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'duration',
                    name: 'duration'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            "drawCallback": function(settings) {
                $(".btn-delete").click(function() {
                    var ids = $(this).data('id');
                    var name = $(this).data('name');
                    $('#featureId').val(ids);
                    $('#featureName').text(name);
                    $('#exampleModal').modal('show');

                });
            }
        });
    });
</script>
@endsection
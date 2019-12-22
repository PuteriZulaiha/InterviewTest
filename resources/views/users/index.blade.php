@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">User
                    <div class=" pull-right">
                        <a onclick="add()" href="javascript:;" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Add</a>
                    </div>
                </div>

                <div class="card-body">
                    <table id="table-user" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('modal')
<!-- Modal -->
<div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add new user</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-add" role="form" method="post" action="{{ route('users') }}">
           
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Name</label>

                        <div class="col-md-6">
                            <input id="name" class="form-control @error('name') is-invalid @enderror" name="name" value="" required autofocus>

                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Email</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="" required autofocus>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Password</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="" required autofocus>

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Confirmation Password</label>

                        <div class="col-md-6">
                            <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" value="" required autofocus>

                            @error('password_confirmation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Type</label>

                        <div class="col-md-6">
                            <select id="type" name="type" class="form-control full-width autoscroll" required="">
                                @foreach(__('options.user_type') as $index => $type)
                                <option value="{{ $index }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary submit" onclick='$("#form-add").submit()'><i class="fa fa-check" ></i> Add</button>
            </div>
        </div>
    </div>
</div>
@endpush
@push('js')
<script type="text/javascript">
var table = $('#table-user');

var settings = {
    "processing": true,
    "serverSide": true,
    "deferRender": true,
    "ajax": "{{ route('users') }}",
    "columns": [
        { data: 'index', defaultContent: '', orderable: false, searchable: false, render: function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
        }},
        { data: "name", name: "name"},
        { data: "email", name: "email"},
        { data: "type", name: "type"},
        { data: "action", name: "action", orderable: false, searchable: false},
    ],

    "destroy": true,
    "scrollCollapse": true,
    "oLanguage": {
        "sEmptyTable":      "No data",
        "sInfo":            "View data from _START_ until _END_ from _TOTAL_ record",
        "sInfoEmpty":       "View 0 until 0 from 0 record",
        "sInfoFiltered":    "(Filter from _MAX_ rekod)",
        "sInfoPostFix":     "",
        "sInfoThousands":   ",",
        "sLengthMenu":      "View _MENU_ record",
        "sLoadingRecords":  "Processing...",
        "sProcessing":      "Processing...",
        "sSearch":          "Search:",
       "sZeroRecords":      "No match found.",
       "oPaginate": {
           "sFirst":        "First",
           "sPrevious":     "Before",
           "sNext":         "Next",
           "sLast":         "Last"
       },
       "oAria": {
           "sSortAscending":  ": activated to ascending order",
           "sSortDescending": ": activated to descending order"
       }
    },
    "iDisplayLength": -1
};

table.dataTable(settings);

// search box for table
$('#search-table').keyup(function() {
    table.fnFilter($(this).val());
});

function add() {
    $('#modal-add').modal('show');
    $('.modal form').trigger("reset");
    $('.modal form').validate();
}

function edit(id) {
    $("#modal-div").load("{{ route('users') }}/"+id);
}

$("#form-add").submit(function(e) {
    e.preventDefault();
    var form = $(this);

    if(!form.valid())
       return;

    $.ajax({
        url: form.attr('action'),
        method: form.attr('method'),
        data: new FormData(form[0]),
        dataType: 'json',
        async: true,
        contentType: false,
        processData: false,
        success: function(data) {
            swal(data.title, data.message, data.status);
            $("#modal-add").modal("hide");
            table.api().ajax.reload(null, false);
        },
        error: function(xhr, ajaxOptions, thrownError){
            swal('Error!', 'Data cannot be added.', 'error');
        }
    });
});

function remove(id) {
    swal({
        title: "Delete Data",
        text: "Do you want to delete this data?",
        icon: "warning",
        buttons: ["Cancel", { text: "Delete", closeModal: false }],
        dangerMode: true,
    })
    .then((confirm) => {
        if (confirm) {
            console.log("{{ route('users') }}/"+id);
            $.ajax({
                url: "{{ route('users') }}/"+id,
                method: 'delete',
                dataType: 'json',
                async: true,
                contentType: false,
                processData: false,
                success: function(data) {
                    swal(data.title, data.message, data.status);
                    table.api().ajax.reload(null, false);
                }
            });
        }
    });
}
</script>
@endpush
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    Listing
                    @if(auth()->user()->type == 'a')
                    <div class=" pull-right">
                        <a onclick="add()" href="javascript:;" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Add</a>
                    </div>
                    @endif
                </div>

                <div class="card-body">
                    <table id="table-list" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>List Name</th>
                                <th>Address</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Submitter</th>
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
<div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add new listing</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-add" role="form" method="post" action="{{ route('listings') }}">
                    @csrf
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Listing Name</label>

                        <div class="col-md-6">
                            <input id="list_name" class="form-control @error('list_name') is-invalid @enderror" name="list_name" value="" required autofocus>

                            @error('list_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Address</label>

                        <div class="col-md-6">
                            <input id="address" type="address" class="form-control @error('address') is-invalid @enderror" name="address" value="" required autofocus>

                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Latitude</label>

                        <div class="col-md-6">
                            <input id="latitude" type="latitude" class="form-control @error('latitude') is-invalid @enderror" name="latitude" value="" required autofocus>

                            @error('latitude')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Longitude</label>

                        <div class="col-md-6">
                            <input id="longitude" type="longitude" class="form-control @error('longitude') is-invalid @enderror" name="longitude" value="" required autofocus>

                            @error('longitude')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="$('#form-add').submit()"><i class="fa fa-check" ></i> Add</button>
            </div>
        </div>
    </div>
@endpush
@push('js')
<script type="text/javascript">
var table = $('#table-list');

var settings = {
    "processing": true,
    "serverSide": true,
    "deferRender": true,
    "ajax": "{{ route('listings') }}",
    "columns": [
        { data: 'index', defaultContent: '', orderable: false, searchable: false, render: function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
        }},
        { data: "list_name", name: "list_name"},
        { data: "address", name: "address"},
        { data: "latitude", name: "latitude"},
        { data: "longitude", name: "longitude"},
        { data: "submitter.name", name: "submitter.name"},
        { data: "action", name: "action", orderable: false, searchable: false, 
            @if(auth()->user()->type != 'a')
            visible:false
            @endif
        },
    ],
    "columnDefs": [
        { className: "nowrap", "targets": [ 5 ] }
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
    $("#modal-div").load("{{ route('listings') }}/"+id);
}

$("#form-add").submit(function(e) {
    console.log('k');
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
            $.ajax({
                url: "{{ route('listings') }}/"+id,
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
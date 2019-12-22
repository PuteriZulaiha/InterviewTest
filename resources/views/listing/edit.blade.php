
<!-- Modal -->
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update listing</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-edit" role="form" method="post" action="{{ route('listings.form', $list->id) }}">
                    @csrf @method('PUT')
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Listing Name</label>

                        <div class="col-md-6">
                            <input id="list_name" class="form-control @error('list_name') is-invalid @enderror" name="list_name" value="{{ $list->list_name }}" required autofocus>

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
                            <input id="address" type="address" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ $list->address }}" required autofocus>

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
                            <input id="latitude" type="latitude" class="form-control @error('latitude') is-invalid @enderror" name="latitude" value="{{ $list->latitude }}" required autofocus>

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
                            <input id="longitude" type="longitude" class="form-control @error('longitude') is-invalid @enderror" name="longitude" value="{{ $list->longitude }}" required autofocus>

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
                <button type="button" class="btn btn-primary submit" onclick='$("#form-edit").submit()'><i class="fa fa-check" ></i> Save</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

$('#modal-edit').modal('show');
$(".modal form").validate();

$("#form-edit").submit(function(e) {
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
            $("#modal-edit").modal("hide");
            table.api().ajax.reload(null, false);
        }
    });
});
</script>
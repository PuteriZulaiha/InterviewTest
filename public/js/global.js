function submitForm(form_id) {
	$("#"+form_id).submit()
    // $("#"+form_id).submit();
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
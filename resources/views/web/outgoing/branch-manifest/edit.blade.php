@extends('layouts.materialize.index')

@section('content')
<div class="row">

    @component('layouts.materialize.components.title-wrapper')
        <div class="row">
            <div class="col s12 m6">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Create Manifest</span></h5>
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('branch-manifest') }}">Branch Manifest</a></li>
                    <li class="breadcrumb-item active">Create Manifest</li>
                </ol>
            </div>
        </div>
    @endcomponent

    <div class="col s12">
        <div class="container">

            <div class="section">

              <div class="card">
                <div class="card-content p-0">
                  <ul class="collapsible m-0">
                    <li class="active">
                      <div class="collapsible-header"><i class="material-icons">keyboard_arrow_right</i>Detail</div>
                      <div class="collapsible-body padding-1">
                        @include('web.outgoing.branch-manifest._form_manifest')
                        @include('web.outgoing.branch-manifest._list_do')
                        @if($lmbHeader->do_details->count() > 0) 
                        @include('web.outgoing.branch-manifest._form_assign_do')
                        @endif
                      </div>
                    </li>
                  </ul>
                </div>
                <div class="content-overlay"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection

@push('vendor_js')
<script src="{{ asset('materialize/vendors/jquery-validation/jquery.validate.min.js') }}">
</script>
@endpush

@push('script_js')
<script type="text/javascript">
  jQuery(document).ready(function($) {
    set_select2_value('#form-manifest [name="city_code"]', '{{$manifestHeader->city_code}}', '{{$manifestHeader->city_name}}')
    set_select2_value('#form-assign-do [name="city_code"]', '{{$manifestHeader->city_code}}', '{{$manifestHeader->city_name}}')
    $('#form-assign-do [name="city_name"]').val('{{$manifestHeader->city_name}}')

    $('#form-manifest .btn-save').text('UPDATE');

    $('#form-manifest').validate({
      submitHandler: function(form){
        setLoading(true); // Disable Button when ajax post data
        $.ajax({
          url: '{{ url("branch-manifest/" . $manifestHeader->do_manifest_no ) }}',
          type: 'PUT',
          data: $(form).serialize(),
        })
        .done(function(data) { // selesai dan berhasil
          setLoading(false); // Enable Button when failed
          showSwalAutoClose("Data manifest updated.")
        })
        .fail(function(xhr) {
            setLoading(false); // Enable Button when failed
            showSwalError(xhr) // Custom function to show error with sweetAlert
        });
      }
    })
  });
    $("#form-assign-do").validate({
      submitHandler: function(form) {
        var selected_list = [];
        dtdatatable_submit_to_logsys.$('tr').each(function() {
          var row_data = dtdatatable_submit_to_logsys.row(this).data()
          selected_list.push(row_data);
        });
        setLoading(true); // Disable Button when ajax post data
        $.ajax({
          url: '{{ url("branch-manifest/" . $manifestHeader->do_manifest_no . "/assign-do") }}',
          type: 'POST',
          data: $(form).serialize() + '&selected_list=' + JSON.stringify(selected_list),
        })
        .done(function(result) { // selesai dan berhasil
          // setLoading(true); // Disable Button when ajax post data
          if (result.status) {
            window.location.href = '{{ url("branch-manifest/" . $manifestHeader->do_manifest_no . "/edit") }}'
          } else{
            setLoading(false);
          }
        })
        .fail(function(xhr) {
          setLoading(false);
            showSwalError(xhr) // Custom function to show error with sweetAlert
        });
      }
    });
</script>
@endpush

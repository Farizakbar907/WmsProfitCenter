@extends('layouts.materialize.index')

@section('content')
<div class="row">

    @component('layouts.materialize.components.title-wrapper')
        <div class="row">
            <div class="col s12 m6">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Berita Acara</span></h5>
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item">Berita Acara</li>
                    <li class="breadcrumb-item active">Create Berita Acara</li>
                </ol>
            </div>
        </div>
    @endcomponent
    
    <div class="col s12">
        <div class="container">
            <div class="section">
                <!-- <div class="card"> -->
                    <div class="card-content">
                        <ul class="collapsible">
                           <li class="active">
                             <div class="collapsible-header">Create Berita Acara</div>
                             <div class="collapsible-body white pt-1 pb-1">
                                @include('web.claim.berita-acara._form_berita_acara')
                             </div>
                           </li>
                        </ul>
                    </div>
                <!-- </div> -->
                    <div class="card-content">
                        <ul class="collapsible">
                           <li class="">
                               <div class="collapsible-header">Add New Detail</div>
                               <div class="collapsible-body white pt-1 pb-1">
                                 @include('web.claim.berita-acara._form_detail')
                               </div>
                           </li>
                        </ul>
                    </div>
                    <div class="card-content">
                        <ul class="collapsible">
                           <li class="active">
                               <div class="collapsible-header">Detail</div>
                               <div class="collapsible-body white pt-1 pb-1">
                                <table id="data-table-section-contents" class="display" width="100%">
                                  <thead>
                                      <tr>
                                        <th data-priority="1" width="30px">No.</th>
                                        <th>No DO</th>
                                        <th>Model/Item No.</th>
                                        <th>No Seri</th>
                                        <th>Qty</th>
                                        <th>Jenis Kerusakan</th>
                                        <th>Photo</th>
                                        <th>Keterangan</th>
                                        <th width="50px;"></th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                  </tbody>
                              </table>
                               </div>
                           </li>
                        </ul>
                    </div>
            </div>
        </div>
        <div class="content-overlay"></div>
    </div>
</div>
@endsection

@push('vendor_js')
<script src="{{ asset('materialize/vendors/jquery-validation/jquery.validate.min.js') }}">
</script>
@endpush

@push('script_js')
<script type="text/javascript">
  $('.collapsible').collapsible({
        accordion:true
    });

  $("#form-berita-acara").validate({
      submitHandler: function(form) {
        $.ajax({
          url: '{{ url("berita-acara") }}',
          type: 'POST',
          data: $(form).serialize(),
        })
        .done(function() { // selesai dan berhasil
          swal("Good job!", "You clicked the button!", "success")
            .then((result) => {
              // Kalau klik Ok redirect ke view
              window.location.href = "{{ url('berita-acara/') }}" + '/' + response.id + '/view'
            }) // alert success
        })
        .fail(function(xhr) {
            showSwalError(xhr) // Custom function to show error with sweetAlert
        });
      }
    });
</script>
@endpush
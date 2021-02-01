@extends('layouts.materialize.index')

@section('content')
<div class="row">

    @component('layouts.materialize.components.title-wrapper')
        <div class="row">
            <div class="col s12 m6">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Branch Invoicing</span></h5>
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active">Branch Invoicing</li>
                </ol>
            </div>
            <div class="col s12 m6">
              <div class="display-flex">
                <!---- Search ----->
                <div class="app-wrapper mr-2">
                  <div class="datatable-search">
                    <i class="material-icons mr-2 search-icon">search</i>
                    <input type="text" placeholder="Search" class="app-filter" id="global_filter">
                  </div>
                </div>
              </div>
            </div>
            <div class="col s12 m3">
            </div>
        </div>
        <div class="row">
          <div class="col s12 m4">
            @if(auth()->user()->allowTo('edit'))
            <span class="btn btn-large waves-effect waves-light btn-add" id="btn-create-receipt">
              Create Reciept
            </span>
            @endif
          </div>
        </div>
    @endcomponent
    
    <div class="col s12">
        <div class="container">
            <div class="section">
                <div class="card">
                    <div class="card-content p-0">
                        <div class="section-data-tables"> 
                          <table id="data-table-section-contents" class="display" width="100%">
                              <thead>
                                  <tr>
                                    <th data-priority="1" width="30px">No.</th>
                                    <th>GROUP ID</th>
                                    <th>MANIFEST NO</th>
                                    <th>EXPEDITION NAME</th>
                                    <th width="50px;"></th>
                                  </tr>
                              </thead>
                              <tbody></tbody>
                          </table>
                        </div>
                        <!-- datatable ends -->
                    </div>
                </div>
            </div>
            <!---- Button Add ----->
            {{-- <div style="bottom: 50px; right: 19px;" class="fixed-action-btn direction-top"><a href="#" class="btn-floating indigo darken-2 gradient-shadow modal-trigger"><i class="material-icons">add</i></a> --}}
            </div>
        </div>
        <div class="content-overlay"></div>
    </div>
</div>
@endsection

@push('script_js')
<script type="text/javascript">
  var dtdatatable
  jQuery(document).ready(function($) {
     $('#btn-create-receipt').click(function(event) {
      /* Act on the event */
      window.location.href = "{{url('branch-invoicing/' . auth()->user()->cabang->kode_cabang)}}" + moment().format("YYYYMMDD hmmss");
    });

    dtdatatable = $('#data-table-section-contents').DataTable({
        serverSide: true,
        scrollX: true,
        responsive: true,
        ajax: {
            url: '{{url("branch-invoicing")}}',
            type: 'GET',
            data: function(d) {
                d.search['value'] = $('#global_filter').val();
              }
        },
        order: [1, 'asc'],
        columns: [
            {data: 'DT_RowIndex', orderable:false, searchable: false, className: 'center-align'},
            {data: 'group_id', name: 'group_id', className: 'detail'},
            {data: 'manifest_no', name: "GROUP_CONCAT(do_manifest_no SEPARATOR ', ')", className: 'detail', orderable: false, searchable: false},
            {data: 'expedition_name', name: "GROUP_CONCAT(expedition_name SEPARATOR ', ')", className: 'detail'},
            {data: 'action', className: 'center-align'},
        ]
    });

    dtdatatable.on('click', '.btn-edit', function(event) {
      var id = $(this).data('id');
      window.location.href = '' ;
    });

    dtdatatable.on('click', '.btn-delete', function(event) {
      var id = $(this).data('id');
      event.preventDefault();
      /* Act on the event */
      // Ditanyain dulu usernya mau beneran delete data nya nggak.
      swal({
        title: "Are you sure?",
        text: "You will not be able to recover this imaginary file!",
        icon: 'warning',
        buttons: {
          cancel: true,
          delete: 'Yes, Delete It'
        }
      }).then(function (confirm) { // proses confirm
        if (confirm) { // Bila oke post ajax ke url delete nya
          // Ajax Post Delete
          $.ajax({
            url: id,
            type: 'DELETE',
          })
          .done(function() { // Kalau ajax nya success
            swal("Good job!", "You clicked the button!", "success") // alert success
            dtdatatable.ajax.reload(null, false); // reload datatable
          })
          .fail(function() { // Kalau ajax nya gagal
            console.log("error");
          });
          
        }
      })
    });
  });
   
</script>
@endpush
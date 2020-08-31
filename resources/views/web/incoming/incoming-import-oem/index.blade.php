@extends('layouts.materialize.index')

@section('content')
<div class="row">

    @component('layouts.materialize.components.title-wrapper')
        <div class="row">
            <div class="col s12 {{auth()->user()->cabang->hq ? 'm3' : 'm6'}}">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Incoming Import/OEM</span></h5>
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active">Incoming Import/OEM</li>
                </ol>
            </div>
            <div class="col s12 m3 {{auth()->user()->cabang->hq ? '' : 'hide'}}">
              <!---- Select ----->
                <div class="app-wrapper">
                  <div class="datatable-search">
                    <select id="area_filter" class="select2-data-ajax browser-default app-filter">
                    </select>
                  </div>
                </div>
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
            <div class="col s12 m6">
                <a href="#" class="btn btn-large waves-effect waves-light btn-add {{auth()->user()->cabang->hq ? 'hide' : ''}}" type="submit" name="action">
                  New Incoming Import/OEM
                </a>
            </div>
        </div>
    @endcomponent
    
    <div class="col s12">
        <div class="container">
            <div class="section">
                <div class="card">
                    <div class="card-content p-0">
                        <div class="section-data-tables"> 
                          <table id="data-table-incoming-import-oem" class="display" width="100%">
                              <thead>
                                  <tr>
                                    <th data-priority="1" width="30px">No.</th>
                                    <th>ARRIVAL NO</th>
                                    <th>PO</th>
                                    <th>VENDOR NAME</th>
                                    <th>STATUS</th>
                                    <th>DOCUMENT DATE</th>
                                    <th width="2px;"></th>
                                    <th width="2px;"></th>
                                    <th width="2px;"></th>
                                    <th width="2px;"></th>
                                  </tr>
                              </thead>
                              <tbody>
                              </tbody>
                          </table>
                        </div>
                        <!-- datatable ends -->
                    </div>
                </div>
            </div>
        </div>
        <div class="content-overlay"></div>
    </div>
</div>
@endsection

@push('page-modal')
<div id="modal-form-print" class="modal" style="">
    <div class="modal-content">
      <form id="form-print">
        <input type="hidden" name="arrival_no">
        <table>
          <tr>
            <td width="100px">Transfer By</td>
            <td>
              <div class="input-field">
                <input type="text" name="transfer_by">
              </div>
            </td>
          </tr>
          <tr>
            <td width="100px">Checked By</td>
            <td>
              <div class="input-field">
                <input type="text" name="checked_by">
              </div>
            </td>
          </tr>
          <tr>
            <td width="100px">Locate</td>
            <td>
              <div class="input-field">
                <input type="text" name="locate">
              </div>
            </td>
          </tr>
        </table>
      </form>
    </div>
    <div class="modal-footer">
      <a href="#!" class="btn waves-effect waves-green btn-show-print-preview btn green darken-4">Print Report</a>
      <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Close</a>
    </div>
  </div>
@endpush

@push('script_js')
<script type="text/javascript">
  jQuery(document).ready(function($) {
    @if(auth()->user()->cabang->hq AND auth()->user()->area != 'All')
    $('#area_filter').attr('disabled', 'disabled');
    set_select2_value('#area_filter', '{{auth()->user()->area}}', '{{auth()->user()->area}}')
    @endif
    $('.btn-add').click(function(event) {
      /* Act on the event */
      window.location.href = '{{url("incoming-import-oem/create?area=")}}' + $('#area_filter').val()
    });
  });
    
    var table
    jQuery(document).ready(function($) {
      table = $('#data-table-incoming-import-oem').DataTable({
        serverSide: true,
        scrollX: true,
        responsive: false,
        ajax: {
            url: '{{ url('incoming-import-oem') }}',
            type: 'GET',
            data: function(d) {
                d.search['value'] = $('#global_filter').val(),
                d.area = $('#area_filter').val()
              }
        },
        order: [1, 'desc'],
        columns: [
            {data: 'DT_RowIndex', orderable:false, searchable: false, className: 'center-align'},
            {data: 'arrival_no', name: 'arrival_no', className: 'detail'},
            {data: 'po', name: 'po', className: 'detail'},
            {data: 'vendor_name', name: 'vendor_name', className: 'detail'},
            {data: 'status', name: 'status', className: 'detail'},
            {data: 'document_date', name: 'document_date', className: 'detail'},
            {data: 'action_view', className: 'center-align', searchable: false, orderable: false},
            {data: 'action_submit_to_inventory', className: 'center-align', searchable: false, orderable: false},
            {data: 'action_delete', className: 'center-align', searchable: false, orderable: false},
            {data: 'action_print', className: 'center-align', searchable: false, orderable: false},
        ]
      });

      table.on('click', '.btn-delete', function(event) {
          event.preventDefault();
          /* Act on the event */
          // Ditanyain dulu usernya mau beneran delete data nya nggak.
          var tr = $(this).parent().parent();
          var data = table.row(tr).data();
          swal({
            text: "Are you sure want to delete " + data.arrival_no + " and the details?",
            icon: 'warning',
            buttons: {
              cancel: true,
              delete: 'Yes, Delete It'
            }
          }).then(function (confirm) { // proses confirm
            if (confirm) {
                $.ajax({
                url: '{{ url('incoming-import-oem') }}' + '/' + data.arrival_no ,
                type: 'DELETE',
                dataType: 'json',
              })
              .done(function() {
                showSwalAutoClose('Success', "Incoming with Arrival No. " + data.arrival_no + " has been deleted.")
                table.ajax.reload(null, false);  // (null, false) => user paging is not reset on reload
              })
              .fail(function() {
                console.log("error");
              });
            }
          })
        });

        table.on('click', '.btn-print', function(event) {
          var tr = $(this).parent().parent();
          var data = table.row(tr).data();
          $('#form-print [name="arrival_no"]').val(data.arrival_no)
          $('#modal-form-print').modal('open')
        })

        {{-- Load Modal Print --}}
        @include('layouts.materialize.components.modal-print', [
          'title' => 'Print',
        ])

        $('.btn-show-print-preview').click(function(event) {
          /* Act on the event */
          initPrintPreviewPrint(
            '{{url("incoming-import-oem")}}' + '/' + $('#form-print [name="arrival_no"]').val() + '/export',
            $('#form-print').serialize()
          )
        });

        table.on('click', '.btn-submit-to-inventory', function(event) {
          var tr = $(this).parent().parent();
          var data = table.row(tr).data();
          swal({
            text: "Are you sure want to Submit to Inventory " + data.arrival_no + " and the details?",
            icon: 'warning',
            buttons: {
              cancel: true,
              delete: 'Yes, Submit It'
            }
          }).then(function (confirm) { // proses confirm
            if (confirm) {
              $.ajax({
                url: '{{ url('incoming-import-oem') }}' + '/' + data.arrival_no + '/submit-to-inventory',
                type: 'POST',
                dataType: 'json',
              })
              .done(function() {
                showSwalAutoClose("Success", "Incoming with Arrival No. " + data.arrival_no + " has been submited to inventory.")
                table.ajax.reload(null, false);  // (null, false) => user paging is not reset on reload
              })
              .fail(function() {
                console.log("error");
              });
            }
          })
          
        });

      $("input#global_filter").on("keyup click", function () {
        filterGlobal();
      });
    });

  $('#area_filter').change(function(event) {
    /* Act on the event */
    table.ajax.reload(null, false);  // (null, false) => user paging is not reset on reload
    if ($(this).val() == null) {
      $('.btn-add').addClass('hide');
    } else {
      $('.btn-add').removeClass('hide');
    }
  });

  $('#area_filter').select2({
       placeholder: '-- Select Area --',
       allowClear: true,
       ajax: get_select2_ajax_options('/master-area/select2-area-only')
    });

  // Custom search
  function filterGlobal() {
      table.search($("#global_filter").val(), $("#global_regex").prop("checked"), $("#global_smart").prop("checked")).draw();
  }

</script>
@endpush
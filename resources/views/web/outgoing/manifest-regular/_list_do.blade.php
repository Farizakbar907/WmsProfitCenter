<br>
<h6>Total Manifest: 1</h6>
<h5 class="card-title">List DO</h5>
<hr>
<a href="#modal-upload-do" class="waves-effect waves-light indigo btn-small btn-upload modal-trigger mb-1">Upload DO</a>
@include('web.outgoing.manifest-regular.modal_upload_do')
<a href="#modal-upload-return" class="waves-effect waves-light indigo btn-small btn-upload modal-trigger mb-1">Upload Return</a>
@include('web.outgoing.manifest-regular.modal_upload_return')
<div class="section-data-tables"> 
  <table id="list-do-table" class="display" width="100%">
    <thead>
      <tr>
        <th>NO.</th>
        <th>No Shipment</th>
        <th>DO No</th>
        <th>DO Int No</th>
        <th>City Ship To</th>
        <th>Items</th>
        <th>Model</th>
        <th>QTY</th>
        <th>DESC</th>
        {{-- <th>Status</th> --}}
        <th>Customer Code</th>
        <th width="50px;"></th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
</div>
  <!-- datatable ends -->

@push('script_js')
<script type="text/javascript">
  var dttable_list_do
  jQuery(document).ready(function($) {

    @if($manifestHeader->status_complete)
    $('.btn-save').addClass('hide')
    $('.btn-upload').addClass('hide')
    $('.btn-delete').addClass('hide')
    @endif
    dttable_list_do = $('#list-do-table').DataTable({
    serverSide: true,
    scrollX: true,
    responsive: true,
    ajax: {
        url: '{{ url('manifest-regular/' . $manifestHeader->do_manifest_no . '/list-do') }}',
        type: 'GET',
        data: function(d) {
            d.search['value'] = $('#global_filter').val()
          }
    },
    order: [2, 'asc'],
    columns: [
        {data: 'DT_RowIndex', orderable:false, searchable: false, className: 'center-align'},
        {data: 'invoice_no', name: 'invoice_no', className: 'detail'},
        {data: 'delivery_no', name: 'delivery_no', className: 'detail'},
        {data: 'do_internal', name: 'do_internal', className: 'detail'},
        {data: 'ship_to', name: 'ship_to', className: 'detail'},
        {data: 'delivery_items', name: 'delivery_items', className: 'detail'},
        {data: 'model', name: 'model', className: 'detail'},
        {data: 'quantity', name: 'quantity', className: 'detail'},
        {data: 'desc', name: 'desc', className: 'detail'},
        {data: 'ship_to_code', name: 'ship_to_code', className: 'detail'},
        {data: 'action', className: 'center-align', searchable: false, orderable: false},
      ],
    });

    $('#list-do-table').on('click', '.btn-delete', function(event) {
      event.preventDefault();
      /* Act on the event */
      var tr = $(this).parent().parent();
      var data = dttable_list_do.row(tr).data();

      swal({
        title: "Are you sure?",
        icon: 'warning',
        buttons: {
          cancel: true,
          delete: 'Yes, Delete It'
        }
      }).then(function (confirm) { // proses confirm
        if (confirm) { // Bila oke post ajax ke url delete nya
          // Ajax Post Delete
          $.ajax({
            url: '{{ url('manifest-regular/delete-do') }}' ,
            type: 'DELETE',
            data: 'id=' + data.id
          })
          .done(function() { // Kalau ajax nya success
            showSwalAutoClose('Success', 'Data deleted.')
            dttable_list_do.ajax.reload(null, false); // reload datatable
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
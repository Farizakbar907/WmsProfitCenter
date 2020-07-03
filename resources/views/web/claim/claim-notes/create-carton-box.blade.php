@extends('layouts.materialize.index')

@section('content')
<div class="row">

    @component('layouts.materialize.components.title-wrapper')
        <div class="row">
            <div class="col s12 m10">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Create Claim Note Carton Box</span></h5>
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('claim-notes') }}">Claim Notes</a></li>
                    <li class="breadcrumb-item active">Create Claim Note Carton Box</li>
                </ol>
            </div>
            <div class="col s12 m2">
                <a class="btn btn-large waves-effect waves-light indigo" href="{{ url('claim-notes') }}">Back</a>
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
                            <div class="collapsible-header p-0">
                              <div class="row">
                                <div class="col s12 m8">
                                  <div class="collapsible-main-header">
                                    <i class="material-icons expand">expand_less</i>
                                    <span>Outstanding List Berita Acara</span>
                                  </div>
                                </div>
                                <div class="col s12 m4">
                                  <div class="app-wrapper">
                                    <div class="datatable-search mb-0">
                                      <i class="material-icons mr-2 search-icon">search</i>
                                      <input type="text" placeholder="Search" class="app-filter no-propagation" id="from-manifest-hq-filter">
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                             <div class="collapsible-body white p-0">
                                <div class="section-data-tables"> 
                                    <table id="data-table-list-berita-acara" class="display" width="100%">
                                      <thead>
                                          <tr>
                                            <th data-priority="1" width="30px">NO.</th>
                                            <th>BERITA ACARA</th>
                                            <th>DATE</th>
                                            <th>EXPEDITION NAME</th>
                                            <th>DRIVER</th>
                                            <th>VEHICLE NO.</th>
                                            <th width="50px;"></th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                        <!-- <tr>
                                          <td>1.</td>
                                          <td>01/BA-HQ/02/2015</td>
                                          <td>May 21, 2020</td>
                                          <td>Expedition 1</td>
                                          <td>Driver 1</td>
                                          <td>B 1231 DE</td>
                                          <td>
                                            {!! get_button_view('#', 'Select') !!}
                                          </td>
                                        </tr>
                                        <tr>
                                          <td>2.</td>
                                          <td>01/BA-HQ/02/2015</td>
                                          <td>May 21, 2020</td>
                                          <td>Expedition 1</td>
                                          <td>Driver 1</td>
                                          <td>B 1231 DE</td>
                                          <td>
                                            {!! get_button_view('#', 'Select') !!}
                                          </td>
                                        </tr>
                                        <tr>
                                          <td>3.</td>
                                          <td>01/BA-HQ/02/2015</td>
                                          <td>May 21, 2020</td>
                                          <td>Expedition 1</td>
                                          <td>Driver 1</td>
                                          <td>B 1231 DE</td>
                                          <td>
                                            {!! get_button_view('#', 'Select') !!}
                                          </td>
                                        </tr>
                                        <tr>
                                          <td>4.</td>
                                          <td>01/BA-HQ/02/2015</td>
                                          <td>May 21, 2020</td>
                                          <td>Expedition 1</td>
                                          <td>Driver 1</td>
                                          <td>B 1231 DE</td>
                                          <td>
                                            {!! get_button_view('#', 'Select') !!}
                                          </td>
                                        </tr>
                                        <tr>
                                          <td>5.</td>
                                          <td>01/BA-HQ/02/2015</td>
                                          <td>May 21, 2020</td>
                                          <td>Expedition 1</td>
                                          <td>Driver 1</td>
                                          <td>B 1231 DE</td>
                                          <td>
                                            {!! get_button_view('#', 'Select') !!}
                                          </td>
                                        </tr>
                                        <tr>
                                          <td>6.</td>
                                          <td>01/BA-HQ/02/2015</td>
                                          <td>May 21, 2020</td>
                                          <td>Expedition 1</td>
                                          <td>Driver 1</td>
                                          <td>B 1231 DE</td>
                                          <td>
                                            {!! get_button_view('#', 'Select') !!}
                                          </td>
                                        </tr>
                                        <tr>
                                          <td>7.</td>
                                          <td>01/BA-HQ/02/2015</td>
                                          <td>May 21, 2020</td>
                                          <td>Expedition 1</td>
                                          <td>Driver 1</td>
                                          <td>B 1231 DE</td>
                                          <td>
                                            {!! get_button_view('#', 'Select') !!}
                                          </td>
                                        </tr>
                                        <tr>
                                          <td>8.</td>
                                          <td>01/BA-HQ/02/2015</td>
                                          <td>May 21, 2020</td>
                                          <td>Expedition 1</td>
                                          <td>Driver 1</td>
                                          <td>B 1231 DE</td>
                                          <td>
                                            {!! get_button_view('#', 'Select') !!}
                                          </td>
                                        </tr> -->
                                      </tbody>
                                  </table>
                              </div>
                             </div>
                           </li>
                        </ul>
                    </div>
                <!-- </div> -->
            </div>
        </div>
        <div class="content-overlay"></div>
    </div>

    <div class="col s12">
        <div class="container">
            <div class="section">
                <!-- <div class="card"> -->
                    <div class="card-content">
                        <ul class="collapsible">
                           <li class="active">
                            <div class="collapsible-header p-0">
                              <div class="row">
                                <div class="col s12 m8">
                                  <div class="collapsible-main-header">
                                    <i class="material-icons expand">expand_less</i>
                                    <span>Berita Acara Detail</span>
                                  </div>
                                </div>
                              </div>
                            </div>
                             <div class="collapsible-body white p-0">
                                <div class="section-data-tables"> 
                                  <div class="pl-2 pr-2 pb-2">
                                      <table id="data-table-detail-berita-acara" class="bordered striped" width="100%">
                                          <thead>
                                              <tr>
                                                <th data-priority="1" width="30px">No.</th>
                                                <th>Berita Acara</th>
                                                <th>Expediton Name</th>
                                                <th>Driver</th>
                                                <th>Car No</th>
                                                <th>Destination</th>
                                                <th>DO NO</th>
                                                <th>Model</th>
                                                <th>Serial No</th>
                                                <th>Qty</th>
                                                <th>Location</th>
                                                <th>Damage Description</th>
                                                <th>Price</th>
                                                <th>Total</th>
                                                {{-- <th width="50px;"></th> --}}
                                              </tr>
                                          </thead>
                                          <tbody>
                                            <!-- <tr>
                                                <td>1.</td>
                                                <td>01/BA-HQ/02/2015</td>
                                                <td>Expedition 1</td>
                                                <td>Driver 1</td>
                                                <td>B 1231 DE</td>
                                                <td>Yogyakarta</td>
                                                <td>DO</td>
                                                <td>MOD001</td>
                                                <td>124141</td>
                                                <td>2</td>
                                                <td>LOC</td>
                                                <td>Kerusakan</td>
                                                <td>
                                                    <div class="form-table">
                                                      <input placeholder="Price" id="first_name" type="text" class="validate">
                                                  </div>
                                                </td>
                                                <td>12.000.000</td>
                                            </tr>
                                            <tr>
                                                <td>2.</td>
                                                <td>01/BA-HQ/02/2015</td>
                                                <td>Expedition 1</td>
                                                <td>Driver 1</td>
                                                <td>B 1231 DE</td>
                                                <td>Yogyakarta</td>
                                                <td>DO</td>
                                                <td>MOD002</td>
                                                <td>124141</td>
                                                <td>2</td>
                                                <td>LOC</td>
                                                <td>Kerusakan</td>
                                                <td>
                                                    <div class="form-table">
                                                      <input placeholder="Price" id="first_name" type="text" class="validate">
                                                  </div>
                                                </td>
                                                <td>12.000.000</td>
                                            </tr> -->
                                          </tbody>
                                      </table>
                                      {!! get_button_view(url('claim-notes/1'), 'Save', 'btn-save mt-2') !!}
                                  </div>  
                              </div>
                             </div>
                           </li>
                        </ul>
                    </div>
                <!-- </div> -->
            </div>
        </div>
        <div class="content-overlay"></div>
    </div>

    {{-- <div class="col s12">
        <div class="container">
            <div class="section">
                <!-- <div class="card"> -->
                    <div class="card-content">
                        <ul class="collapsible">
                           <li class="active">
                             <div class="collapsible-header">Claim Note Carton Box</div>
                             <div class="collapsible-body white p-0">
                                <div class="section-data-tables"> 
                                  <div class="pl-2 pr-2 pb-2">
                                    {!!get_button_print()!!}
                                      <table id="data-table-section-contents" class="bordered striped" width="100%">
                                          <thead>
                                              <tr>
                                                <th data-priority="1" width="30px">No.</th>
                                                <th>Berita Acara</th>
                                                <th>Expediton Name</th>
                                                <th>Driver</th>
                                                <th>Car No</th>
                                                <th>Destination</th>
                                                <th>DO NO</th>
                                                <th>Model</th>
                                                <th>Serial No</th>
                                                <th>Qty</th>
                                                <th>Location</th>
                                                <th>Damage Description</th>
                                                <th>Price</th>
                                                <th>Total</th>
                                                <th width="170px;"></th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                            <tr>
                                                <td>1.</td>
                                                <td>01/BA-HQ/02/2015</td>
                                                <td>Expedition 1</td>
                                                <td>Driver 1</td>
                                                <td>B 1231 DE</td>
                                                <td>Yogyakarta</td>
                                                <td>DO</td>
                                                <td>MOD001</td>
                                                <td>124141</td>
                                                <td>2</td>
                                                <td>LOC</td>
                                                <td>Kerusakan</td>
                                                <td>
                                                    <div class="form-table">
                                                      <input placeholder="Price" id="first_name" type="text" class="validate">
                                                  </div>
                                                </td>
                                                <td>12.000.000</td>
                                                <td>
                                                    {!!get_button_edit('#', 'Update')!!}
                                                    {!!get_button_delete()!!}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2.</td>
                                                <td>01/BA-HQ/02/2015</td>
                                                <td>Expedition 1</td>
                                                <td>Driver 1</td>
                                                <td>B 1231 DE</td>
                                                <td>Yogyakarta</td>
                                                <td>DO</td>
                                                <td>MOD002</td>
                                                <td>124141</td>
                                                <td>2</td>
                                                <td>LOC</td>
                                                <td>Kerusakan</td>
                                                <td>
                                                    <div class="form-table">
                                                      <input placeholder="Price" id="first_name" type="text" class="validate">
                                                  </div>
                                                </td>
                                                <td>12.000.000</td>
                                                <td>
                                                    {!!get_button_edit('#', 'Update')!!}
                                                    {!!get_button_delete()!!}
                                                </td>
                                            </tr>
                                          </tbody>
                                      </table>
                                  </div>  
                              </div>
                             </div>
                           </li>
                        </ul>
                    </div>
                <!-- </div> -->
            </div>
        </div>
        <div class="content-overlay"></div>
    </div> --}}
</div>
@endsection

@push('vendor_js')
<script src="{{ asset('materialize/vendors/jquery-validation/jquery.validate.min.js') }}">
</script>
@endpush

@push('script_js')
<script type="text/javascript">
  $(document).ready(function() {
    $('.collapsible').collapsible({
        accordion:true
    });
  });

  var dtdatatable = $('#data-table-list-berita-acara').DataTable({
    serverSide: true,
    scrollX: true,
    responsive: true,
    pageLength: 5,
    ajax: {
        url: '{{ url('claim-notes/create-carton-box') }}',
        type: 'GET',
        data: function(d) {
            d.search['value'] = $('#global_filter').val()
          }
    },
    order: [1, 'asc'],
    columns: [
        {data: 'DT_RowIndex', orderable:false, searchable: false, className: 'center-align'},
        {data: 'berita_acara_no', name: 'berita_acara_no', className: 'detail'},
        {data: 'date_of_receipt', name: 'date_of_receipt', className: 'detail'},
        {data: 'expedition_code', name: 'expedition_code', className: 'detail'},
        {data: 'driver_name', name: 'driver_name', className: 'detail'},
        {data: 'vehicle_number', name: 'vehicle_number', className: 'detail'},
        {data: 'action', className: 'center-align', searchable: false, orderable: false},
    ]
  });

  dtdatatable.on('click', '.btn-select', function(event) {
      event.preventDefault();
      /* Act on the event */
      // select row-copier row, clone it, and append to second table body
      var tr = $(this).closest("tr").clone();
      tr.find(".btn-select").text("Remove");
      $("#data-table-detail-berita-acara tbody").append(tr);
    });

  $("input#global_filter").on("keyup click", function () {
    filterGlobal();
  });

  // Custom search
  function filterGlobal() {
      table.search($("#global_filter").val(), $("#global_regex").prop("checked"), $("#global_smart").prop("checked")).draw();
  }

  // var dtdatatable = $('#data-table-detail-berita-acara').DataTable({
  //   serverSide: false,
  //   scrollX: true,
  //   responsive: true,
  //   paging: false,
  //   order: [1, 'asc'],
  // });
</script>
@endpush
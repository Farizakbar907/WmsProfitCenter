@extends('layouts.materialize.index')

@section('content')
<div class="row">

    @component('layouts.materialize.components.title-wrapper')
        <div class="row">
            <div class="col s12 m6">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Master Gate</span></h5>
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active">Master Gate</li>
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
                <!---- Button Modal Add ----->
                <a class="btn btn-large waves-effect waves-light btn-add modal-trigger" href="#modal-add">New Gate</a>
                <!-- <button class="btn btn-large waves-effect waves-light btn-add" type="submit" name="action">New Gate</button> -->
              </div>
            </div>
            <div class="col s12 m3">
            </div>
        </div>
    @endcomponent
    
    <div class="col s12">
        <div class="container">
            <div class="section">
                <div class="card">
                    <div class="card-content p-0">
                        <div class="section-data-tables"> 
                          <table id="data-table-simple" class="display" width="100%">
                              <thead>
                                  <tr>
                                    <th data-priority="1" width="30px">No.</th>
                                    <th>GATE</th>
                                    <th>DESCRIPTION</th>
                                    <th>AREA</th>
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
            </div>
        </div>
        <div class="content-overlay"></div>
    </div>
</div>
@endsection

@push('page-modal')
<!-- Modal Structure -->
<div id="modal-add" class="modal">
  <form id="form-kelas" onsubmit="return false;">
  <div class="modal-content">
    <h4>New Gate</h4>
    @csrf
    <input type="hidden" name="id">
    <div class="row">
      <div class="input-field col s12">
        <input id="number" type="text" class="validate" name="gate_number" required>
        <label for="number">Gate Number</label>
      </div>
      <div class="input-field col s12">
        <input id="description" type="text" class="validate" name="description" required>
        <label for="description">Description</label>
      </div>
      <div class="input-field col m6 s12">
        <select>
            <option value="" disabled selected>-- Select --</option>
            <option value="1">KARAWANG</option>
            <option value="2">SURABAYA HUB</option>
            <option value="3">SWADAYA</option>
        </select>
        <label>Area</label>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="submit" class="modal-action waves-effect waves-green btn-flat">Save</button>
    <span class="modal-action modal-close waves-effect waves-green btn-flat">Cancel</span>
  </div>
  </form> 
</div>

<!-- Modal Structure -->
<div id="modal-edit" class="modal">
  <form id="form-kelas-edit" onsubmit="return false;">
  <div class="modal-content">
    <h4>Edit Kelas</h4>
    
  </div>
  <div class="modal-footer">
    <span class="modal-action modal-close waves-effect waves-green btn-flat">Save</span>
    <button type="submit" class="modal-action waves-effect waves-green btn-flat">Cancel</button>
  </div>
  </form>
</div>
@endpush

@push('script_js')
<script type="text/javascript">
    var table = $('#data-table-simple').DataTable({
      "responsive": true,
    });
   $("input#global_filter").on("keyup click", function () {
      filterGlobal();
   });
  // Custom search
   function filterGlobal() {
      table.search($("#global_filter").val(), $("#global_regex").prop("checked"), $("#global_smart").prop("checked")).draw();
   }

</script>
@endpush
@extends('layouts.materialize.index')

@section('content')
<div class="row">

    @component('layouts.materialize.components.title-wrapper')
        <div class="row">
            <div class="col s12 m6">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Create Incoming Import/OEM</span></h5>
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('incoming-import-oem') }}">Incoming Import/OEM</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
            <div class="col s12 m2"></div>
            <div class="col s12 m4">
              <div class="display-flex">
                <!---- Search ----->
                <div class="app-wrapper mr-2">
                  <div class="datatable-search">
                    <select>
                      <option value="" disabled>-- Select Area --</option>
                      <option value="1" selected>KARAWANG</option>
                      <option value="2">SURABAYA HUB</option>
                      <option value="3">SWADAYA</option>
                    </select>
                  </div>
                </div>
                <!---- Button Back ----->
                <a class="btn btn-large waves-effect waves-light indigo" href="{{ url('incoming-import-oem') }}">Back</a>
              </div>
            </div>
        </div>
    @endcomponent
    
    <div class="col s12">
      <div class="container">
        <div class="section">
          <div class="card">
            <div class="card-content">
              <h4 class="card-title">INPUT INCOMING IMPORT/OEM/OTHERS</h4>
              <hr>
              <form class="form-table">
                <table width="100%">
                  <tr>
                    <td width="25%">Arrival No</td>
                    <td width="25%">
                      <div class="input-field col s12">
                        <input id="first_name" type="text" class="validate" disabled>
                      </div>
                    </td>
                    <td width="50%" colspan="2">
                      <div class="input-field col s12">
                        <div class="row">
                          <div class="col s12 m4">
                            <label>
                              <input name="group1" type="radio"/>
                              <span>IMPORT</span>
                            </label>
                          </div>
                          <div class="col s12 m4">
                            <label>
                              <input name="group1" type="radio"/>
                              <span>OEM</span>
                            </label>
                          </div>
                          <div class="col s12 m4">
                            <label>
                              <input name="group1" type="radio" checked/>
                              <span>OTHERS</span>
                            </label>
                          </div>
                        </div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>PO</td>
                    <td>
                      <div class="input-field col s12">
                        <input id="first_name" type="text" class="validate" required>
                      </div>
                    </td>
                    <td>Vendor Name</td>
                    <td>
                      <div class="input-field col s12">
                        <select required="">
                          <option value="0" selected>Select Vendor Name</option>
                          <option value="1">BIMA GREEN ENERGI, PT.</option>
                          <option value="2">DAEWOO ELECTRONICS (M) SDN.BHD.</option>
                          <option value="3">Option 3</option>
                        </select>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Invoice No</td>
                    <td>
                      <div class="input-field col s12">
                        <input id="first_name" type="text" class="validate">
                      </div>
                    </td>
                    <td>Actual Arrive Date</td>
                    <td>
                      <div class="input-field col s12">
                        <input id="first_name" type="text" class="validate datepicker">
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>No GR SAP</td>
                    <td>
                      <div class="input-field col s12">
                        <input id="first_name" type="text" class="validate">
                      </div>
                    </td>
                    <td>Expedition Name</td>
                    <td>
                      <div class="input-field col s12">
                        <input id="first_name" type="text" class="validate">
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Document Date</td>
                    <td>
                      <div class="input-field col s12">
                        <input id="first_name" type="text" class="validate datepicker">
                      </div>
                    </td>
                    <td>Container No</td>
                    <td>
                      <div class="input-field col s12">
                        <input id="first_name" type="text" class="validate">
                    </td>
                  </tr>
                </table>
                {!! get_button_save() !!}
              </form>
            </div>
            <div class="card-content">
              <!-- Incoming Detail -->
              <h4 class="card-title">Incoming Detail</h4>
              <hr>
              <form class="form-table">
                <table></table>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection

@push('script_js')
<script type="text/javascript">
  $('.collapsible').collapsible({
        accordion:true
    });
</script>
@endpush
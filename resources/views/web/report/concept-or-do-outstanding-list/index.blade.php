@extends('layouts.materialize.index')
{{-- @include('admin.materi.modal_form_materi') --}}

@section('content')
<div class="row">

    @component('layouts.materialize.components.title-wrapper')
        <div class="row">
            <div class="col s12 m4 l4">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Concept or DO Outstanding List</span></h5>
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active">Concept or DO Outstanding List</li>
                </ol>
            </div>
            <div class="col s12 m4 l4">
                <!---- Search ----->
                <div class="app-wrapper">
                  <div class="datatable-search">
                    <select id="area_filter">
                      <option>-Select Area-</option>
                      <option>ALL</option>
                      <option>KARAWANG</option>
                      <option>SURABAYA HUB</option>
                      <option>SWADAYA</option>
                    </select>
                  </div>
                </div>
                <!-- end search -->
            </div>
            <div class="col s12 m4 l4">
                <!---- Search ----->
                <div class="app-wrapper">
                  <div class="datatable-search">
                    <select id="area_filter">
                      <option>-Select Branch-</option>
                      <option>ALL</option>
                      <option>KARAWANG</option>
                      <option>SURABAYA HUB</option>
                      <option>SWADAYA</option>
                    </select>
                  </div>
                </div>
                <!-- end search -->
            </div>
        </div>
    @endcomponent
    
    <div class="col s12">
        <div class="container">
            <div class="section">
                <div class="card">
                    <div class="card-content p-0">
                        <form class="form-table">
                            <table>
                              {{-- <tr>
                                <td>Area</td>
                                <td>
                                  <div class="input-field col s12">
                                    <select>
                                      <option value="" disabled selected>-- Select Branch --</option>
                                      <option>[HYP]PT. SEID HQ JKT</option>
                                      <option>[JKT]PT. SEID CAB. JAKARTA</option>
                                      <option>[JF]PT. SEID CAB. JAKARTA</option>
                                    </select>
                                  </div>
                                </td>
                              </tr>
                              <tr>
                                <td>Branch</td>
                                <td><div class="input-field col s12">
                                  <select required>
                                    <option value="" disabled selected>-- Please Select Location --</option>
                                    <option value="1">1001-YP-1st Class</option>
                                    <option value="2">1060-HYP-Return All</option>
                                    <option value="3">1090-HYP-Intransit BR</option>
                                  </select>
                                </div></td>
                              </tr> --}}
                              
                              <tr>
                                <td>Shipment No</td>
                                <td><div class="input-field col s12">
                                  <input id="model" type="text" class="validate" name="model" required>
                                </div></td>
                              </tr>
                              <tr>
                                <td>Do NO</td>
                                <td><div class="input-field col s12">
                                  <input id="aqty" type="text" class="validate " name="aqty" disabled>
                                </div></td>
                              </tr>
                              <tr>
                                <td>Expediotion</td>
                                <td>
                                  <div class="input-field col s12">
                                    <select>
                                      <option value="" disabled selected>-All-</option>
                                      <option value="1">JAKARTA-JEMBER</option>
                                      <option value="2">JAKARTA-KARAWANG</option>
                                      <option value="3">JAKARTA-KEDIRI</option>
                                    </select>
                                  </div>
                                </td>
                              </tr>
                              <tr>
                                <td>Upload Concept Date</td>
                                <td>
                                  <div class="input-field col s6">
                                    <div class="col s3 m2 label">
                                      From
                                    </div>
                                    <div class="col s9 m10">
                                      <input placeholder="" id="first_name" type="text" class="validate datepicker" readonly>
                                    </div>
                                  </div>
                                  <div class="input-field col s6">
                                    <div class="col s3 m2 label">
                                      To
                                    </div>
                                    <div class="col s9 m10">
                                      <input placeholder="" id="first_name" type="text" class="validate datepicker" readonly>
                                    </div>
                                  </div>
                                </td>
                              </tr>
                              <tr>
                                <td>Vehicle Type</td>
                                <td><div class="input-field col s12">
                                  <textarea id="textarea2" class="materialize-textarea" required></textarea>
                                </div></td>
                              </tr>
                            </table>
                            <div class="input-field col s12">
                              <button type="submit" class="waves-effect waves-light indigo btn">Submit</button>
                            </div>
                          </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-overlay"></div>
    </div>
</div>
@endsection

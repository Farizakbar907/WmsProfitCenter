@extends('layouts.materialize.index')
{{-- @include('admin.materi.modal_form_materi') --}}

@section('content')
<div class="row">

    @component('layouts.materialize.components.title-wrapper')
        <div class="row">
            <div class="col s12">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Report KPi Expeditions</span></h5>
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active">Report KPi Expeditions</li>
                </ol>
            </div>
        </div>
    @endcomponent
    
    <div class="col s12">
        <div class="container">
            <div class="section">
                <div class="card">
                    <div class="card-content">
                        <form class="form-table">
                            <table>
                                <tr>
                                    <td>Area</td>
                                    <td>
                                      <div class="input-field col s12">
                                        <select>
                                          <option value="" disabled selected>-Select Area-</option>
                                          <option value="1">KARAWANG</option>
                                          <option value="2">SURABAYA HUB</option>
                                          <option value="3">SWADAYA</option>
                                        </select>
                                      </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Periode</td>
                                    <td>
                                      <div class="input-field col s12">
                                        <input type="text" class="validate datepicker">
                                      </div>
                                    </td>
                                </tr>
                            </table>
                            <div class="input-field col s12">
                                <button type="submit" class="waves-effect waves-light indigo btn">Submit</button>
                              </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content center-align">
                        <div><p> FLeet CApability - Area :KARAWANG(2020-05-01 09:51:40)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-overlay"></div>
    </div>
</div>
@endsection

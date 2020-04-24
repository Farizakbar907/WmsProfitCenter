@extends('layouts.materialize.index')
{{-- @include('admin.materi.modal_form_materi') --}}

@section('content')
<div class="row">

    @component('layouts.materialize.components.title-wrapper')
        <div class="row">
            <div class="col s12 m8 l8">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Standby Driver List</span></h5>
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active">Standby Driver List</li>
                </ol>
            </div>
            <div class="col s12 m4 l4">
                {{-- <!---- Search ----->
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
                <!-- end search --> --}}
            </div>
        </div>
    @endcomponent
    
    <div class="col s12">
        <div class="container">
            <div class="section">
                <div class="card large">
                    <div class="card-content">
                        <form class="form-table">
                            <table>
                              <tr>
                                <td>Area</td>
                                <td>
                                  <div class="input-field col s12">
                                    <select class="select2 browser-default">
                                      <option>- All Area -</option>
                                      <option>KARAWANG</option>
                                      <option>SURABAYA HUB</option>
                                      <option>SWADAYA</option>
                                      
                                    </select>
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
            </div>
        </div>
        <div class="content-overlay"></div>
    </div>
</div>
@endsection

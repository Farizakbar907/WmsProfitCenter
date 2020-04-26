@extends('layouts.materialize.index')
{{-- @include('admin.materi.modal_form_materi') --}}

@section('content')
<div class="row">

    @component('layouts.materialize.components.title-wrapper')
        <div class="row">
            <div class="col s12">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Serial Number Trace</span></h5>
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active">Serial Number Trace</li>
                </ol>
            </div>
        </div>
    @endcomponent
    
    <div class="col s12">
        <div class="container">
            <div class="section">
                <div class="card">
                    <div class="card-content ">
                        <form class="form-table">
                               <table>
                                   
                                 <tr>
                                    <td>MODEL</td>
                                    <td><div class="input-field col s12">
                                       <input id="" type="text" class="validate" name="" required>
                                     </div></td>
                                 </tr>
                                 <tr>
                                    <td>SERIAL NUMBER</td>
                                    <td><div class="input-field col s12">
                                       <input id="" type="text" class="validate" name="" required>
                                     </div></td>
                                 </tr>
                                 <tr>
                                    <td></td>
                                    <td><div class="input-field col s12">
                                        <button type="submit" class="waves-effect waves-light indigo btn">Submit</button>
                                      </div></td>
                                 </tr>
                               </table>
                              
                               
                            </form>
                      </div>
                </div>
            </div>
        </div>
        <div class="content-overlay"></div>
    </div>
</div>
@endsection

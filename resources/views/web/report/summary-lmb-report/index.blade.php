@extends('layouts.materialize.index')
{{-- @include('admin.materi.modal_form_materi') --}}

@section('content')
<div class="row">

    @component('layouts.materialize.components.title-wrapper')
        <div class="row">
            <div class="col s12">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Summary LMB Report</span></h5>
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active">Summary LMB Report</li>
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
                                       <td>Branch</td>
                                       <td>
                                         <div class="input-field col s4">
                                           <select name="" id="" required>
                                             <option value="" disabled selected >-Select Area-</option>
                                             <option value="1" >KARAWANG</option>
                                             <option value="2">SURABAYA HUB</option>
                                             <option value="3">SWADAYA</option>
                                              
                                           </select>
       
                                         </div>
                                       </td>
                                     </tr>
                                 <tr>
                                   <td>Picking List Date</td>
                                   <td>
                                     <div class="input-field col s6">
                                       <div class="col s3 m2 label">
                                         From
                                       </div>
                                       <div class="col s9 m10">
                                         <input placeholder="" id="first_name" type="text" class="validate datepicker" required>
                                       </div>
                                     </div>
                                     <div class="input-field col s6">
                                       <div class="col s3 m2 label">
                                         To
                                       </div>
                                       <div class="col s9 m10">
                                         <input placeholder="" id="first_name" type="text" class="validate datepicker" required >
                                       </div>
                                     </div>
                                   </td>
                                 </tr>
                                 <tr>
                                    <td>LMB Date</td>
                                   <td>
                                     <div class="input-field col s6">
                                       <div class="col s3 m2 label">
                                         From
                                       </div>
                                       <div class="col s9 m10">
                                         <input placeholder="" id="first_name" type="text" class="validate datepicker" >
                                       </div>
                                     </div>
                                     <div class="input-field col s6">
                                       <div class="col s3 m2 label">
                                         To
                                       </div>
                                       <div class="col s9 m10">
                                         <input placeholder="" id="first_name" type="text" class="validate datepicker" >
                                       </div>
                                     </div>
                                   </td>
                                 </tr>
                                 <tr>
                                     <td>Picking List NO.</td>
                                     <td><div class="input-field col s12">
                                        <input id="" type="text" class="validate" name="" >
                                      </div></td>
                                 </tr>
                                 <tr>
                                    <td>Model</td>
                                    <td><div class="input-field col s12">
                                       <input id="" type="text" class="validate" name="" >
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

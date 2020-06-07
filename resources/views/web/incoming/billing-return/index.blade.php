@extends('layouts.materialize.index')
{{-- @include('admin.materi.modal_form_materi') --}}

@section('content')
<div class="row">

    @component('layouts.materialize.components.title-wrapper')
        <div class="row">
            <div class="col s12 m4">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Billing Return</span></h5>
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active">Billing Return</li>
                </ol>
            </div>
        </div>
    @endcomponent
    
    @include('web.incoming.billing-return._pending_billing_return_branch')
    @include('web.incoming.billing-return._return_billing_branch')
        
        <div class="content-overlay"></div>
    </div>
</div>
@endsection

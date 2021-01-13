<form class="form-table" id="form-manifest">
    <input type="hidden" name="driver_register_id" value="{{ !empty($lmbHeader) ? $lmbHeader->driver_register_id : ''}}">
    <table class="mb-1">
        <tr>
            <td width="20%">Manifest No.</td>
            <td width="30%">
                <div class="input-field col s8">
                    <input 
                        type="text" 
                        class="validate" 
                        name="do_manifest_no" 
                        value="{{!empty($manifestHeader) ? $manifestHeader->do_manifest_no : ''}}"
                        readonly 
                        />
              </div>
              <div class="col s4 mt-3">{{!empty($manifestHeader) ? $manifestHeader->manifest_type : ''}}</div>
            </td>
            <td width="20%">Manifest Date</td>
            <td width="30%">
                <div class="input-field col s12">
                    <input 
                        type="text" 
                        class="validate" 
                        name="do_manifest_date" 
                        value="{{date('Y-m-d')}}"
                        readonly 
                        />
              </div>
            </td>
        </tr>
        <tr>
            <td width="20%">Remarks 1</td>
            <td width="30%">
                <div class="input-field col s12">
                    <input 
                        type="text" 
                        class="validate" 
                        name="seal_no" 
                        value="{{ !empty($manifestHeader) ? $manifestHeader->seal_no : '' }}"
                        />
              </div>
            </td>
            <td width="20%">Checker</td>
            <td width="30%">
                <div class="input-field col s12">
                    <input 
                        type="text" 
                        class="validate" 
                        name="checker" 
                        value="{{ !empty($manifestHeader) ? $manifestHeader->checker : '' }}"
                        />
              </div>
            </td>
        </tr>
        <tr>
            <td width="20%">Remarks 2</td>
            <td width="30%" colspan="3">
                <div class="input-field col s12">
                    <input 
                        type="text" 
                        class="validate" 
                        name="pdo_no" 
                        value="{{ !empty($manifestHeader) ? $manifestHeader->pdo_no : '' }}"
                        />
              </div>
            </td>
        </tr>
    </table>
    {!! get_button_save('Save', 'btn-save') !!}
    {!! get_button_delete('Delete', 'btn-delete hide') !!}
    {!! get_button_print('#!', 'Print', 'btn-print hide') !!}
    {!! get_button_view('#1', 'Submit', 'btn-submit hide') !!}
    {!! get_button_cancel(url('manifest-as'), 'Back', '') !!}
</form>

{{-- Load Modal Print --}}
@include('layouts.materialize.components.modal-print', [
  'title' => 'Print Manifest',
  'url' => 'manifest-as/' . (!empty($manifestHeader) ? $manifestHeader->do_manifest_no : '') . '/export',
  'trigger' => '.btn-print'
  ])

@push('script_js')
<script type="text/javascript">
    jQuery(document).ready(function($) {
      $('#form-manifest [name="city_code"]').select2({
        placeholder: '-- Select Destination City --',
        allowClear: true,
        ajax: get_select2_ajax_options('{{url('/master-expedition/select2-expedition-destination-city')}}', {expedition_code: '{{ !empty($lmbHeader) ? $lmbHeader->expedition_code : ""}}'})
      })

        $('#form-manifest [name="city_code"]').change(function(event) {
            var data = $(this).select2('data')[0];
          $('#form-manifest [name="city_name"]').val(data == undefined ? '' : data.text);
        });
    });
</script>
@endpush
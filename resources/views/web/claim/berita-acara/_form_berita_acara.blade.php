<form class="form-table" id="form-berita-acara">
    <table>
        <tr>
            <td>No. Berita Acara</td>
            <td>
                <input id="berita_acara_no" name="berita_acara_no" type="text" class="validate" value="{{old('berita_acara_no', !empty($beritaAcara) ? $beritaAcara->berita_acara_no : $beritaAcaraNo)}}" readonly>
            </td>
        </tr>
        <tr>
            <td>Tanggal Terima</td>
            <td>
                <input id="date_of_receipt" name="date_of_receipt" type="text" class="validate" value="{{old('date_of_receipt', !empty($beritaAcara) ? $beritaAcara->date_of_receipt : $dateOfReceipt)}}" readonly>
            </td>
        </tr>
        <tr>
            <td>Expedition</td>
            <td>
                <select id="expedition_code" name="expedition_code" class="select2-data-ajax browser-default" required>
                </select>
                <input type="hidden" name="expedition_name">
            </td>
        </tr>
        <tr>
            <td>Driver</td>
            <td>
                <select id="driver_name" name="driver_name" class="select2-data-ajax browser-default" required>
                </select>
            </td>
        </tr>
        <tr>
            <td>Vehicle Number</td>
            <td>
                <select id="vehicle_number" name="vehicle_number" class="select2-data-ajax browser-default" required>
                </select>
            </td>
        </tr>
        <tr>
            <td>DO Manifest</td>
            <td>
                <div class="file-field input-field">
                  <div class="btn">
                    <span>Browse</span>
                    <input type="file" name="file-do-manifest">
                  </div>
                  <div class="file-path-wrapper">
                    <input class="file-path validate" type="text">
                  </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>Internal DO / DO</td>
            <td>
                <div class="file-field input-field">
                  <div class="btn">
                    <span>Browse</span>
                    <input type="file" name="file-internal-do">
                  </div>
                  <div class="file-path-wrapper">
                    <input class="file-path validate" type="text">
                  </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>LMB</td>
            <td>
                <div class="file-field input-field">
                  <div class="btn">
                    <span>Browse</span>
                    <input type="file" name="file-lmb">
                  </div>
                  <div class="file-path-wrapper">
                    <input class="file-path validate" type="text">
                  </div>
                </div>
            </td>
        </tr>
        
    </table>
    {!! get_button_save() !!}
    {!! get_button_cancel(url('berita-acara'), 'Back') !!}
</form>

@push('script_js')
<script type="text/javascript">
  set_select2_expedition()
  set_select2_driver()
  set_select2_vehicle_number()

  $('#form-berita-acara [name="expedition_code"]').change(function(event) {
      /* Act on the event */
      var data = $(this).select2('data')[0]
      set_select2_driver({expedition_code: $(this).val()})
      set_select2_vehicle_number({expedition_code: $(this).val()})
      set_select2_value('#form-berita-acara [name="driver_name"]', '', '')
      set_select2_value('#form-berita-acara [name="vehicle_number"]', '', '')
      $('#form-berita-acara [name="expedition_name"]').val(data.text)
   });

  function set_select2_expedition(){
        $('#form-berita-acara [name="expedition_code"]').select2({
            placeholder: '-- Select Expedition --',
            ajax: get_select2_ajax_options('/master-expedition/select2-active-expedition')
        })
    }

  function set_select2_driver(filter = {expedition_code: ''}){
        $('#form-berita-acara [name="driver_name"]').select2({
            placeholder: '-- Select Driver --',
            ajax: get_select2_ajax_options('/master-driver/select2-driver-expedition', filter)
          })
    }

    function set_select2_vehicle_number(filter = {expedition_code: ''}){
        $('#form-berita-acara [name="vehicle_number"]').select2({
            placeholder: '-- Select Vehicle Number --',
            ajax: get_select2_ajax_options('/master-vehicle-expedition/select2-vehicle-number', filter)
          })
    }
</script>
@endpush
<form class="form-table" id="form-storage-master">
	<table>
		<tr>
			<td>Branch</td>
			<td>
				<div class="input-field col s12">
			    <select id="branch"
                class="select2-data-ajax browser-default select-branch"
                name="branch" 
                required>
                    <option></option>
			        <!-- <option value="" disabled selected>-- Select --</option>
			        <option value="1">10-HYP-PT. SEID HQ JKT</option> -->
			    </select>
			  </div>
			</td>
		</tr>
		<tr>
			<td>Storage Code</td>
			<td>
				<div class="input-field col s12">
					<input id="code" type="text" class="validate" name="code" required>
			  </div>
			</td>
		</tr>
		<tr>
			<td>Storage Type</td>
			<td>
				<div class="input-field col s12">
			    <select required="">
			        <option value="" disabled selected>-- Select --</option>
			        <option value="1">1st Class</option>
			        <option value="2">Return All</option>
			        <option value="3">2nd Class Insurance</option>
			    </select>
			  </div>
			</td>
		</tr>
		<tr>
			<td>Total Pallate</td>
			<td>
				<div class="input-field col s12">
				    <input id="total" type="number" class="validate" name="total" required>
			  </div>
			</td>
		</tr>
		<tr>
			<td>Used Space</td>
			<td>
				<input id="used" type="number" class="validate" name="used">
			</td>
		</tr>
		<tr>
			<td>Space WH</td>
			<td>
				<input id="wh" type="number" class="validate" name="wh">
			</td>
		</tr>
		<tr>
			<td>Hand Pallet Space</td>
			<td>
				<input id="space" type="number" class="validate" name="space">
			</td>
		</tr>
	</table>
	{!! get_button_save() !!}
    {!! get_button_cancel(url('storage-master')) !!}
</form>

@push('script_js')
<script type="text/javascript">
   jQuery(document).ready(function($) {
      // Loading branch data
      $('.select-branch').select2({
         placeholder: '-- Select --',
         ajax: get_select2_ajax_options('/master-cabang/select2-branch')
      });
   });
</script>
@endpush
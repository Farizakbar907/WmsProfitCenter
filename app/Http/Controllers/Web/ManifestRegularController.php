<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Concept;
use App\Models\DriverRegistered;
use App\Models\FreightCost;
use App\Models\LMBHeader;
use App\Models\LogManifestDetail;
use App\Models\LogManifestHeader;
use App\Models\MasterCabang;
use App\Models\MasterModel;
use App\Models\PickinglistHeader;
use DataTables;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ManifestRegularController extends Controller
{
  public function index(Request $request)
  {
    if ($request->ajax()) {
      $query = LogManifestHeader::select(
        'log_manifest_header.driver_register_id',
        'log_manifest_header.status_complete',
        DB::raw('log_manifest_header.do_manifest_no AS first_do_manifest_no'),
        DB::raw('GROUP_CONCAT(DISTINCT log_manifest_header.do_manifest_no SEPARATOR ", <br>") AS do_manifest_no'),
        DB::raw('GROUP_CONCAT(DISTINCT log_manifest_header.expedition_name SEPARATOR ", <br>") AS expedition_name'),
        DB::raw('GROUP_CONCAT(DISTINCT log_manifest_header.city_name SEPARATOR ", <br>") AS city_name'),
        DB::raw('GROUP_CONCAT(DISTINCT log_manifest_header.vehicle_number SEPARATOR ", <br>") AS vehicle_number'),
        // DB::raw('COUNT(wms_pickinglist_detail.id) AS total_detail_tcs_do'),
        // DB::raw('COUNT(log_manifest_detail.id) AS countManifestDO'),
        // DB::raw('SUM(IF(log_manifest_detail.status_confirm = 0, 1, 0)) AS countUnconfirmDetail')
		//'wms_pickinglist_header.picking_no',
      )
        // ->where('city_name', '<>', 'Ambil Sendiri')
        // ->leftjoin('log_manifest_detail', 'log_manifest_header.do_manifest_no', '=', 'log_manifest_detail.do_manifest_no')
        // ->leftjoin('wms_pickinglist_header', 'wms_pickinglist_header.driver_register_id', '=', 'log_manifest_header.driver_register_id')
        // ->leftjoin('wms_pickinglist_detail', 'wms_pickinglist_header.id', '=', 'wms_pickinglist_detail.header_id')
        ->where('log_manifest_header.ambil_sendiri', 0)
        ->where('log_manifest_header.area', $request->input('area'))
        ->groupBy('log_manifest_header.driver_register_id');



      $datatables = DataTables::of($query)
        ->addIndexColumn() //DT_RowIndex (Penomoran)
		->filter(function($instance) use ($request){
			if(!empty($request->input('search'))){
				$value = Str::lower($request->input('search')['value']);
				if($value !== ''){
					//$instance->leftjoin('wms_pickinglist_header', 'wms_pickinglist_header.driver_register_id', '=', 'log_manifest_header.driver_register_id')
					$instance->where(function($query) use($value){
						$query->orWhereRaw('LOWER(`log_manifest_header`.`do_manifest_no`) LIKE "%'. $value .'%"')
							->orWhereRaw('LOWER(`log_manifest_header`.`expedition_name`) LIKE "%'. $value .'%"')
							->orWhereRaw('LOWER(`log_manifest_header`.`city_name`) LIKE "%'. $value .'%"')
							->orWhereRaw('LOWER(`log_manifest_header`.`vehicle_number`) LIKE "%'. $value .'%"');
							//->orWhereRaw('LOWER(`wms_pickinglist_header`.`picking_no`) LIKE "%'. $value .'%"');
						$pickinglists = PickinglistHeader::select('driver_register_id')->where('picking_no', 'like', '%'. $value .'%')->get();
						foreach ($pickinglists as $key => $header) {
							$query->orWhere('driver_register_id',$header->driver_register_id);
						}
					});
				}
			}
		})
        ->addColumn('picking_no', function ($data) {
          return $data->picking->picking_no;
		  //return $data->picking_no;
        })
        ->addColumn('status', function ($data) {
          // return LogManifestHeader::status($data);
          return $data->status();
        })
        ->addColumn('action', function ($data) {
          $action = '';
          $action .= ' ' . get_button_view(url('manifest-regular/' . $data->first_do_manifest_no . '/edit'), 'View');
          return $action;
        })
        ->rawColumns(['picking_no', 'do_manifest_no', 'expedition_name', 'city_name', 'vehicle_number', 'status', 'action']);

      return $datatables->make(true);
    }

    return view('web.outgoing.manifest-regular.index');
  }

  public function listDO(Request $request, $do_manifest_no)
  {
    if ($request->ajax()) {
      $query = LogManifestDetail::listDO($do_manifest_no);

      $datatables = DataTables::of($query)
        ->addIndexColumn() //DT_RowIndex (Penomoran)
        ->addColumn('desc', function ($data) {
          return $data->getDesc();
        })
        ->editColumn('status_confirm', function ($data) {
          return $data->status();
        })
        ->addColumn('action', function ($data) {
          $action = '';
          if (($data->manifest_type == 'LCL') || (! $data->status_complete)) {
            $action .= ' ' . get_button_delete();
          }
          return $action;
        })
        ->rawColumns(['do_status', 'action']);

      return $datatables->make(true);
    }
  }

  public function newManifestLCL(Request $request)
  {
    $request->validate([
      'do_manifest_no' => 'required',
    ]);

    $oldManifestHeader = LogManifestHeader::findOrFail($request->input('do_manifest_no'));

    $manifestHeader = new LogManifestHeader;

    // DO MANIFEST NO => KODEAREA-ymd-URUT
    $now = date('Y-m-d H:i:s', strtotime('-7 hours'));
    $prefix = auth()->user()->area_data->code . '-' . date('ymd', strtotime($now));
    // $prefix = auth()->user()->area_data->code . '-' . date('ymd');

    $prefix_length = strlen($prefix);
    $max_no        = DB::select('SELECT MAX(SUBSTR(do_manifest_no, ?)) AS max_no FROM log_manifest_header WHERE SUBSTR(do_manifest_no,1,?) = ? ', [$prefix_length + 2, $prefix_length, $prefix])[0]->max_no;
    $max_no        = str_pad($max_no + 1, 3, 0, STR_PAD_LEFT);

    $do_manifest_no = $prefix . '-' . $max_no;

    $urut_manifest = LogManifestHeader::selectRaw('MAX(urut_manifest) AS urut_manifest')->where('driver_register_id', $oldManifestHeader->driver_register_id)->first()->urut_manifest;

    $urut_manifest++;

    $manifestHeader->driver_register_id          = $oldManifestHeader->driver_register_id;
    $manifestHeader->do_manifest_no              = $do_manifest_no;
    $manifestHeader->expedition_code             = $request->input('expedition_code');
    $manifestHeader->expedition_name             = $request->input('expedition_name');
    $manifestHeader->driver_id                   = $request->input('driver_id');
    $manifestHeader->driver_name                 = $request->input('driver_name');
    $manifestHeader->vehicle_number              = $request->input('vehicle_number');
    $manifestHeader->vehicle_code_type           = $request->input('vehicle_code_type');
    $manifestHeader->vehicle_description         = $request->input('vehicle_description');
    // $manifestHeader->do_manifest_date            = $request->input('do_manifest_date');
    $manifestHeader->do_manifest_date            = date('Y-m-d', strtotime($now));
    $manifestHeader->do_manifest_time            = date('Y-m-d H:i:s');
    $manifestHeader->destination_number_driver   = $request->input('destination_number_driver');
    $manifestHeader->destination_name_driver     = $request->input('destination_name_driver');
    $manifestHeader->city_code                   = $request->input('city_code');
    $manifestHeader->city_name                   = $request->input('city_name');
    $manifestHeader->container_no                = $request->input('container_no');
    $manifestHeader->seal_no                     = $request->input('seal_no');
    $manifestHeader->checker                     = $request->input('checker');
    $manifestHeader->pdo_no                      = $request->input('pdo_no');
    $manifestHeader->area                        = auth()->user()->area;
    $manifestHeader->status_complete             = 0;
    $manifestHeader->urut_manifest               = $urut_manifest;
    $manifestHeader->tcs                         = 0;
    $manifestHeader->ambil_sendiri               = 0;
    $manifestHeader->ritase                      = 0;
    $manifestHeader->cbm                         = 0;
    $manifestHeader->manifest_return             = 0;
    $manifestHeader->manifest_type               = 'LCL';
    $manifestHeader->status_inv_recieve          = 0;
    $manifestHeader->have_lcl                    = 1;
    $manifestHeader->lcl_from_driver_register_id = $oldManifestHeader->driver_register_id;
    $manifestHeader->lcl_from_manifest_no        = $oldManifestHeader->do_manifest_no;
    $manifestHeader->lcl_created_date            = date('Y-m-d H:i:s');
    $manifestHeader->lcl_created_by              = auth()->user()->id;
    $manifestHeader->have_resend                 = 0;
    $manifestHeader->manifest_resend             = 0;
    $manifestHeader->r_from_manifest             = $request->input('r_from_manifest');
    $manifestHeader->r_driver_register_id        = $request->input('r_driver_register_id');
    $manifestHeader->r_create_date               = $request->input('r_create_date');
    $manifestHeader->r_create_by                 = $request->input('r_create_by');

    $freightCost = FreightCost::where('area', $manifestHeader->area)
      ->where('vehicle_code_type', $manifestHeader->vehicle_code_type)
      ->where('expedition_code', $manifestHeader->expedition_code)
      ->where('city_code', $manifestHeader->city_code)
      ->first();

    if (empty($freightCost)) {
      return sendError('Freight Cost not found!');
    }

    $manifestHeader->id_freight_cost = $freightCost->id;

    $jumlahLCL = LogManifestHeader::select(DB::raw('COUNT(do_manifest_no) AS jumlahLCL'))
      ->where('driver_register_id', $oldManifestHeader->driver_register_id)
      ->where('manifest_type', 'LCL')->first()->jumlahLCL;
    $urutanLCL = $jumlahLCL + 1;

    $rs_details = [];
    foreach ($oldManifestHeader->details as $key => $value) {
      $rs_details[$key] = $value->toArray();
      unset($rs_details[$key]['id']);

      $rs_details[$key]['city_name']         = $manifestHeader->city_name;
      $rs_details[$key]['city_code']    = $manifestHeader->city_code;

      $rs_details[$key]['do_manifest_no'] = $manifestHeader->do_manifest_no;
      $rs_details[$key]['delivery_no'] = $rs_details[$key]['delivery_no'] . "L" . $urutanLCL;
      $rs_details[$key]['created_at']       = date('Y-m-d H:i:s');
      $rs_details[$key]['created_by']       = auth()->user()->id;
      $rs_details[$key]['updated_at']       = date('Y-m-d H:i:s');
      $rs_details[$key]['updated_by']       = auth()->user()->id;
    }

    try {
      DB::beginTransaction();
      LogManifestDetail::insert($rs_details);
      $manifestHeader->save();

      $oldManifestHeader->have_lcl         = 1;
      $oldManifestHeader->lcl_created_date = $manifestHeader->lcl_created_date;

      $oldManifestHeader->save();

      DB::commit();

      return sendSuccess('Manifest LCL Created', $manifestHeader);
    } catch (Exception $e) {
      DB::rollBack();
    }
  }

  public function resend(Request $request)
  {
    $request->validate([
      'do_manifest_no' => 'required',
    ]);

    $oldManifestHeader = LogManifestHeader::findOrFail($request->input('do_manifest_no'));

    $manifestHeader = new LogManifestHeader;

    // DO MANIFEST NO => KODEAREA-ymd-URUT
    $now = date('Y-m-d H:i:s', strtotime('-7 hours'));
    $prefix = auth()->user()->area_data->code . '-' . date('ymd', strtotime($now));
    // $prefix = auth()->user()->area_data->code . '-' . date('ymd');

    $prefix_length = strlen($prefix);
    $max_no        = DB::select('SELECT MAX(SUBSTR(do_manifest_no, ?)) AS max_no FROM log_manifest_header WHERE SUBSTR(do_manifest_no,1,?) = ? ', [$prefix_length + 2, $prefix_length, $prefix])[0]->max_no;
    $max_no        = str_pad($max_no + 1, 3, 0, STR_PAD_LEFT);

    $do_manifest_no = $prefix . '-' . $max_no;

    $urut_manifest = LogManifestHeader::selectRaw('MAX(urut_manifest) AS urut_manifest')->where('driver_register_id', $oldManifestHeader->driver_register_id)->first()->urut_manifest;

    $urut_manifest++;

    $manifestHeader->driver_register_id        = $oldManifestHeader->driver_register_id;
    $manifestHeader->do_manifest_no            = $do_manifest_no;
    $manifestHeader->expedition_code           = $request->input('expedition_code');
    $manifestHeader->expedition_name           = $request->input('expedition_name');
    $manifestHeader->driver_id                 = $request->input('driver_id');
    $manifestHeader->driver_name               = $request->input('driver_name');
    $manifestHeader->vehicle_number            = $request->input('vehicle_number');
    $manifestHeader->vehicle_code_type         = $request->input('vehicle_code_type');
    $manifestHeader->vehicle_description       = $request->input('vehicle_description');
    // $manifestHeader->do_manifest_date          = $request->input('do_manifest_date');
    $manifestHeader->do_manifest_date            = date('Y-m-d', strtotime($now));
    $manifestHeader->do_manifest_time          = date('Y-m-d H:i:s');
    $manifestHeader->destination_number_driver = $request->input('destination_number_driver');
    $manifestHeader->destination_name_driver   = $request->input('destination_name_driver');
    $manifestHeader->city_code                 = $request->input('city_code');
    $manifestHeader->city_name                 = $request->input('city_name');
    $manifestHeader->container_no              = $request->input('container_no');
    $manifestHeader->seal_no                   = $request->input('seal_no');
    $manifestHeader->checker                   = $request->input('checker');
    $manifestHeader->pdo_no                    = $request->input('pdo_no');
    $manifestHeader->area                      = auth()->user()->area;
    $manifestHeader->status_complete           = $oldManifestHeader->status_complete;
    $manifestHeader->urut_manifest             = $urut_manifest;
    $manifestHeader->tcs                       = 0;
    $manifestHeader->ambil_sendiri             = 0;
    $manifestHeader->ritase                    = 0;
    $manifestHeader->cbm                       = 0;
    $manifestHeader->manifest_return           = 0;
    $manifestHeader->manifest_type             = 'RESEND';
    $manifestHeader->status_inv_recieve        = 0;
    $manifestHeader->have_lcl                  = 0;
    // $manifestHeader->lcl_from_driver_register_id = $oldManifestHeader->driver_register_id;
    // $manifestHeader->lcl_from_manifest_no        = $oldManifestHeader->do_manifest_no;
    // $manifestHeader->lcl_created_date            = date('Y-m-d H:i:s');
    // $manifestHeader->lcl_created_by              = auth()->user()->id;
    $manifestHeader->have_resend          = 0;
    $manifestHeader->manifest_resend      = 1;
    $manifestHeader->r_from_manifest      = $oldManifestHeader->do_manifest_no;
    $manifestHeader->r_driver_register_id = $request->input('change_vehicle');
    $manifestHeader->r_create_date        = date('Y-m-d H:i:s');
    $manifestHeader->r_create_by          = auth()->user()->id;

    $freightCost = FreightCost::where('area', $manifestHeader->area)
      ->where('vehicle_code_type', $manifestHeader->vehicle_code_type)
      ->where('expedition_code', $manifestHeader->expedition_code)
      ->where('city_code', $manifestHeader->city_code)
      ->first();

    if (empty($freightCost)) {
      return sendError("Freight Cost not found!");
    }

    $manifestHeader->id_freight_cost = $freightCost->id;

    $rs_details = [];
    foreach ($oldManifestHeader->details as $key => $value) {
      $rs_details[$key] = $value->toArray();

      $lastChar = str_split(substr($rs_details[$key]['delivery_no'], -2));
      if ($lastChar[0] == 'R') {
        $rs_details[$key]['delivery_no'] = str_replace($lastChar[0] . $lastChar[1], $lastChar[0] . ($lastChar[1] + 1), $rs_details[$key]['delivery_no']);
      } else {
        $rs_details[$key]['delivery_no'] = $rs_details[$key]['delivery_no'] . 'R1';
      }
      unset($rs_details[$key]['id']);

      $rs_details[$key]['do_manifest_no'] = $manifestHeader->do_manifest_no;
    }

    try {
      DB::beginTransaction();
      LogManifestDetail::insert($rs_details);
      $manifestHeader->save();

      $oldManifestHeader->have_resend   = 1;
      $oldManifestHeader->r_create_date = $manifestHeader->lcl_created_date;

      LogManifestDetail::where('do_manifest_no', $oldManifestHeader->do_manifest_no)
        ->update([
          'status_confirm' => 1,
          'confirm_date'   => date('Y-m-d H:i:s'),
          'confirm_by'     => 'AUTO SYSTEM RESEND',
        ]);

      $oldManifestHeader->save();

      DB::commit();

      return sendSuccess('Manifest LCL Created', $manifestHeader);
    } catch (Exception $e) {
      DB::rollBack();
    }
  }

  public function getSelect2ResendDriver(Request $request)
  {
    $query = DriverRegistered::select(
      'tr_driver_registered.*',
      DB::raw('CONCAT(tr_driver_registered.driver_name, " - ", tr_driver_registered.expedition_name) AS text')
    )
      ->toBase()->where('tr_driver_registered.area', $request->input('area'))
      ->leftjoin('tr_vehicle_type_detail', 'tr_vehicle_type_detail.vehicle_code_type', '=', 'tr_driver_registered.vehicle_code_type')
      ->leftjoin('wms_pickinglist_header', 'wms_pickinglist_header.driver_register_id', '=', 'tr_driver_registered.id')
      ->leftjoin('tr_expedition', 'tr_expedition.code', '=', 'tr_driver_registered.expedition_code')
      ->leftjoin('log_manifest_header', 'log_manifest_header.r_driver_register_id', '=', 'tr_driver_registered.id')
      ->whereNull('log_manifest_header.driver_register_id')
      ->whereNull('wms_pickinglist_header.driver_register_id')
      ->whereNull('datetime_out');

    return get_select2_data($request, $query);
  }

  public function truckWaitingManifest(Request $request)
  {
    if ($request->ajax()) {
      $query = LMBHeader::noManifestLMBHeader()
        ->where('wms_lmb_header.expedition_code', '<>', 'AS')
        ->where('wms_lmb_header.send_manifest', 1)
        ->get();

      $datatables = DataTables::of($query)
        ->addIndexColumn() //DT_RowIndex (Penomoran)
        ->addColumn('picking_no', function ($data) {
			if($data->picking !== null) return $data->picking->picking_no;
        })
        ->addColumn('action', function ($data) {
          $action = '';
          $action .= ' ' . get_button_view(url('manifest-regular/' . $data->driver_register_id . '/create-manifest'), 'Create');
          return $action;
        })
        ->rawColumns(['do_status', 'action']);

      return $datatables->make(true);
    }
  }

  public function createManifest($lmb_id)
  {
    $data['lmbHeader'] = LMBHeader::find($lmb_id);

    return view('web.outgoing.manifest-regular.create', $data);
  }

  public function store(Request $request)
  {
    $request->validate([
      'driver_register_id' => 'required',
    ]);

    $manifestHeader = new LogManifestHeader;

    // DO MANIFEST NO => KODEAREA-ymd-URUT
    $now = date('Y-m-d H:i:s', strtotime('-7 hours'));
    $prefix = auth()->user()->area_data->code . '-' . date('ymd', strtotime($now));

    $prefix_length = strlen($prefix);
    $max_no        = DB::select('SELECT MAX(SUBSTR(do_manifest_no, ?)) AS max_no FROM log_manifest_header WHERE SUBSTR(do_manifest_no,1,?) = ? ', [$prefix_length + 2, $prefix_length, $prefix])[0]->max_no;
    $max_no        = str_pad($max_no + 1, 3, 0, STR_PAD_LEFT);

    $do_manifest_no = $prefix . '-' . $max_no;

    $urut_manifest = LogManifestHeader::selectRaw('MAX(urut_manifest) AS urut_manifest')->where('driver_register_id', $request->input('driver_register_id'))->first()->urut_manifest;

    $urut_manifest++;

    $manifestHeader->driver_register_id          = $request->input('driver_register_id');
    $manifestHeader->do_manifest_no              = $do_manifest_no;
    $manifestHeader->expedition_code             = $request->input('expedition_code');
    $manifestHeader->expedition_name             = $request->input('expedition_name');
    $manifestHeader->driver_id                   = $request->input('driver_id');
    $manifestHeader->driver_name                 = $request->input('driver_name');
    $manifestHeader->vehicle_number              = $request->input('vehicle_number');
    $manifestHeader->vehicle_code_type           = $request->input('vehicle_code_type');
    $manifestHeader->vehicle_description         = $request->input('vehicle_description');
    // $manifestHeader->do_manifest_date            = $request->input('do_manifest_date');
    $manifestHeader->do_manifest_date            = date('Y-m-d', strtotime($now));
    $manifestHeader->do_manifest_time            = date('Y-m-d H:i:s');
    $manifestHeader->destination_number_driver   = $request->input('destination_number_driver');
    $manifestHeader->destination_name_driver     = $request->input('destination_name_driver');
    $manifestHeader->city_code                   = $request->input('city_code');
    $manifestHeader->city_name                   = $request->input('city_name');
    $manifestHeader->container_no                = $request->input('container_no');
    $manifestHeader->seal_no                     = $request->input('seal_no');
    $manifestHeader->checker                     = $request->input('checker');
    $manifestHeader->pdo_no                      = $request->input('pdo_no');
    $manifestHeader->area                        = auth()->user()->area;
    $manifestHeader->status_complete             = 0;
    $manifestHeader->urut_manifest               = $urut_manifest;
    $manifestHeader->tcs                         = 0;
    $manifestHeader->ambil_sendiri               = 0;
    $manifestHeader->ritase                      = 0;
    $manifestHeader->cbm                         = 0;
    $manifestHeader->manifest_return             = 0;
    $manifestHeader->manifest_type               = 'NORMAL';
    $manifestHeader->status_inv_recieve          = 0;
    $manifestHeader->have_lcl                    = 0;
    $manifestHeader->lcl_from_driver_register_id = $request->input('lcl_from_driver_register_id');
    $manifestHeader->lcl_from_manifest_no        = $request->input('lcl_from_manifest_no');
    $manifestHeader->lcl_created_date            = $request->input('lcl_created_date');
    $manifestHeader->lcl_created_by              = $request->input('lcl_created_by');
    $manifestHeader->have_resend                 = 0;
    $manifestHeader->manifest_resend             = 0;
    $manifestHeader->r_from_manifest             = $request->input('r_from_manifest');
    $manifestHeader->r_driver_register_id        = $request->input('r_driver_register_id');
    $manifestHeader->r_create_date               = $request->input('r_create_date');
    $manifestHeader->r_create_by                 = $request->input('r_create_by');
    $manifestHeader->created_by                 = auth()->user()->id;

    $freightCost = FreightCost::where('area', $manifestHeader->area)
      ->where('vehicle_code_type', $manifestHeader->vehicle_code_type)
      ->where('expedition_code', $manifestHeader->expedition_code)
      ->where('city_code', $manifestHeader->city_code)
      ->first();

    if (empty($freightCost)) {
      abort(404, 'Freight Cost not found');
    }

    $manifestHeader->id_freight_cost = $freightCost->id;
    $manifestHeader->save();
    return $manifestHeader;
  }

  public function update(Request $request, $do_manifest_no)
  {
    $manifestHeader = LogManifestHeader::findOrFail($do_manifest_no);

    $manifestHeader->city_code    = $request->input('city_code');
    $manifestHeader->city_name    = $request->input('city_name');
    $manifestHeader->container_no = $request->input('container_no');
    $manifestHeader->seal_no      = $request->input('seal_no');
    $manifestHeader->checker      = $request->input('checker');
    $manifestHeader->pdo_no       = $request->input('pdo_no');

    $manifestHeader->save();

    return sendSuccess('Manifest updated.', $manifestHeader);
  }

  public function destroy(Request $request, $do_manifest_no)
  {

    try {
      DB::beginTransaction();
      LogManifestDetail::where('do_manifest_no', $do_manifest_no)->delete();
      LogManifestHeader::destroy($do_manifest_no);

      DB::commit();
      return sendSuccess('Success delete manifest.', []);
    } catch (Exception $e) {
      DB::rollBack();
    }
  }

  public function destroyDO(Request $request)
  {
    $detail = LogManifestDetail::findOrFail($request->input('id'));

    $detail->delete();

    return sendSuccess('DO deleted.', $detail);
  }

  public function destroySelectedDO(Request $request)
  {
    $data_list_do = json_decode($request->input('data_list_do'), true);

    $rs_id = [];
    try {
      DB::beginTransaction();
      foreach ($data_list_do as $key => $value) {
        $rs_id[] = $value['id'];
        LogManifestDetail::where('id', $value['id'])->delete();
      }

      DB::commit();
      return sendSuccess("Selected DO deleted.", $rs_id);
    } catch (Exception $e) {
      DB::rollback();
    }
  }

  public function edit($id)
  {
    $data['manifestHeader'] = LogManifestHeader::findOrFail($id);
    $data['lmbHeader']      = $data['manifestHeader']->lmb;
    $data['doData']         = [];
    $data['rsManifest']     = LogManifestHeader::where('driver_register_id', $data['manifestHeader']->driver_register_id)->get();

    return view('web.outgoing.manifest-regular.edit', $data);
  }

  public function assignDO(Request $request, $id)
  {
    $manifestHeader = LogManifestHeader::findOrFail($id);

    $selected_list = json_decode($request->input('selected_list'), true);

    $rs_manifest_detail = [];
    foreach ($selected_list as $key => $value) {
      // print_r($value);

      $concept = Concept::where('delivery_no', $value['delivery_no'])
        ->where('invoice_no', $value['invoice_no'])
        ->where('delivery_items', $value['delivery_items'])
        ->where('model', $value['model'])->first();

      // Cek Manifest Detail
      if (!empty(LogManifestDetail::where('do_manifest_no', $request->input('do_manifest_no'))
        ->where('delivery_no', $value['delivery_no'])
        ->where('invoice_no', $value['invoice_no'])
        ->where('delivery_items', $value['delivery_items'])
        ->where('line_no', $value['line_no'])
        ->first())) {
        continue;
      }

      $manifestDetail['do_manifest_no'] = $request->input('do_manifest_no');
      // $manifestDetail['no_urut']             = '';
      $manifestDetail['delivery_no']     = $value['delivery_no'];
      $manifestDetail['delivery_items']  = $value['delivery_items'];
      $manifestDetail['invoice_no']      = $value['invoice_no'];
      $manifestDetail['line_no']         = $value['line_no'];
      $manifestDetail['ambil_sendiri']   = 0;
      $manifestDetail['model']           = $value['model'];
      $manifestDetail['expedition_code'] = $manifestHeader->expedition_code;
      $manifestDetail['expedition_name'] = $manifestHeader->expedition_name;
      $manifestDetail['sold_to']         = $concept->sold_to;
      $manifestDetail['sold_to_code']    = $concept->sold_to_code;
      $manifestDetail['sold_to_street']  = $concept->sold_to_street;
      $manifestDetail['ship_to']         = $concept->ship_to;
      $manifestDetail['ship_to_code']    = $concept->ship_to_code;
      $manifestDetail['city_code']       = $request->input('ship_to');
      $manifestDetail['city_name']       = $request->input('city_name');
      $manifestDetail['do_date']         = $manifestHeader->do_manifest_date;
      $manifestDetail['quantity']        = $value['quantity'];
      $manifestDetail['cbm']             = $value['cbm'];
      $manifestDetail['area']            = $manifestHeader->area;
      $manifestDetail['do_internal']     = $request->input('do_internal');
      $manifestDetail['reservasi_no']    = $request->input('reservasi_no');
      $manifestDetail['code_sales']      = $concept->code_sales;
      $manifestDetail['tcs']             = 1;
      $manifestDetail['kode_cabang']     = substr($value['kode_customer'], 0, 2);
      $manifestDetail['region']          = '';
      $manifestDetail['status_ds_done']  = 0;
      $manifestDetail['do_reject']       = 0;

      $manifestDetail['created_at']       = date('Y-m-d H:i:s');
      $manifestDetail['created_by']       = auth()->user()->id;
      $manifestDetail['updated_at']       = date('Y-m-d H:i:s');
      $manifestDetail['updated_by']       = auth()->user()->id;

      $freightCost = FreightCost::where('area', $manifestHeader->area)
        ->where('vehicle_code_type', $manifestHeader->vehicle_code_type)
        ->where('expedition_code', $manifestHeader->expedition_code)
        ->where('city_code', $manifestDetail['city_code'])
        ->first();

      if (empty($freightCost)) {
        return sendError('Freight Cost Not Found');
      }

      $manifestDetail['nilai_ritase']  = $freightCost->ritase;
      $manifestDetail['nilai_ritase2'] = $freightCost->ritase2;
      $manifestDetail['lead_time']     = $freightCost->leadtime;
      $manifestDetail['base_price']    = $freightCost->cbm;
      $manifestDetail['nilai_cbm']     = $manifestDetail['base_price'] * $manifestDetail['cbm'];

      $rs_manifest_detail[] = $manifestDetail;
    }

    $manifestHeader->tcs = 1;

    try {
      DB::beginTransaction();

      LogManifestDetail::insert($rs_manifest_detail);
      // if ($manifestHeader->lmb->do_details->count() == 0) {
      //   $manifestHeader->status_complete = 1;
      // }

      $manifestHeader->save();
      DB::commit();

      return sendSuccess('Success submit to logsys', $manifestHeader);
    } catch (Exception $e) {
      DB::rollBack();
    }
  }

  public function uploadDO(Request $request, $do_manifest_no)
  {
    $request->validate([
      'file-do' => 'required',
    ]);

    $manifestHeader = LogManifestHeader::findOrFail($do_manifest_no);

    $file = fopen($request->file('file-do'), "r");

    $title         = true; // Untuk Penada Baris pertama adalah Judul
    $rs_do         = [];
    $rs_model      = [];
    $rs_code_sales = [];

    $rs_check_delivery_no = [];

    while (!feof($file)) {
      $row = fgetcsv($file);
      if ($title) {
        $title = false;
        continue; // Skip baris judul
      }

      if (!empty($row[5])) {
        $do = [];

        $do['invoice_no']     = $row[5];
        $do['delivery_no']    = $row[1];
        $do['delivery_items'] = $row[4];
        $do['line_no']        = $row[4];
        $do['do_date']        = $row[2];
        $do['sold_to_code']   = $row[7];
        $do['sold_to']        = $row[8];
        $do['ship_to_code']   = $row[7];
        $do['ship_to']        = $row[8];
        $do['model']          = $row[9];
        $do['quantity']       = $row[10];
        $do['cbm']            = $row[16];
        $do['area']           = auth()->user()->area;
        // $do['kode_cabang']     = auth()->user()->cabang->kode_cabang;
        $do['kode_cabang']   = substr($do['sold_to_code'], 0, 2);
        $do['remarks']       = '-';
        $do['created_by']    = auth()->user()->id;
        $do['created_at']    = date('Y-m-d H:i:s');
        $do['ambil_sendiri'] = 0;
        $do['tcs']           = 0;
        if ($request->input('type') == 'return') {
          $do['do_return'] = 1;
        }
        $do['expedition_code'] = $manifestHeader->expedition_code;
        $do['expedition_name'] = $manifestHeader->expedition_name;
        $do['city_code']       = $request->input('ship_to');
        $do['city_name']       = $request->input('city_name');
        $do['do_date']         = $manifestHeader->do_manifest_date;
        $do['do_manifest_no']  = $manifestHeader->do_manifest_no;
        $do['do_internal']     = $request->input('do_internal');
        $do['reservasi_no']    = $request->input('reservasi_no');

        $do['created_at']       = date('Y-m-d H:i:s');
        $do['created_by']       = auth()->user()->id;
        $do['updated_at']       = date('Y-m-d H:i:s');
        $do['updated_by']       = auth()->user()->id;

        if (empty($rs_model[$do['model']])) {
          $model = MasterModel::where('model_name', $do['model'])->first();

          if ($request->input('type') != 'return' && empty($model)) {
            return sendError("Model Not Found in Master Model");
          }

          $rs_model[$do['model']] = $model;
        }

        if (empty($rs_code_sales[$do['sold_to_code']])) {
          $cabang                             = MasterCabang::where('kode_customer', $do['sold_to_code'])->first();
          $rs_code_sales[$do['sold_to_code']] = empty($cabang) ? 'DS' : 'BR';
        }

        $do['code_sales'] = $rs_code_sales[$do['sold_to_code']];

        // $do['ean_code'] = !empty($rs_model[$do['model']]) ? $rs_model[$do['model']]->ean_code : '';

        $freightCost = FreightCost::where('area', $manifestHeader->area)
          ->where('vehicle_code_type', $manifestHeader->vehicle_code_type)
          ->where('expedition_code', $manifestHeader->expedition_code)
          ->where('city_code', $do['city_code'])
          ->first();

        if (empty($freightCost)) {
          return sendError('Freight Cost Not Found');
        }

        $do['nilai_ritase']  = $freightCost->ritase;
        $do['nilai_ritase2'] = $freightCost->ritase2;
        $do['lead_time']     = $freightCost->leadtime;
        $do['base_price']    = $freightCost->cbm;
        $do['nilai_cbm']     = $do['base_price'] * $do['cbm'];

        $rs_do[]                = $do;
        $rs_check_delivery_no[] = $do['delivery_no'];
      }
    }

    if ($request->input('type') != 'return') {
      $rsCheckConcept = Concept::whereIn('delivery_no', $rs_check_delivery_no)->get();

      if ($rsCheckConcept->count() > 0) {
        return sendError('DO Already Exist', $rsCheckConcept);
      }
    }

    LogManifestDetail::insert($rs_do);

    return sendSuccess('DO Uploaded', $rs_do);
  }

  public function export(Request $request, $id)
  {
    $data['manifestHeader'] = LogManifestHeader::findOrFail($id);

    $rs_details = [];
    foreach (LogManifestDetail::listDO($data['manifestHeader']->do_manifest_no)->orderBy('log_manifest_detail.delivery_no')->get() as $key => $value) {
      $rs_details[$value->delivery_no]['ship_to_code'] = $value->ship_to_code;
      $rs_details[$value->delivery_no]['ship_to']      = $value->ship_to;

      // $rs_details[$value->delivery_no]['rowspan'] = empty($rs_details[$value->delivery_no]['rowspan']) ? 1 : ($rs_details[$value->delivery_no]['rowspan'] + 1);

      // if (empty($rs_details[$value->delivery_no]['dos'][$value->delivery_no])) {
      //   $rs_details[$value->delivery_no]['rowspan']++;
      // }

      $rs_details[$value->delivery_no]['dos'][$value->delivery_no]['data']     = $value;
      $rs_details[$value->delivery_no]['dos'][$value->delivery_no]['models'][] = $value;
    }

    $data['rs_details'] = $rs_details;

    // echo "<pre>";
    // return print_r($rs_details);

    $view_print = view('web.outgoing.manifest-regular._print', $data);
    $title      = 'Manifest Reguler';

    if ($request->input('filetype') == 'html') {
      $mpdf = new \Mpdf\Mpdf([
        'tempDir' => '/tmp',
        'margin_left'                     => 3,
        'margin_right'                    => 3,
        'margin_top'                      => 60,
        'margin_bottom'                   => 60,
        'format'                          => 'Letter',
      ]);

      $mpdf->WriteHTML($view_print);

      $mpdf->Output();
      return;
    } elseif ($request->input('filetype') == 'xls') {

      // Request FILE EXCEL
      $reader      = new \PhpOffice\PhpSpreadsheet\Reader\Html();
      $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

      $spreadsheet = $reader->loadFromString($view_print, $spreadsheet);

      // Set warna background putih
      $spreadsheet->getDefaultStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ffffff');
      // Set Font
      $spreadsheet->getDefaultStyle()->getFont()->setName('courier New');

      // Atur lebar kolom
      $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
      $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(5);
      $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(5);
      $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(5);
      $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
      $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(5);
      $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(5);
      $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(5);
      $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(5);
      $spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(5);
      $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);

      $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment; filename="' . $title . '.xls"');

      $writer->save("php://output");
    } else if ($request->input('filetype') == 'pdf') {

      // REQUEST PDF
      $mpdf = new \Mpdf\Mpdf([
        'tempDir' => '/tmp',
        'margin_left'                     => 3,
        'margin_right'                    => 3,
        'margin_top'                      => 60,
        'margin_bottom'                   => 60,
        'format'                          => 'Letter',
      ]);

      $mpdf->WriteHTML($view_print);
      $mpdf->Output($title . '.pdf', "D");
    } else {
      // Parameter filetype tidak valid / tidak ditemukan return 404
      return redirect(404);
    }
  }
}

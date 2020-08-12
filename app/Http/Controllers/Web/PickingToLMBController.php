<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\InventoryStorage;
use App\Models\LMBDetail;
use App\Models\LMBHeader;
use App\Models\MasterCabang;
use App\Models\MasterModel;
use App\Models\MovementTransactionLog;
use App\Models\PickinglistDetail;
use App\Models\PickinglistHeader;
use App\Models\StorageMaster;
use DataTables;
use DB;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class PickingToLMBController extends Controller
{
  public function index(Request $request)
  {
    if ($request->ajax()) {
      $query = LMBHeader::all();

      $datatables = DataTables::of($query)
        ->addIndexColumn() //DT_RowIndex (Penomoran)
        ->addColumn('picking_no', function ($data) {
          return $data->picking->picking_no;
        })
        ->addColumn('action', function ($data) {
          $action = '';
          $action .= ' ' . get_button_view(url('picking-to-lmb/' . $data->driver_register_id), 'View Detail');
          if (!$data->send_manifest) {
            $action .= ' ' . get_button_delete('Cancel');
          }
          return $action;
        })
        ->rawColumns(['do_status', 'action']);

      return $datatables->make(true);
    }
    return view('web.picking.picking-to-lmb.index');
  }

  public function show(Request $request, $id)
  {
    $data['lmbHeader'] = LMBHeader::findOrFail($id);

    if ($request->ajax()) {
      $details = $data['lmbHeader']->details;
      return sendSuccess('Seat Loading Quantity', $details);
    }

    $data['rs_loading_quantity'] = $data['lmbHeader']
      ->details()
      ->selectRaw('
        wms_lmb_detail.invoice_no,
        wms_lmb_detail.delivery_no,
        wms_lmb_detail.model,
        COUNT(serial_number) AS qty_loading,
        wms_lmb_detail.code_sales,
        wms_pickinglist_detail.quantity
      ')
      ->leftjoin('wms_pickinglist_header', 'wms_pickinglist_header.id', '=', 'wms_lmb_detail.picking_id')
      ->leftjoin('wms_pickinglist_detail', function ($join) {
        $join->on('wms_pickinglist_detail.invoice_no', '=', 'wms_lmb_detail.invoice_no');
        $join->on('wms_pickinglist_detail.delivery_no', '=', 'wms_lmb_detail.delivery_no');
        $join->on('wms_pickinglist_detail.model', '=', 'wms_lmb_detail.model');
      })
      ->groupBy('delivery_no', 'model')
      ->get();
    ;

    return view('web.picking.picking-to-lmb.view', $data);
  }

  public function store(Request $request)
  {
    $request->validate([
      'picking_no'   => 'required',
      'seal_no'      => 'required',
      'container_no' => 'required',
    ]);

    $picking = PickinglistHeader::where('picking_no', $request->input('picking_no'))->first();

    $lmbHeader                     = new LMBHeader;
    $lmbHeader->driver_register_id = $request->input('driver_register_id');
    $lmbHeader->lmb_date           = date('Y-m-d');
    // $lmbHeader->do_reservation_no        = '';
    // $lmbHeader->pdo                      = '';
    $lmbHeader->expedition_code    = $picking->expedition_code;
    $lmbHeader->expedition_name    = $picking->expedition_name;
    $lmbHeader->driver_id          = $picking->driver_id;
    $lmbHeader->driver_name        = $picking->driver_name;
    $lmbHeader->vehicle_number     = $picking->vehicle_number;
    $lmbHeader->destination_number = $picking->destination_number;
    $lmbHeader->destination_name   = $picking->destination_name;
    $lmbHeader->kode_cabang        = $picking->kode_cabang;

    $cabang = MasterCabang::where('kode_cabang', $lmbHeader->kode_cabang)->first();

    $lmbHeader->short_description_cabang = $cabang->short_description;
    $lmbHeader->seal_no                  = $request->input('seal_no');
    $lmbHeader->container_no             = $request->input('container_no');
    $lmbHeader->send_manifest            = 0;

    if ($picking->expedition_code == 'AS') {
      $lmbHeader->destination_number = $picking->expedition_code;
      $lmbHeader->destination_name   = $picking->expedition_name;
    }
    // $lmbHeader->start_date               = '';
    // $lmbHeader->finish_date              = '';
    // $lmbHeader->finish_by                = '';

    $lmbHeader->save();

    return $lmbHeader;

  }

  /**
   * Stock di 1 class berkurang dan stock di intransit bertambah
   */
  public function sendManifest($id)
  {
    $lmbHeader = LMBHeader::findOrFail($id);

    $lmbHeader->send_manifest = 1;

    $details = $lmbHeader
      ->details()
      ->select(
        'wms_lmb_detail.*',
        'wms_pickinglist_header.storage_id',
        'wms_master_storage.sto_loc_code_long'
      )
      ->leftjoin('wms_pickinglist_header', 'wms_pickinglist_header.picking_no', '=', 'wms_lmb_detail.picking_id')
      ->leftjoin('wms_master_storage', 'wms_master_storage.id', '=', 'wms_pickinglist_header.storage_id')
      ->get();

    $rs_models = [];

    foreach ($details as $key => $value) {
      if (empty($rs_models[$value->model])) {
        $model                      = [];
        $model['storage_id']        = $value->storage_id;
        $model['sto_loc_code_long'] = $value->sto_loc_code_long;
        $model['ean_code']          = $value->ean_code;
        $model['code_sales']        = $value->code_sales;
        $model['qty']               = 0;
        $model['cbm_total']         = 0;

        $rs_models[$value->model] = $model;
      }

      $rs_models[$value->model]['qty'] += 1;
      $rs_models[$value->model]['cbm_total'] += $value->cbm_unit;

    }

    // print_r($rs_models);
    // exit;
    $date_now = date('Y-m-d H:i:s');

    // Storage Intransit
    // 3 Intransit BR
    $storageIntransit['BR'] = StorageMaster::where('sto_type_id', 3)
      ->where('kode_cabang', $lmbHeader->kode_cabang)
      ->first();
    // 4 Intransit DS
    $storageIntransit['DS'] = StorageMaster::where('sto_type_id', 4)
      ->where('kode_cabang', $lmbHeader->kode_cabang)
      ->first();

    $rs_movement_transaction_log = [];

    // Update Movement 1 class berkurang intransit bertambah
    foreach ($rs_models as $key => $value) {
      // Update Or Create Inventory Stroage data
      InventoryStorage::updateOrCreate(
        // Condition
        [
          'storage_id' => $value['storage_id'],
          'model_name' => $key,
        ],
        // Data Update
        [
          'ean_code'       => $value['ean_code'],
          'quantity_total' => DB::raw('IF(ISNULL(quantity_total), 0, quantity_total) - ' . $value['qty']),
          'cbm_total'      => DB::raw('IF(ISNULL(cbm_total), 0, cbm_total) - ' . $value['cbm_total']),
          'last_updated'   => $date_now,
        ]
      );

      InventoryStorage::updateOrCreate(
        // Condition
        [
          'storage_id' => $storageIntransit[$value['code_sales']]->id,
          'model_name' => $key,
        ],
        // Data Update
        [
          'ean_code'       => $value['ean_code'],
          'quantity_total' => DB::raw('IF(ISNULL(quantity_total), 0, quantity_total) + ' . $value['qty']),
          'cbm_total'      => DB::raw('IF(ISNULL(cbm_total), 0, cbm_total) + ' . $value['cbm_total']),
          'last_updated'   => $date_now,
        ]
      );

      // ADD MOVEMENT
      // Movement Code
      // id 7 Code 101 Increase Menambah Sloc Intransit
      $movement_transaction_log['log_id']                = Uuid::uuid4()->toString();
      $movement_transaction_log['arrival_no']            = '';
      $movement_transaction_log['mvt_master_id']         = 7;
      $movement_transaction_log['inventory_movement']    = 'Stock INCREASE';
      $movement_transaction_log['movement_code']         = 101;
      $movement_transaction_log['transactions_desc']     = 'Add LMB Outgoing';
      $movement_transaction_log['storage_location_from'] = $value['sto_loc_code_long'];
      $movement_transaction_log['storage_location_to']   = $storageIntransit[$value['code_sales']]->sto_loc_code_long;
      $movement_transaction_log['storage_location_code'] = $movement_transaction_log['storage_location_from'] . ' & ' . $movement_transaction_log['storage_location_to'];
      $movement_transaction_log['eancode']               = $value['ean_code'];
      $movement_transaction_log['model']                 = $key;
      $movement_transaction_log['quantity']              = $value['qty'];
      $movement_transaction_log['created_at']            = $date_now;
      $movement_transaction_log['flow_id']               = '';
      $movement_transaction_log['kode_cabang']           = $lmbHeader->kode_cabang;

      $rs_movement_transaction_log[] = $movement_transaction_log;

      // id 8 Code 647 Decrease Mengurangi SLOC
      $movement_transaction_log['log_id']                = Uuid::uuid4()->toString();
      $movement_transaction_log['arrival_no']            = '';
      $movement_transaction_log['mvt_master_id']         = 8;
      $movement_transaction_log['inventory_movement']    = 'Stock DECREASE';
      $movement_transaction_log['movement_code']         = 647;
      $movement_transaction_log['transactions_desc']     = 'Add LMB Outgoing';
      $movement_transaction_log['storage_location_from'] = $value['sto_loc_code_long'];
      $movement_transaction_log['storage_location_to']   = $storageIntransit[$value['code_sales']]->sto_loc_code_long;
      $movement_transaction_log['storage_location_code'] = $movement_transaction_log['storage_location_from'] . ' & ' . $movement_transaction_log['storage_location_to'];
      $movement_transaction_log['eancode']               = $value['ean_code'];
      $movement_transaction_log['model']                 = $key;
      $movement_transaction_log['quantity']              = $value['qty'];
      $movement_transaction_log['created_at']            = $date_now;
      $movement_transaction_log['flow_id']               = '';
      $movement_transaction_log['kode_cabang']           = $lmbHeader->kode_cabang;

      $rs_movement_transaction_log[] = $movement_transaction_log;
    }

    MovementTransactionLog::insert($rs_movement_transaction_log);

    $lmbHeader->save();

    return $lmbHeader;
  }

  public function upload(Request $request)
  {
    $request->validate([
      'file_scan' => 'required',
    ]);

    $file = fopen($request->file('file_scan'), "r");

    // $title          = true; // Untuk Penada Baris pertama adalah Judul
    $serial_numbers = [];
    $scan_summaries = [];

    $rs_models               = [];
    $rs_picking_list_details = [];

    $rs_key = [];

    while (!feof($file)) {
      $row = fgetcsv($file);
      // if ($title) {
      //   $title = false;
      //   continue; // Skip baris judul
      // }
      $serial_number = [
        'picking_id'    => $row[0],
        'ean_code'      => $row[1],
        'serial_number' => $row[2],
      ];

      // Validasi Data Per Baris
      if (!empty($serial_number['picking_id'])) {
        // kalau data ada isinya

        if (empty($rs_models[$serial_number['ean_code']])) {
          $model = MasterModel::where('ean_code', $serial_number['ean_code'])->first();
          if (empty($model)) {
            $result['status']  = false;
            $result['message'] = 'Model ' . $serial_number['ean_code'] . ' not found in master model !';
            return $result;
          }
          $rs_models[$serial_number['ean_code']] = $model;
        }

        if (empty($rs_picking_list_details[$serial_number['ean_code']])) {
          $picking_detail = PickinglistDetail::select('wms_pickinglist_detail.*')
            ->leftjoin('wms_pickinglist_header', 'wms_pickinglist_header.id', '=', 'wms_pickinglist_detail.header_id')
            ->where('ean_code', $serial_number['ean_code'])
            ->where('picking_no', $serial_number['picking_id'])
            ->first();

          if (empty($picking_detail)) {
            $result['status']  = false;
            $result['message'] = 'EAN ' . $serial_number['ean_code'] . ' not found in picking_list !';
            return $result;
          }
          $rs_picking_list_details[$serial_number['ean_code']] = $picking_detail;
        }

        $serial_number['model']              = $rs_models[$serial_number['ean_code']]->model_name;
        $serial_number['delivery_no']        = $rs_picking_list_details[$serial_number['ean_code']]->delivery_no;
        $serial_number['invoice_no']         = $rs_picking_list_details[$serial_number['ean_code']]->invoice_no;
        $serial_number['kode_customer']      = $rs_picking_list_details[$serial_number['ean_code']]->kode_customer;
        $serial_number['code_sales']         = $rs_picking_list_details[$serial_number['ean_code']]->code_sales;
        $serial_number['city_code']          = $rs_picking_list_details[$serial_number['ean_code']]->header->city_code;
        $serial_number['city_name']          = $rs_picking_list_details[$serial_number['ean_code']]->header->city_name;
        $serial_number['driver_register_id'] = $rs_picking_list_details[$serial_number['ean_code']]->header->driver_register_id;

        $serial_number['cbm_unit'] = $rs_picking_list_details[$serial_number['ean_code']]->cbm / $rs_picking_list_details[$serial_number['ean_code']]->quantity;

        if (empty($scan_summaries[$serial_number['ean_code']])) {
          $scan_summaries[$serial_number['ean_code']] = [
            'model'             => $rs_models[$serial_number['ean_code']]->model_name,
            'quantity_scan'     => 0,
            'quantity_picking'  => $rs_picking_list_details[$serial_number['ean_code']]->quantity,
            'quantity_existing' => $rs_picking_list_details[$serial_number['ean_code']]->quantity,
          ];
        }

        $scan_summaries[$serial_number['ean_code']]['quantity_scan'] += 1;
        $scan_summaries[$serial_number['ean_code']]['quantity_existing'] -= 1;

        $serial_numbers[] = $serial_number;
      }
      // Akhir validasi data per baris
    }

    $result['serial_numbers'] = $serial_numbers;
    $result['scan_summaries'] = $scan_summaries;

    return $result;
  }

  public function storeScan(Request $request)
  {
    $data_serial_numbers = json_decode($request->input('data_serial_numbers'), true);

    foreach ($data_serial_numbers as $key => $value) {
      $data_serial_numbers[$key] = $value;
    }

    LMBDetail::insert($data_serial_numbers);

    return true;
  }

  public function destroy($id)
  {
    return LMBHeader::destroy($id);
  }

  public function destroyLmbDetail(Request $request)
  {
    return LMBDetail::where('ean_code', $request->input('ean_code'))
      ->where('serial_number', $request->input('serial_number'))
      ->where('picking_id', $request->input('picking_id'))
      ->delete()
    ;
  }

  public function pickingListIndex(Request $request)
  {
    $query = PickinglistHeader::noLMBPickingList()->get();

    $datatables = DataTables::of($query)
      ->addIndexColumn() //DT_RowIndex (Penomoran)
      ->editColumn('destination_name', function ($data) {
        return $data->expedition_code == 'AS' ? "Ambil Sendiri" : $data->destination_name;
      })
      ->addColumn('do_status', function ($data) {
        return $data->details()->count() > 0 ? 'DO Already' : '<span class="red-text">DO not yet assign</span>';
      })
      ->addColumn('lmb', function ($data) {
        return '-';
      })
      ->addColumn('action', function ($data) {
        $action = '';
        $action .= ' ' . get_button_edit(url('picking-to-lmb/picking-list/' . $data->id), 'Create');
        return $action;
      })
      ->rawColumns(['do_status', 'action']);

    return $datatables->make(true);
  }

  public function pickingListCreate(Request $request, $id)
  {
    $data['picking'] = PickinglistHeader::findOrFail($id);

    if ($request->ajax()) {
      $query = LMBDetail::where('picking_id', $id)
        ->get();

      $datatables = DataTables::of($query)
        ->addIndexColumn() //DT_RowIndex (Penomoran)
        ->addColumn('action', function ($data) {
          $action = '';
          $action .= ' ' . get_button_delete();
          return $action;
        })
      ;

      return $datatables->make(true);
    }

    return view('web.picking.picking-to-lmb.create', $data);
  }

  public function export(Request $request, $id)
  {
    // $data['pickinglistHeader'] = PickinglistHeader::findOrFail($id);

    $view_print = view('web.picking.picking-to-lmb._print');
    $title      = 'Picking List LMB';

    if ($request->input('filetype') == 'html') {

      // request HTML View
      return $view_print;

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
      $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
      $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
      $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(15);
      $spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);

      $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment; filename="' . $title . '.xls"');

      $writer->save("php://output");

    } else if ($request->input('filetype') == 'pdf') {

      // REQUEST PDF
      $mpdf = new \Mpdf\Mpdf(['tempDir' => '/tmp']);

      $mpdf->WriteHTML($view_print, \Mpdf\HTMLParserMode::HTML_BODY);

      $mpdf->Output($title . '.pdf', "D");

    } else {
      // Parameter filetype tidak valid / tidak ditemukan return 404
      return redirect(404);
    }
  }

}

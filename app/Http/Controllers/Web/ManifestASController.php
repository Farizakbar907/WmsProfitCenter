<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\LMBHeader;
use App\Models\LogManifestHeader;
use DataTables;
use DB;
use Illuminate\Http\Request;

class ManifestASController extends Controller
{
  public function index(Request $request)
  {
    if ($request->ajax()) {
      $query = LogManifestHeader::where('city_name', '=', 'Ambil Sendiri');

      $datatables = DataTables::of($query)
        ->addIndexColumn() //DT_RowIndex (Penomoran)
        ->addColumn('picking_no', function ($data) {
          return $data->picking->picking_no;
        })
        ->addColumn('status', function ($data) {
          return $data->status();
        })
        ->addColumn('actionEdit', function ($data) {
          $action = '';
          $action .= ' ' . get_button_view(url('manifest-as/' . $data->do_manifest_no . '/edit'), 'View');
          return $action;
        })
        ->addColumn('actionDelete', function ($data) {
          $action = '';
          $action .= ' ' . get_button_delete();
          return $action;
        })
        ->addColumn('actionPrint', function ($data) {
          $action = '';
          $action .= ' ' . get_button_print(url('manifest-as/' . $data->do_manifest_no . '/export'));
          return $action;
        })
        ->rawColumns(['do_status', 'actionEdit', 'actionDelete', 'actionPrint']);

      return $datatables->make(true);
    }

    return view('web.outgoing.manifest-as.index');
  }
  public function lmbWaitingManifest(Request $request)
  {
    if ($request->ajax()) {
      $query = LMBHeader::noManifestLMBHeader()->where('wms_lmb_header.expedition_code', '=', 'AS')->get();

      $datatables = DataTables::of($query)
        ->addIndexColumn() //DT_RowIndex (Penomoran)
        ->addColumn('picking_no', function ($data) {
          return $data->picking->picking_no;
        })
        ->addColumn('action', function ($data) {
          $action = '';
          $action .= ' ' . get_button_view(url('manifest-as/' . $data->driver_register_id . '/create-manifest'), 'Create');
          return $action;
        })
        ->rawColumns(['do_status', 'action']);

      return $datatables->make(true);
    }
  }

  public function createManifest($lmb_id)
  {
    $data['lmbHeader'] = LMBHeader::find($lmb_id);

    return view('web.outgoing.manifest-as.create', $data);
  }

  public function store(Request $request)
  {
    $request->validate([
      'driver_register_id' => 'required',
    ]);

    $manifestHeader = new LogManifestHeader;

    // DO MANIFEST NO => KODEAREA-ymd-URUT
    $prefix = auth()->user()->area_data->code . '-' . date('ymd');

    $prefix_length = strlen($prefix);
    $max_no        = DB::select('SELECT MAX(SUBSTR(do_manifest_no, ?)) AS max_no FROM log_manifest_header WHERE SUBSTR(do_manifest_no,1,?) = ? ', [$prefix_length + 2, $prefix_length, $prefix])[0]->max_no;
    $max_no        = str_pad($max_no + 1, 3, 0, STR_PAD_LEFT);

    $do_manifest_no = $prefix . '-' . $max_no;

    $manifestHeader->driver_register_id = $request->input('driver_register_id');
    $manifestHeader->do_manifest_no     = $do_manifest_no;
    // $manifestHeader->expedition_code             = $request->input('expedition_code');
    $manifestHeader->expedition_name = 'Ambil Sendiri';
    // $manifestHeader->driver_id                   = $request->input('driver_id');
    $manifestHeader->driver_name = 'Ambil Sendiri';
    // $manifestHeader->vehicle_number              = $request->input('vehicle_number');
    // $manifestHeader->vehicle_code_type           = $request->input('vehicle_code_type');
    $manifestHeader->vehicle_description = 'Ambil Sendiri';
    $manifestHeader->do_manifest_date    = $request->input('do_manifest_date');
    $manifestHeader->do_manifest_time    = date('Y-m-d H:i:s');
    // $manifestHeader->destination_number_driver   = $request->input('destination_number_driver');
    $manifestHeader->destination_name_driver = 'Ambil Sendiri';
    // $manifestHeader->city_code                   = $request->input('city_code');
    $manifestHeader->city_name                   = 'Ambil Sendiri';
    $manifestHeader->container_no                = $request->input('container_no');
    $manifestHeader->seal_no                     = $request->input('seal_no');
    $manifestHeader->checker                     = $request->input('checker');
    $manifestHeader->pdo_no                      = $request->input('pdo_no');
    $manifestHeader->area                        = auth()->user()->area;
    $manifestHeader->status_complete             = 0;
    $manifestHeader->urut_manifest               = 1;
    $manifestHeader->tcs                         = 0;
    $manifestHeader->ambil_sendiri               = 1;
    $manifestHeader->ritase                      = 0;
    $manifestHeader->cbm                         = 0;
    $manifestHeader->manifest_return             = 0;
    $manifestHeader->manifest_type               = 'MANUAL';
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

    $manifestHeader->save();

    return $manifestHeader;
  }

  public function edit($id)
  {
    $data['manifestHeader'] = LogManifestHeader::findOrFail($id);
    $data['lmbHeader']      = $data['manifestHeader']->lmb;
    $data['doData']         = [];

    return view('web.outgoing.manifest-as.edit', $data);
  }

  public function export(Request $request, $id)
  {
    // $data['pickinglistHeader'] = PickinglistHeader::findOrFail($id);

    $view_print = view('web.outgoing.manifest-as._print');
    $title      = 'Manifest AS';

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
      $mpdf = new \Mpdf\Mpdf(['tempDir' => '/tmp']);

      $mpdf->WriteHTML($view_print, \Mpdf\HTMLParserMode::HTML_BODY);

      $mpdf->Output($title . '.pdf', "D");

    } else {
      // Parameter filetype tidak valid / tidak ditemukan return 404
      return redirect(404);
    }
  }
}

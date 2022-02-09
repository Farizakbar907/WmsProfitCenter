<?php

namespace App\Http\Controllers\Web;

use Amenadiel\JpGraph\Graph;
use Amenadiel\JpGraph\Plot;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportConceptComingActualLoadingController extends Controller
{
  public function index(Request $request)
  {
    return view('web.report.report-concept-coming-vs-actual-loading.index');
  }

  public function exports(Request $request)
  {
    $data['graph'] = $this->getGraph($request);
    function index(){
      
        Excel::create('New File', function($excel){
          $excel->sheet('First shhet', function($sheet){
            $sheet->LoadView('web.report.report-concept-coming-vs-actual-loading.print', $data);
          });
        })->export('xlsx');
    }
  }

  public function gambar(Request $request)
  {
    $data['graph'] = $this->getGraph($request);

    $view_gambar = view('web.report.report-concept-coming-vs-actual-loading.gambar', $data);

    return $view_gambar;
  }
  public function export(Request $request)
  {
    $data['graph'] = $this->getGraph($request);

    $view_print = view('web.report.report-concept-coming-vs-actual-loading.print', $data);
    $title      = 'Concept Coming vs Actual Loading Report';

    if ($request->input('filetype') == 'html') {

      // request HTML View
      return $view_print;

    } elseif ($request->input('filetype') == 'xls') {

      // Request FILE EXCEL
      //$reader      = new \PhpOffice\PhpSpreadsheet\Reader\Html();
      //$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

      //$spreadsheet = $reader->loadFromString($view_print, $spreadsheet);

      $graph = $this->getGraph($request);
     

      ob_start();
      $img = 'image/PNG';
      $img = $graph->Stroke(_IMG_HANDLER);

      ob_start();  
      $graph->img->Stream();             // print data to buffer
      $image_data = ob_get_contents(); 
      ob_end_clean();
      
      

      //$graph = $this->getGraph($request);
      //$contentType = 'image/PNG';
      //$gdImgHandler = $graph->Stroke(_IMG_HANDLER);
      
      //ob_start();                        // start buffering
      //$graph->img->Stream();             // print data to buffer
      //$image_data = ob_get_contents();   // retrieve buffer contents
      //ob_end_clean();                    // stop buffer
      
      //echo "data:$contentType;base64," . base64_encode($image_data);
      $htmlString = '<table>
                          <tr>
                           <td><img src="data:image/png;base64,'.base64_encode($image_data).'" /></td>
                          </tr>
                          <tr>
                            <td></td>
                          </tr>
                         <tr>
                            <td style="">Print date : '.date('d M, Y').'</td>
                          </tr>
                          <tr>
                            <td style="">Print by : '.auth()->user()->username.'</td>
                          </tr>
                    </table>';
      $reader      = new \PhpOffice\PhpSpreadsheet\Reader\Html();
      $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

      $spreadsheet = $reader->loadFromString($htmlString, $spreadsheet);              
      //$reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
      //$spreadsheet = $reader->load($view_print);
      //$spreadsheet->getSheet(0)->setTitle('file1');
      //$reader->setSheetIndex(1);
      //$reader->loadIntoExisting($view_print, $spreadsheet);
      //$writer1 = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
      //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      //header('Content-Disposition: attachment; filename="' . $title . '.xlsx"');

      //$writer1->save("php://output");
      //$writer1->save('outfile.xlsx');
      //$spreadsheet = new Spreadsheet();
      //$sheet = $spreadsheet->getActiveSheet();
      //$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
      //$drawing->setPath(imagepng($img));
      //$drawing->setWorksheet($spreadsheet->getActiveSheet());
      //$reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
      //$spreadsheet = $reader->loadFromString($htmlString);
      // Set warna background putih
      $spreadsheet->getDefaultStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ffffff');
      // Set Font
      $spreadsheet->getDefaultStyle()->getFont()->setName('courier New');

      // Atur lebar kolom
      //$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(15);
      //$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15);
      //$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
      //$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(15);

      $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment; filename="' . $title . '.xlsx"');

      $writer->save("php://output");

    } else if ($request->input('filetype') == 'pdf') {

      // REQUEST PDF
      $mpdf = New \Mpdf\Mpdf(['tempDir'=>storage_path('tempdir')]);
      //$mpdf = new \Mpdf\Mpdf(['tempDir' => '/tmp']);

      $mpdf->WriteHTML($view_print, \Mpdf\HTMLParserMode::HTML_BODY);

      $mpdf->Output($title . '.pdf', "D");

    } else {
      // Parameter filetype tidak valid / tidak ditemukan return 404
      return redirect(404);
    }
  }

  public function getGraph(Request $request)
  {
    $conceptComingActualLoading = $this->getConceptComingActualLoading($request);

    $databary = ["0.000", "0.000", "0.000", "0.000"];
    foreach ($conceptComingActualLoading as $key => $value) {
      $databary[($value->weeks - 1)] = $value->percentage;
    }

    // new Graph\Graph with a drop shadow
    $__width  = 1200;
    $__height = 800;
    $graph    = new Graph\Graph($__width, $__height);
    $graph->SetShadow();
    $graph->SetMargin(120, 100, 100, 100);

    // Use a "text" X-scale
    $graph->SetScale('textlin');

    // Set title and subtitle
    $graph->title->SetFont(FF_ARIAL, FS_BOLD, 20);
    $graph->title->Set('CONCEPT COMING VS ACTUAL LOADING - ' . $request->input('area'));

    // Create the bar plot
    $b1 = new Plot\BarPlot($databary);
    $b1->SetColor('orange');
    $b1->SetLegend('%');

    $bplot = new Plot\BarPlot($databary);
    // Setup the values that are displayed on top of each bar
    // Must use TTF fonts if we want text at an arbitrary angle
    $bplot->value->Show();
    $bplot->value->SetFont(FF_ARIAL, FS_NORMAL, 20);
    $bplot->value->SetFormat('%01.2f%%');
    $bplot->value->SetColor('darkred');
    // $bplot->value->SetAngle(45);
    $graph->Add($bplot);

    $datax = [
      'Date 1 to 10',
      'Date 11 to 20',
      'Date 21 to 25',
      'Date 26 to 31',
    ];

    $graph->xaxis->SetTickLabels($datax);
    $graph->xaxis->SetFont(FF_ARIAL, FS_NORMAL, 14);
    $graph->xaxis->SetColor('darkblue', 'black');

    $graph->yaxis->SetLabelFormat('%01.2f%%');
    $graph->yaxis->SetFont(FF_ARIAL, FS_NORMAL, 14);

    // The order the plots are added determines who's ontop
    $graph->Add($b1);

    // Finally output the  image
    return $graph;
  }

  protected function getConceptComingActualLoading($request)
  {
    if ($request->input('area') == "All") {
      $sql = "
      SELECT
        weeks,
        SUM(total_concept) AS total_item_concept,
        SUM(total_loading) AS total_loading_truck,
        CAST(SUM(total_loading) / SUM(total_concept) * 100 AS DECIMAL(18,3)) AS percentage
      FROM (
        SELECT
          CASE
            WHEN EXTRACT(DAY FROM created_at) BETWEEN 1 AND 10 THEN 1
            WHEN EXTRACT(DAY FROM created_at) BETWEEN 11 AND 20 THEN 2
            WHEN EXTRACT(DAY FROM created_at) BETWEEN 21 AND 25 THEN 3
            ELSE 4
          END AS weeks,
          0 AS total_loading,
          COUNT(delivery_no) AS total_concept
        FROM tr_concept
        WHERE MONTH(created_at) = ?
        AND YEAR (created_at) = ?
        GROUP BY weeks
        UNION
        SELECT
          CASE
            WHEN EXTRACT(DAY FROM tctf.complete_date) BETWEEN 1 AND 10 THEN 1
            WHEN EXTRACT(DAY FROM tctf.complete_date) BETWEEN 11 AND 20 THEN 2
            WHEN EXTRACT(DAY FROM tctf.complete_date) BETWEEN 21 AND 25 THEN 3
            ELSE 4
          END AS weeks,
          COUNT(tcfd.delivery_no) AS total_loading,
          0 AS total_concept
        FROM tr_concept_flow_detail tcfd
        LEFT JOIN tr_concept_truck_flow tctf ON tcfd.id_header = tctf.concept_flow_header
        LEFT JOIN tr_concept tc ON (tc.invoice_no = tcfd.invoice_no AND tc.delivery_no = tcfd.delivery_no AND tc.delivery_items = tcfd.delivery_items)
        WHERE MONTH(tctf.complete_date) = ?
          AND YEAR(tctf.complete_date) = ?
          AND DATEDIFF(tctf.complete_date, tc.created_at) < 2
        GROUP BY weeks
      ) t
      GROUP BY weeks
      ";

      return DB::select(DB::raw($sql), [
        date('m', strtotime(str_replace('/', '-', '01/' . $request->input('periode')))),
        date('Y', strtotime(str_replace('/', '-', '01/' . $request->input('periode')))),
        date('m', strtotime(str_replace('/', '-', '01/' . $request->input('periode')))),
        date('Y', strtotime(str_replace('/', '-', '01/' . $request->input('periode')))),
      ]);
    } else {
      $sql = "
      SELECT
        weeks,
        SUM(total_concept) AS total_item_concept,
        SUM(total_loading) AS total_loading_truck,
        CAST(SUM(total_loading) / SUM(total_concept) * 100 AS DECIMAL(18,3)) AS percentage
      FROM (
        SELECT
          CASE
            WHEN EXTRACT(DAY FROM created_at) BETWEEN 1 AND 10 THEN 1
            WHEN EXTRACT(DAY FROM created_at) BETWEEN 11 AND 20 THEN 2
            WHEN EXTRACT(DAY FROM created_at) BETWEEN 21 AND 25 THEN 3
            ELSE 4
          END AS weeks,
          0 AS total_loading,
          COUNT(delivery_no) AS total_concept
        FROM tr_concept
        WHERE
        area = ?
        AND MONTH(created_at) = ?
        AND YEAR (created_at) = ?
        GROUP BY weeks
        UNION
        SELECT
          CASE
            WHEN EXTRACT(DAY FROM tctf.complete_date) BETWEEN 1 AND 10 THEN 1
            WHEN EXTRACT(DAY FROM tctf.complete_date) BETWEEN 11 AND 20 THEN 2
            WHEN EXTRACT(DAY FROM tctf.complete_date) BETWEEN 21 AND 25 THEN 3
            ELSE 4
          END AS weeks,
          COUNT(tcfd.delivery_no) AS total_loading,
          0 AS total_concept
        FROM tr_concept_flow_detail tcfd
        LEFT JOIN tr_concept_truck_flow tctf ON tcfd.id_header = tctf.concept_flow_header
        LEFT JOIN tr_concept tc ON (tc.invoice_no = tcfd.invoice_no AND tc.delivery_no = tcfd.delivery_no AND tc.delivery_items = tcfd.delivery_items)
        WHERE
          tctf.area = ?
          AND tctf.area = tc.area
          AND MONTH(tctf.complete_date) = ?
          AND YEAR(tctf.complete_date) = ?
          AND DATEDIFF(tctf.complete_date, tc.created_at) < 2
        GROUP BY weeks
      ) t
      GROUP BY weeks
      ";

      return DB::select(DB::raw($sql), [
        $request->input('area'),
        date('m', strtotime(str_replace('/', '-', '01/' . $request->input('periode')))),
        date('Y', strtotime(str_replace('/', '-', '01/' . $request->input('periode')))),
        $request->input('area'),
        date('m', strtotime(str_replace('/', '-', '01/' . $request->input('periode')))),
        date('Y', strtotime(str_replace('/', '-', '01/' . $request->input('periode')))),
      ]);
    }

  }
}

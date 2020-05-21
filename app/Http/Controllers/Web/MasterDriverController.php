<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MasterDriver;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterDriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request )
    {
        if ($request->ajax()) {
            $query = MasterDriver::select(
                DB::raw('master_expedition.expedition_name')
            )
            ->leftjoin('master_expedition', 'master_expedition.expedition_name', '=', 'master_driver.expedition_code')
            ;
  
            $datatables = DataTables::of($query)
              ->addIndexColumn() //DT_RowIndex (Penomoran)
              ->editColumn('driving_lisence_type', '{{$driving_lisence_type == 1 ? "SIM A" : "SIM B":"SIM B1"}}')
              ->addColumn('action', function ($data) {
                $action = '';
                $action .= ' ' . get_button_edit(url('master-driver/' . $data->driver_id . '/edit'));
                $action .= ' ' . get_button_delete();
                return $action;
              });
  
             return $datatables->make(true);
          }
          return view('web.master.master-driver.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('web.master.master-driver.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'expedition_code'  => 'required|max:3',
            'diver_id'  => 'required|max:10',
            'driving_lisence_number'  => 'required|max:50',
          ]);
  
          $masterDriver              = new MasterDriver;
          $masterDriver->expedition_code = $request->input('expedition_code');
          $masterDriver->driver_id = $request->input('driver_id');
          $masterDriver->driver_name     = $request->input('driver_name');
          $masterDriver->driving_lisence_type  = $request->input('driving_lisence_type');
          $masterDriver->driving_lisence_number = $request->input('driving_lisence_number');
          $masterDriver->ktp_no     = $request->input('ktp_no');
          $masterDriver->phone1   = $request->input('phone1');  
          $masterDriver->phone2   = $request->input('phone2');  
          $masterDriver->remarks1   = $request->input('remarks1');  
          $masterDriver->remarks2   = $request->input('remarks2');
          $masterDriver->remarks3   = $request->input('remarks3'); 
          $masterDriver->status_active =!empty($request->input('status_active'));
          $masterDriver->photo_name     = $request->input('photo_name');  
          return $masterDriver->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['masterDriver'] = MasterDriver::findOrFail($id);
        return view('web.master.master-driver.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'expedition_code'  => 'required|max:3',
            'diver_id'  => 'required|max:10',
            'driving_lisence_number'  => 'required|max:50',
            'driver_name'=>'required',
            'driving_lisence_type'=>'required',
            'ktp_no'=>'required',
            'phone1'=>'required',
            'phone2'=>'required',
            'remarks1'=>'required',
            'remarks2'=>'required',
            'remarks3'=>'required',
            'status_active'=>'required',
            'photo_name'=>'required'

          ]);
  
          $masterDriver              = MasterDriver::findOrFail($id);
          $masterDriver->expedition_code = $request->input('expedition_code');
          $masterDriver->driver_id = $request->input('driver_id');
          $masterDriver->driver_name     = $request->input('driver_name');
          $masterDriver->driving_lisence_type  = $request->input('driving_lisence_type');
          $masterDriver->driving_lisence_number = $request->input('driving_lisence_number');
          $masterDriver->ktp_no     = $request->input('ktp_no');
          $masterDriver->phone1   = $request->input('phone1');  
          $masterDriver->phone2   = $request->input('phone2');  
          $masterDriver->remarks1   = $request->input('remarks1');  
          $masterDriver->remarks2   = $request->input('remarks2');
          $masterDriver->remarks3   = $request->input('remarks3'); 
          $masterDriver->status_active =!empty($request->input('status_active'));
          $masterDriver->photo_name     = $request->input('photo_name');  
          return $masterDriver->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return MasterDriver::destroy($id);
    }
    public function getSelect2ActiveExpedition(Request $request)
    {
      $query = BranchDriver::select(
        DB::raw("expedition_name AS id"),
        DB::raw("CONCAT(expedition_name, '-', expedition_code) AS text")
      )
        ->where('status_active', 1)
        ->toBase();
  
      return get_select2_data($request, $query);
    }
}

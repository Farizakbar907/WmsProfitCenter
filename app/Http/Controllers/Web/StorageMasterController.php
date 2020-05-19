<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\StorageMaster;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StorageMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
          $query = StorageMaster::all();

          $datatables = DataTables::of($query)
            ->addIndexColumn() //DT_RowIndex (Penomoran)
            ->addColumn('action', function ($data) {
              $action = '';
              $action .= ' ' . get_button_edit(url('master-area/' . $data->id . '/edit'));
              $action .= ' ' . get_button_delete();
              return $action;
            });

          return $datatables->make(true);
        }
        return view('web.master.storage-master.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('web.master.storage-master.create');
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
            'kode_cabang_id'        => 'required',
            'sto_loc_code_short'    => 'required',
            'sto_type_desc'         => 'required',
            'total_pallate'         => 'required|numeric',
            'used_space'            => 'numeric',
            'space_wh'              => 'numeric',
            'hand_pallet_space'     => 'numeric',
        ]);

        $storageMaster                     = new StorageMaster;
        $storageMaster->kode_cabang_id     = $request->input('kode_cabang_id');
        $storageMaster->sto_loc_code_short = $request->input('sto_loc_code_short');
        $cabang = \App\Models\MasterCabang::find($storageMaster->kode_cabang_id);
        $sto_loc_code_long = $storageMaster->sto_loc_code_short . $cabang->kode_cabang;
        $storageMaster->sto_loc_code_long  = $sto_loc_code_long;
        // $storageMaster->sto_type_id        = $request->input('');// yang ini tabel sto_type_id tp aku gak tau input e apa gak ada inputan e di form soal e
        $storageMaster->sto_type_desc      = $request->input('sto_type_desc');
        $storageMaster->total_max_pallet   = $request->input('total_pallate');
        $storageMaster->used_space         = $request->input('used_space');
        $storageMaster->space_wh           = $request->input('space_wh');
        $storageMaster->hand_pallet_space  = $request->input('hand_pallet_space');

        return $storageMaster->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

     public function getSelect2StorageType(Request $request)
    {
        $query = DB::table('storage_types')->select(
          'id',
          DB::raw("storage_type AS text")
        );

        return get_select2_data($request, $query);
    }
}

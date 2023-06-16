<?php

namespace App\Http\Controllers;

use App\Models\Sampah;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use AppendIterator;
use Exception;

class SampahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sampah = sampah::all();

        if ($sampah){
            return ApiFormatter::createAPI(200, 'success', $sampah);
        }else{
            return ApiFormatter::createAPI(400, 'failed');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
          try {
                 $request->validate([
                    'kepala_keluarga' => 'required',
                    'no_rumah' => 'required',
                    'rt_rw' => 'required',
                    'total_karung_sampah' => 'required',
                    'tanggal_pengangkutan' => 'required',

                 ]);

            $total_karung_sampah = $request['total_karung_sampah'];

            if ($total_karung_sampah > 3) {
                $kriteria = "collapse";
            }else {
                $kriteria = "standar";
            }
                 $sampah = Sampah::create([
                    'kepala_keluarga' => $request->kepala_keluarga,
                    'no_rumah' => $request->no_rumah,
                    'rt_rw' => $request->rt_rw,
                    'total_karung_sampah' => $request->total_karung_sampah,
                    'kriteria' => $kriteria,
                    'tanggal_pengangkutan' => $request->tanggal_pengangkutan,
                 ]); 

                 $data = Sampah::where('id', $sampah->id)->first();
                 if ($data) {
                    return ApiFormatter::createAPI(200, 'success', $sampah);
                 } else {
                    return ApiFormatter::createAPI(400, 'failed');
                 }
            } catch (Exception $error) {
                return ApiFormatter::createAPI(400, 'error', $error->getMessage());
            }
        }

        public function token()
        {
            return csrf_token();
        }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $sampah = sampah::find($id);
            if ($sampah) {
               return ApiFormatter::createAPI(200, 'succes', $sampah);
            }else{
                return ApiFormatter::createAPI(400, 'failed');
            }
        } catch (Exception $error) {
            return ApiFormatter::createApi(400, 'eror', $error->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sampah $sampah)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
        try {
               $request->validate([
                    'kepala_keluarga' => 'required',
                    'no_rumah' => 'required',
                    'rt_rw' => 'required',
                    'total_karung_sampah' => 'required',
                    'tanggal_pengangkutan' => 'required',
                ]);

                $sampah = sampah::find($id);
                $sampah->update([
                    'kepala_keluarga' => $request->kepala_keluarga,
                    'rt_rw' => $request->rt_rw,
                    'total_karung_sampah' => $request->total_karung_sampah,
                    'tanggal_pengangkutan' => $request -> tanggal_pengangkutan,
                ]);
                
                $data = Sampah::where('id', $sampah->id)->first();
                if ($data) {
                   return ApiFormatter::createAPI(200, 'success', $sampah);
                } else {
                   return ApiFormatter::createAPI(400, 'failed');
                }
           } catch (Exception $error) {
               return ApiFormatter::createAPI(400, 'error', $error->getMessage());
           }

        
    }

    
    public function destroy($id)
    {
        try{
            $sampah = sampah::find($id);
            $cekBerhasil = $sampah->delete();
            if($cekBerhasil) {
                return ApiFormatter::createAPI(200, 'success', 'Data terhapus!' );
            }else{
                return ApiFormatter::createAPI(400, 'failed');
            }
        } catch (Exception $error) {
                     return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }

    public function trash()
    {
        try {
            $sampah = sampah::onlyTrashed()->get();
            if ($sampah) {
                return ApiFormatter::createAPI(200 , 'success', $sampah);
            }else{
                return ApiFormatter::createAPI(400, 'failed');
            }
        } catch (Exception $error) {
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }

    public function restore($id)
    {
        try{
            $sampah = sampah::onlyTrashed()->where('id', $id);
            $sampah->restore();
            $dataRestore = sampah::where('id', $id)->first();
            if($dataRestore) {
                return ApiFormatter::createAPI(200, 'success', $dataRestore);
            }else{
                return ApiFormatter::createApi(400, 'failed');
            }
        }catch (Exception $error) {
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }

    public function permanentDelete($id)
    {
        try{$sampah = sampah::onlyTrashed()->where('id', $id);
            $proses = $sampah->forceDelete();
            if ($proses){
                return ApiFormatter::createAPI(200, 'success', 'data dihapus permanent!');
            }else{
                return ApiFormatter::createAPI(400, 'failed');

            }
        } catch (Exception $error) {
             return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }

}
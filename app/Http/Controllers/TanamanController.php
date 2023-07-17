<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\Tanaman;
use Validator;


class TanamanController extends Controller
{
    function postTanaman(Request $request) {
        $validator = Validator::make($request->all(), [
            'id_pulau'   => 'required|exists:pulau,id',
            'nama' => 'required',
            'deskripsi' => 'required',
            'warna' => 'required',
            'foto' => 'required',
        ]);
        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'   => $validator->errors()
            ],401);


    
        }

        $tanaman = new Tanaman;
        $tanaman->id_pulau = $request->id_pulau;
        $tanaman->nama = $request->nama;
        $tanaman->deskripsi = $request->deskripsi;
        $tanaman->warna = $request->warna;
        $tanaman->foto = $request->foto;
        $tanaman->save();
        return response()->json([
            'success' => true,
            'message' => 'Sukses Post Tanaman',
            'data'   => $tanaman
        ],201);
    }
}
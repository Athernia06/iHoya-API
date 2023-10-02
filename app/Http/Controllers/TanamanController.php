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
            'foto' => 'required',
        ]);
        if ($validator->fails()) {
            
            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'   => $validator->errors()
            ],401);
        }
        
        //your base64 encoded data
        $image_64 = $request['foto']; 
        if ($this->getBase64ImageSize($image_64) > 2.048) {
            return response()->json('File to large, max 2MB', 400);
        }
        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
        if ($extension != "jpg" && $extension != "png" && $extension != "jpeg") {
            return response()->json(['File dengan extension '.$extension.' tidak diperbolehkan, silahkan gunakan file dengan extension .jpg, .png, .jpeg'], 400);
        }
        $replace = substr($image_64, 0, strpos($image_64, ',')+1); 
        $image = str_replace($replace, '', $image_64); 
        
        $image = str_replace(' ', '+', $image); 
        
        $imageName = Str::random(10).'.'.$extension;
        
        //Storage::disk('foto')->put($imageName, base64_decode($image));
        $file = base64_decode($request['foto']);
        //print_r(base_path().'/public');
        //die();
        file_put_contents(url().'/img/'.$folder.'/'.$imageName, $file);

        $pulau = Pulau::where('id', $request['pulau_id'])->first();
        $folder = $pulau->nama_pulau;
        
        //postTanaman
        $tanaman = new Tanaman;
        $tanaman->id_pulau = $request->id_pulau;
        $tanaman->nama = $request->nama;
        $tanaman->deskripsi = $request->deskripsi;
        $tanaman->foto = $request->foto;
        $tanaman->save();
        return response()->json([
            'success' => true,
            'message' => 'Sukses Post Tanaman',
            'data'   => $tanaman
        ],201);
    }
    
    function listTanaman()
    {
        $tanamanPerPulau = Tanaman::with('pulau')->get()->groupBy('id_pulau');
        return response()->json($tanamanPerPulau, 200);
    }
}
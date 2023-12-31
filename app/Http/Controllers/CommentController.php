<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Redirect,Response,File, Storage;
use Illuminate\Support\Str;

use App\Models\Post;
use App\Models\Forum;
use App\Models\Comment;
use App\Models\ForumBookmark;
use Validator;


class CommentController extends Controller
{
    function createComment(Request $request) {
        // Validasi permintaan
        $this->validate($request, [
            'deskripsi' => 'required|string',
        ]); 
        
        
        $image_64 = $request['foto']; //your base64 encoded data
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
        file_put_contents(base_path().'/public/forum/'.$imageName, $file);
        // Proses pembuatan posting dengan data yang telah divalidasi
        $user = $request->user(); // Mengambil informasi pengguna yang terautentikasi
        $comment = new Forum;
        $comment->id_user = $user->id;
        $comment->tanggal = date('Y-m-d');
        $comment->deskripsi = $request->input('deskripsi');
        $comment->foto = url().'/forum/'.$imageName;
        $comment->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Post created successfully',
            'data' => $post
        ], 201);
    }
    
    public function getForums()
    {
        $forums = Forum::with(['user', 'comments', 'bookmarks'])->get();
        return response()->json($forums, 200);
    }
    
    function getBase64ImageSize($base64Image){ //return memory size in B, KB, MB
        try{
            $size_in_bytes = (int) (strlen(rtrim($base64Image, '=')) * 3 / 4);
            $size_in_kb    = $size_in_bytes / 1024;
            $size_in_mb    = $size_in_kb / 1024;
            
            return $size_in_mb;
        }
        catch(Exception $e){
            return $e;
        }
    }
}
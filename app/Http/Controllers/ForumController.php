<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\Post;
use Validator;


class ForumController extends Controller
{
    function createPost(Request $request) {
        // Validasi permintaan
        $this->validate($request, [
            'deskripsi' => 'required|string',
            'foto' => 'foto|mimes:jpeg,png,jpg|max:2048',
        ]); 

        // Proses pembuatan posting dengan data yang telah divalidasi
        $user = $request->user(); // Mengambil informasi pengguna yang terautentikasi
        $post = new Post;
        $post->id_user = $user->id;
        $post->tanggal = date('Y-m-d');
        $post->deskripsi = $request->input('deskripsi');
        $post->foto = $request->file('foto') ? $request->file('foto')->store('foto') : null;
        $post->save();

        return response()->json(['message' => 'Post created successfully'], 201);
    }
}
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
use App\Models\Likes;
use Validator;


class ForumController extends Controller
{
    function createPost(Request $request) {
        // Validasi permintaan
        $this->validate($request, [
            'deskripsi' => 'required|string',
        ]); 
        
        if($request['foto'] != "") {
            // code foto
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
            
            file_put_contents(base_path().'/public/forum/'.$imageName, $file);
            $imageNamePath = url().'/forum/'.$imageName;
        } else {
            $imageNamePath = NULL;
        }
        
        // Proses pembuatan posting dengan data yang telah divalidasi
        $user = $request->user(); // Mengambil informasi pengguna yang terautentikasi
        $post = new Forum;
        $post->id_user = $user->id;
        $post->tanggal = date('Y-m-d');
        $post->deskripsi = $request->input('deskripsi');
        $post->foto = $imageNamePath;
        $post->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Post created successfully',
            'data' => $post
        ], 201);
    }
    
    public function getForums($userId=null)
    {
        if (isset($userId)) {
            $get_forum = ForumBookmark::where('id_user', $userId)->get();
            $forumId = [];
            foreach ($get_forum as $value) {
                array_push($forumId, $value->id_forum);
            }
            $forums = Forum::with(['user', 'comments', 'bookmarks'])->whereIn("id", $forumId)->get();
        } else {
            $forums = Forum::with(['user', 'comments', 'bookmarks'])->get();
        }
        
        return response()->json($forums, 200);
    }
    
    public function updateForums(Request $request)
    {
        $forums = Forum::find($request['id_forum']);
        
        if (!$forums) {
            return response()->json(['post' => 'Post Not Found'], 404);
        }
        
        $this->validate($request, [
            'deskripsi' => 'required|string'
        ]);
        
        $user = $request->all();
        $forums->fill($user);
        $forums->save();
        
        return response()->json($forums);
    }
    
    public function destroy(Request $request)
    {
        $forums = Forum::find($request['id_forum']);
        
        if (!$forums) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        
        $forums->delete();
        
        return response()->json(['message' => 'Post deleted successfully'], 200);
    }
    
    public function commentStore(Request $request)
    {
        $this->validate($request, [
            'deskripsi' => 'required|string',
        ]); 
        
        // Proses pembuatan posting dengan data yang telah divalidasi
        $user = $request->user(); // Mengambil informasi pengguna yang terautentikasi
        $post = new Comment;
        $post->id_user = $user->id;
        $post->id_forum = $request['id_forum'];
        $post->deskripsi = $request->input('deskripsi');
        $post->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Comment created successfully',
            'data' => $post
        ], 201);
    }
    
    public function commentUpdate(Request $request)
    {
        $this->validate($request, [
            'deskripsi' => 'required|string',
        ]); 
        
        // Proses pembuatan posting dengan data yang telah divalidasi
        $user = $request->user(); // Mengambil informasi pengguna yang terautentikasi
        $post = Comment::find($request['id_comment']);
        $post->deskripsi = $request->input('deskripsi');
        $post->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Comment updated successfully',
            'data' => $post
        ], 201);
    }
    
    public function commentDelete(Request $request)
    {   
        // Proses pembuatan posting dengan data yang telah divalidasi
        $post = Comment::find($request['id_comment']);
        $post->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully'
        ], 201);
    }
    
    public function Like(Request $request, $postId)
    {
        $user = Auth::user();
        $post = Forum::findOrFail($postId);
        
        $like = Likes::where('id_forum', $post->id)
        ->where('id_user', $user->id)
        ->first();
        
        if ($like) {
            $like->delete();
            $message = 'Post unliked successfully';
        } else {
            $like = new Likes([
                'id_forum' => $post->id,
                'id_user' => $user->id,
            ]);
            $like->save();
            $message = 'Post liked successfully';
        }
        
        return response()->json(['message' => $message]);
    }
    
    public function Share(Request $request, $postId)
    {
        $user = Auth::user();
        $post = Post::findOrFail($postId);
        
        $share = Share::where('id_forum', $post->id)
        ->where('id_user', $user->id)
        ->first();
        
        if ($share) {
            $share->delete();
            $message = 'Post unshared successfully';
        } else {
            $share = new Share([
                'id_forum' => $post->id,
                'id_user' => $user->id,
            ]);
            $share->save();
            $message = 'Post shared successfully';
        }
        
        return response()->json(['message' => $message]);
    }
    
    public function createBookmark(Request $request, $postId)
    {
        $user = Auth::user();
        $post = Forum::findOrFail($postId);
        
        $bookmark = ForumBookmark::where('id_forum', $post->id)
        ->where('id_user', $user->id)
        ->first();
        
        if ($bookmark) {
            $bookmark->delete();
            $message = 'Post unbookmarked successfully';
        } else {
            $bookmark = new ForumBookmark([
                'id_forum' => $post->id,
                'id_user' => $user->id,
            ]);
            $bookmark->save();
            $message = 'Post bookmarked successfully';
        }
        
        return response()->json(['message' => $message]);
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
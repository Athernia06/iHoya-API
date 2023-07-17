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

        return response()->json([
            'success' => true,
            'message' => 'Post created successfully',
            'data' => $post
        ], 201);
    }

    public function Like(Request $request, $postId)
    {
        $user = Auth::user();
        $post = Post::findOrFail($postId);

        $like = Like::where('id_forum', $post->id)
            ->where('id_user', $user->id)
            ->first();

        if ($like) {
            $like->delete();
            $message = 'Post unliked successfully';
        } else {
            $like = new Like([
                'id_forum' => $post->id,
                'id_user' => $user->id,
            ]);
            $like->save();
            $message = 'Post liked successfully';
        }

        return response()->json(['message' => $message]);
    }

    public function createComment(Request $request, $postId)
    {
        $this->validate($request, [
            'deskripsi' => 'required|string',
        ]);

        $user = Auth::user();
        $post = Post::findOrFail($postId);

        $comment = new Comment([
            'id_forum' => $post->id,
            'id_user' => $user->id,
            'deskripsi' => $request->input('deskripsi'),
        ]);

        $comment->save();

        return response()->json(['message' => 'Comment created successfully']);
    }

    public function createShare(Request $request, $postId)
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
        $post = Post::findOrFail($postId);

        $bookmark = Bookmark::where('id_forum', $post->id)
            ->where('id_user', $user->id)
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            $message = 'Post unbookmarked successfully';
        } else {
            $bookmark = new Bookmark([
                'id_forum' => $post->id,
                'id_user' => $user->id,
            ]);
            $bookmark->save();
            $message = 'Post bookmarked successfully';
        }

        return response()->json(['message' => $message]);
    }
}
<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Support\Facades\Auth;
use Validator;
class PostController extends Controller
{
public $successStatus = 200;

    /**
     * Create Post
     *
     * @return \Illuminate\Http\Response
     */
      public function createPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
            'image' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
              'message' => "The given data was invalid.",
              'errors' => $validator->errors()
            ], 422);
        }
        $input = $request->all();
        //return response()->json($input,201);
        // $input["user_id"] = Auth::user()->id;
        $input["user_id"] = 1;
        $post = Post::create($input);
        return response()->json(['data'=> [
          "title" => $post->title,
          "content" => $post->content,
          "slug" => str_replace(" ","-",$post->title),
          "updated_at" => $post->updated_at,
          "created_at" => $post->created_at,
          "id" => $post->id,
          "user_id" => $post->user_id
        ]], 201);
    }

    public function updatePost($post,Request $request)
    {
      $post = str_replace("-"," ",$post);

      $validator = Validator::make($request->all(), [
          'title' => 'required'
      ]);
      if ($validator->fails()) {
          return response()->json([
            'message' => "The given data was invalid.",
            'errors' => $validator->errors()
          ], 422);
      }
      $input = $request->all();
      $postData = Post::where('title', $post)->first();

      if(empty($postData)){
        return response()->json([
          'message' => "No Data in Database",
          'errors' => "There are no posts to update with title: $post"
        ], 422);
      }else{
        $postData->update(["title" => $input["title"]]);
        $updatedData = Post::where('id', $postData->id)->first();
      }

      return response()->json([
        'data' => $updatedData,
      ], 200);
    }

    public function deletePost($post)
    {
      $post = str_replace("-"," ",$post);
      $postData = Post::where('title', $post)->first();
      if(empty($postData)){
        return response()->json([
          'message' => "No Data in Database",
          'errors' => "There are no posts to delete with title: $post"
        ], 422);
      }else{
        $postData->delete();
        return response()->json([
          'status' => "record deleted successfully",
        ], 200);
      }

    }

}

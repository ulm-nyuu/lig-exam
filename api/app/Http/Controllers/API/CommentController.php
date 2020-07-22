<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Comment;
use App\Post;
use Illuminate\Support\Facades\Auth;
use Validator;
class CommentController extends Controller
{
public $successStatus = 200;

    /**
     * Create Post
     *
     * @return \Illuminate\Http\Response
     */
      public function createComment($comment, Request $request)
    {
        $comment = str_replace("-"," ",$comment);
        $validator = Validator::make($request->all(), [
            'body' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
              'message' => "The given data was invalid.",
              'errors' => $validator->errors()
            ], 422);
        }
        $postData = Post::where('title', $comment)->first();
        if(empty($postData)){
          return response()->json([
            'message' => "No Data in Database",
            'errors' => "There are no posts with title: $comment"
          ], 422);
        }
        $input = $request->all();
        $input["commentable_type"] = "App\\Post";
        $input["commentable_id"] = $postData->id;
        $input["creator_id"] = 1; // Auth::user()->id;
        $input["parent_id"] = null;
        $commentCreated = Comment::create($input);
        return response()->json(['data'=> $commentCreated ], 201);
    }

    public function updateComment($post,$comment,Request $request)
    {
      $post = str_replace("-"," ",$post);

      $validator = Validator::make($request->all(), [
          'body' => 'required'
      ]);
      if ($validator->fails()) {
          return response()->json([
            'message' => "The given data was invalid.",
            'errors' => $validator->errors()
          ], 422);
      }
      $input = $request->all();

      $postData = Post::where('title', $post)->first();
      $commentData = Comment::where('commentable_id', $postData->id)->where('id',$comment)->first();

      if(empty($postData)){
        return response()->json([
          'message' => "No Data in Database",
          'errors' => "There are no posts to update with title: $post"
        ], 422);
      }else{
        $commentData->update(["body" => $input["body"]]);
        $updatedData = Comment::where('id', $commentData->id)->first();
      }

      return response()->json([
        'data' => $updatedData,
      ], 200);
    }

    public function deleteComment($post,$comment)
    {
      $post = str_replace("-"," ",$post);
      $postData = Post::where('title', $post)->first();
      $commentData = Comment::where('commentable_id', $postData->id)->where('id',$comment)->first();
      if(empty($postData)){
        return response()->json([
          'message' => "No Data in Database",
          'errors' => "There are no comment to delete with title: $post"
        ], 422);
      }else{
        $commentData->delete();
        return response()->json([
          'status' => "record deleted successfully",
        ], 200);
      }

    }

}

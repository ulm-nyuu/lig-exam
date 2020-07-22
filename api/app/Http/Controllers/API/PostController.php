<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
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
            'slug' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $input = $request->all();
        $post = Posts::create($input);
        $success['token'] =  $post->createToken('MyApp')->accessToken;
        return response()->json(['success'=>$success], $this-> successStatus);
    }

}

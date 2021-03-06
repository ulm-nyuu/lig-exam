<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
class UserController extends Controller
{
public $successStatus = 200;
    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request){
      $validator = Validator::make($request->all(), [
          'email' => 'required|email',
          'password' => 'required'
      ]);
      if ($validator->fails()) {
        return response()->json(
          [
            'message' => "The given data was invalid.",
            'errors'=> $validator->errors()
          ], 422);
      }else{
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('lig')->accessToken;
            $success['token_type'] =  "bearer";
            $success['expires_at'] =  date("Y-m-d H:i:s",strtotime($user->createToken('lig')->token->expires_at));
            return response()->json($success, $this-> successStatus);
        }
        else{
            return response()->json(
              [
                'message' => "The given data was invalid.",
                'errors'=> [
                    "email" => [ "These credentials do not match our records." ]
                ]
              ], 422);
        }
      }
    }
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ]);
        if ($validator->fails()) {
          return response()->json([
            "message" => "The given data was invalid.",
            'errors'=> $validator->errors()
          ], 422);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['name'] =  $user->name;
        $success['email'] =  $user->email;
        $success['updated_at'] =  date("Y-m-d H:i:s",strtotime($user->updated_at));
        $success['created_at'] =  date("Y-m-d H:i:s",strtotime($user->created_at));
        $success['id'] =  $user->id;
        return response()->json($success,201);
    }

    /**
     * Logout api
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        if (Auth::check()) {
           Auth::user()->AauthAcessToken()->delete();
        }
    }

}

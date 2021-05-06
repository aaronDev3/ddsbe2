<?php
namespace App\Http\Controllers;

use App\Models\UserJob;
use App\Models\User;
use Illuminate\Http\Response;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use DB;


Class UserController extends Controller {
    use ApiResponser;

    private $request;

    public function __construct(Request $request){
    $this->request = $request;
    }

    public function getUsers(){
        
        $users = DB::connection('mysql')->select("Select * from tbluser");

        //return response()->json($users, 200);
        return $this->successResponse($users);
    
    }

    public function index(Request $request){

        $username = $request->input('username');
        $password = $request->input('password');

        $users1 = DB::connection('mysql')->select("SELECT * FROM `tbluser` WHERE `username` LIKE '$username' AND `password` LIKE '$password'");

        if($users1){
            
            $user = User::where('username',$username)->first();
            return $this->successResponse($user);
            
        }elseif($username==null&&$password==null){

            $users = User::all();
            return $this->successResponse($users);

        }else{

            return "Invalid username and password";

        }
    }

    public function addUser(Request $request ){

        $rules = [
            'username' => 'required|max:20',
            'password' => 'required|max:20',
            'gender' => 'required|in:Male,Female',
            'jobid' => 'required|numeric|min:1|not_in:0',
        ];

        $this->validate($request,$rules);

        // validate if Jobid is found in the table tbluserjob
        $userjob = UserJob::findOrFail($request->jobid);

        $user = User::create($request->all());
        return $this->successResponse($user,Response::HTTP_CREATED);
    }

        /**
        * Obtains and show one user
        * @return Illuminate\Http\Response
        */
    
    public function show($id){

        $user = User::findOrFail($id);
        return $this->successResponse($user);

    }

        /**
        * Update an existing author
        * @return Illuminate\Http\Response
        */

    public function update(Request $request,$id){

        $rules = [
            'username' => 'max:20',
            'password' => 'max:20',
            'gender' => 'in:Male,Female',
            'jobid' => 'required|numeric|min:1|not_in:0',
        ];

        $this->validate($request, $rules);

         // validate if Jobid is found in the table tbluserjob
        $userjob = UserJob::findOrFail($request->jobid);

        $user = User::findOrFail($id);
        $user->fill($request->all());

        if ($user->isClean()) {

            return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
       
        }

        $user->save();
        return $this->successResponse($user);
       
    }

        /**
        * Remove an existing user
        * @return Illuminate\Http\Response
        */

    public function delete($id){

        $user = User::findOrFail($id);
        $user->delete();
        return $this->successResponse($user);    
    
    }

}



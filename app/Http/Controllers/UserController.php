<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;

Class UserController extends Controller {
 private $request;
 public function __construct(Request $request){
 $this->request = $request;
 }
 public function getUsers(){
 $users = User::all();
 //return $this->response($users, 200);
 return response()->json($users, 200);
 
 }
}


<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\user;

class UserController extends Controller
{
	public function index(Request $request){

		$key = $request->has('query') ? $request->get('query') : null;
		if($key) {
			$users = User::where('name', 'LIKE', '%'. $key .'%')
						->orwhere('email', 'LIKE', '%'. $key .'%')
						->orwhere('address', 'LIKE', '%'. $key .'%')
						->orwhere('phone', 'LIKE', '%'. $key .'%')
						->orwhere('born', 'LIKE', '%'. $key .'%')
						->orwhere('hobby', 'LIKE', '%'. $key .'%')
						->paginate(10);
		} else {
			$users = User::paginate(10);
		}
	
		return view('user.index', compact('users'));
	}

	public function create() {
		return view('user.create');
	}

	public function store(Request $request) {

		$request->validate([
			'name' => 'required|string|min:5',
			'email' => 'required|string|min:5|unique:users',
			'address' => 'required|string',
			'born' => 'required|date',
			'hobby' => 'required|string',
			'phone' => 'required|string',
			'password' => 'required|string|confirmed',
		]);
		
		User::create($request->all());

		return redirect()->route('user.index')->with('success', 'Successfuly create new user');
	}
}

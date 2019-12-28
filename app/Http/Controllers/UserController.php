<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\user;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Storage;

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
						->paginate(5);
		} else {
			$users = User::paginate(5);
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
			'profile' => 'required|file|image'
		]);
		$file =  $request->file('profile');

		$filename = sha1($file->getClientoriginalName() . Carbon::now() . mt_rand()). '.'.$file->getClientoriginalExtension();
		$user = (object) $request->all();
		$file->storeAs('public/user/images', $filename);
		$user->profile = $filename;
		$user->password = bcrypt($request->password);
	
		User::create((array) $user);


		return redirect()->route('user.index')->with('success', 'Successfuly create new user');
	}


	public function edit($id) {
		$user = User::findOrFail($id);
		return view('user.edit', compact('user'));
	}

	public function update(Request $request) {

		$user = user::findOrFail($request->id);

		$request->validate([
			'name' => 'required|string|min:5',
			'email' => [
				'required',
				'string',
				'min:5',
				Rule::unique('users')->ignore($user->id)
			],
			'address' => 'required|string',
			'born' => 'required|date',
			'hobby' => 'required|string',
			'phone' => 'required|string'
		]);

		if($request->profile) {
			$request->validate(['profile' => 'required|file|image']);

			$file =  $request->file('profile');
			$filename = sha1($file->getClientoriginalName() . Carbon::now() . mt_rand()). '.'.$file->getClientoriginalExtension();
		
			$file->storeAs('public/user/images',$filename);

			$path = 'public/user/images/'.$user->profile;

			if(Storage::exists($path)) {
				Storage::delete($path);
			}

			$user->profile = $filename;
		}

		if($request->password) {
			$request->validate( ['password' => 'required|string|confirmed'] );
			$user->password = bcrypt($request->password);
		}

		$user->name = $request->name;
		$user->email = $request->email;
		$user->address = $request->address;
		$user->born = $request->born;
		$user->hobby = $request->hobby;
		$user->phone = $request->phone;

		$user->save();

		return redirect()->route('user.index')->withSuccess('Successfuly updatinng user');
	}

	public function delete(Request $request){
		$user = User::findOrFail($request->id);
		
		$path = 'public/user/images/'.$user->profile;

		if(Storage::exists($path)) {
			Storage::delete($path);
		}

		$user->delete();
		return redirect()->route('user.index')->withSuccess('Successfuly Deleting User');
	}
}
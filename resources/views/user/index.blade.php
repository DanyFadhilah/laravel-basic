@extends("layouts.main")

@section('content')
@if(\Session::has('success'))
<div class="alert alert-success">{{ \Session::get('success') }}</div>
@endif
	<div class="w-100 mb-3 mt-2 clearfix">
		<a href="{{ route('user.create') }}" class="btn btn-info text-white px-4 float-right">Add User</a>
	</div>
	<div class="card">	
		<div class="card-header">
			<h4 class="mb-0 float-left">List Users</h4>
			<div class="float-right">
				<form action="{{ route('user.index') }}" method="get">
					<input type="text" class="form-control" name="query" placeholder="search" value="{{ app('request')->input('query') }}">
				</form>
			</div>	
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-stripped table-bordered">
				<thead class="bg-primary text-white">
					<th>No</th>
					<th>profile</th>
					<th>Email</th>
					<th>Name</th>
					<th>Address</th>
					<th>Phone</th>
					<th>Born</th>
					<th>Age</th>
					<th>Hobby</th>
					<th>Action</th>
				</thead>
				<tbody>
					@foreach($users as $key => $user)
					<tr>
						<td>{{ $key +1 }}</td>
						<td>
							<img src="{{ $user->profile ? URL::to('storage/user/images/'. $user->profile) : asset('images/default.png') }}" height="70" alt="">
						</td>
						<td>{{ $user->email }}</td>
						<td>{{ $user->name }}</td>
						<td>{{ $user->address }}</td>
						<td>{{ $user->phone }}</td>
						<td>{{ $user->born }}</td>
						<td>{{ \Carbon\Carbon::parse($user->born)->diffForHumans(null, true) }}</td>
						<td>{{ $user->hobby }}</td>
						<td>
							<a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary btn-sm text-white">Edit</a>
							<form method="POST" action=" {{ route('user.delete') }}" class="floa-left">
								@csrf
								{{ method_field('DELETE') }}
								<input type="hidden" name="id" value="{{ $user->id }}">
								<button type="submit" class="btn btn-danger btn-sm text-white">Delete</button>
							</form>

						</td>
					</tr>
					@endforeach
				</tbody>
				</table>
				{{ $users->links() }}
			</div>
		</div>
	</div>
@endsection
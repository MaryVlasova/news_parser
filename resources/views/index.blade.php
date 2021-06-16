@extends('layout')

@section('title', 'Users')

@section('content')


@if (session('success'))
    <div class="alert alert-success" role="alert">{{session('success')}}</div> 
@endif

<table class="table table-light table-striped">
  <thead>
    <tr>
      <th scope="col">id</th>
      <th scope="col">Name</th>
      <th scope="col">Email</th>
      <th colspan="2" scope="col">Action</th>
    </tr>
  </thead>
  <tbody>

  
      <!-- @foreach($users as $user)
        <tr>
            <th scope="row">{{$user->id}}</th>
            <td>{{$user->name}}</td>
            <td>{{$user->email}}</td>
            <td>
                <a class="btn btn-primary mb-2" href="{{route('users.edit', $user)}}" role="button">
                    Edit
                </a> 
            </td>
            <td>  
                <form method="POST" class="mb-2" action="{{route('users.destroy', $user)}}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit" role="button">Delete</button>
                </form>                             
            </td>
        </tr>
      @endforeach -->

      

  </tbody>
</table>

{{ $users->links('pagination::bootstrap-4') }}
<div class="text-start">
<a class="btn btn-primary mb-4" role="button" href="{{route('users.create')}}">Create</a>
</div>


@endsection
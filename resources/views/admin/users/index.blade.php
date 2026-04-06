@extends('admin.layouts.master')

@section('title', 'User List')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>All Users</h4>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-dark btn-sm">← Back</a>
</div>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($users as $key => $user)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->created_at->format('d M Y') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">No users found</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-3">
    {{ $users->links() }}
</div>
@endsection

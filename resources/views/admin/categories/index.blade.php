@extends('admin.layouts.master')
@section('title', 'Categories')
@section('content')
<div class="card shadow-sm border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div class="d-flex align-items-center gap-2">
        <a href="{{ url()->previous() }}" class="btn btn-dark btn-sm">
          ← Back
        </a>
        <h4 class="mb-0">All Categories</h4>
      </div>
      <a href="{{ route('admin.categories.create') }}" class="btn btn-dark btn-sm">+ Add New</a>
    </div>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Description</th>
          <th>Image</th> <!-- 🔹 New column -->
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($categories as $cat)
          <tr>
            <td>{{ $cat->id }}</td>
            <td>{{ $cat->name }}</td>
            <td>{{ $cat->description }}</td>
            <!-- <td>{{ $cat->description }}</td> -->
            <td>
                @if($cat->image) 
                    <img src="{{ asset('uploads/categories/' . $cat->image) }}" alt="Image" style="width:50px; height:50px; object-fit:cover; border-radius:4px;">
                @else
                    <span class="text-muted">No Image</span>
                @endif
            </td>

            <td>{{ $cat->status ? 'Active' : 'Inactive' }}</td>
            <td>
              <a href="{{ route('admin.categories.edit', $cat->id) }}" class="btn btn-dark btn-sm">Edit</a>
              <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" class="d-inline">
                @csrf @method('DELETE')
                <button class="btn btn-dark btn-sm" onclick="return confirm('Delete this category?')">Delete</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection

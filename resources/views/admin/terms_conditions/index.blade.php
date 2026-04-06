@extends('admin.layouts.master')
@section('title', 'Terms & Conditions')
@section('content')
<div class="card shadow-sm border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>All Terms & Conditions</h4>
        <a href="{{ route('admin.terms_conditions.create') }}" class="btn btn-dark btn-sm">+ Add New</a>
    </div>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Content</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($terms as $term)
          <tr>
            <td>{{ $term->id }}</td>
            <td>{{ $term->title }}</td>
            <td>{!! \Illuminate\Support\Str::limit(strip_tags($term->content), 50, '...') !!}</td>
            <td>{{ $term->status ? 'Active' : 'Inactive' }}</td>
            <td>
                <a href="{{ route('admin.terms_conditions.edit', $term->id) }}" class="btn btn-dark btn-sm">Edit</a>
                <form action="{{ route('admin.terms_conditions.destroy', $term->id) }}" method="POST" class="d-inline">
                  @csrf @method('DELETE')
                  <button class="btn btn-dark btn-sm" onclick="return confirm('Delete this term?')">Delete</button>
                </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection

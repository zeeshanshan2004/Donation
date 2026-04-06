@extends('admin.layouts.master')

@section('title', 'Manage FAQs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ url()->previous() }}" class="btn btn-dark btn-sm">
            ← Back
        </a>
        <h4 class="mb-0">FAQ List</h4>
    </div>
   <a href="{{ route('admin.faqs.create') }}" class="btn btn-dark btn-sm">+ Add FAQ</a>

</div>



@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Question</th>
            <th>Answer</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($faqs as $faq)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $faq->question }}</td>
            <td>{{ Str::limit($faq->answer, 50) }}</td>
            <td>{{ $faq->is_active ? 'Active' : 'Inactive' }}</td>
         <td>
    <a href="{{ route('admin.faqs.edit', $faq->id) }}" class="btn btn-sm btn-dark">Edit</a>
    <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST" style="display:inline-block;">
        @csrf 
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-dark" onclick="return confirm('Delete this FAQ?')">
            Delete
        </button>
    </form>
</td>

        </tr>
        @endforeach
    </tbody>
</table>
@endsection

@extends('admin.layouts.master')

@section('title', 'Add FAQ')

@section('content')
<h4>Add New FAQ</h4>

<form action="{{ route('admin.faqs.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Question</label>
        <input type="text" name="question" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Answer</label>
        <textarea name="answer" class="form-control" rows="4" required></textarea>
    </div>

    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
        <label class="form-check-label">Active</label>
    </div>

<button class="btn btn-dark">Save FAQ</button>

</form>
@endsection

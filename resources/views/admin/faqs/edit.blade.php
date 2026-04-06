@extends('admin.layouts.master')

@section('title', 'Edit FAQ')

@section('content')
<h4>Edit FAQ</h4>

<form action="{{ route('admin.faqs.update', $faq->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Question</label>
        <input type="text" name="question" class="form-control" value="{{ $faq->question }}" required>
    </div>

    <div class="mb-3">
        <label>Answer</label>
        <textarea name="answer" class="form-control" rows="4" required>{{ $faq->answer }}</textarea>
    </div>

    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $faq->is_active ? 'checked' : '' }}>
        <label class="form-check-label">Active</label>
    </div>

    <button class="btn btn-primary">Update FAQ</button>
</form>
@endsection

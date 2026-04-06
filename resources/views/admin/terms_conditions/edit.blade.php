@extends('admin.layouts.master')

@section('title', 'Edit Terms & Conditions')

@section('content')
<div class="card shadow-sm border-0 p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Edit Terms & Conditions</h4>
        <a href="{{ url()->previous() }}" class="btn btn-dark btn-sm">← Back</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.terms_conditions.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <textarea id="summernote" name="content">{{ old('content', $terms_condition->content) }}</textarea>
        </div>

        <button type="submit" class="btn btn-dark">Save</button>
    </form>
</div>

<!-- Summernote CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.js"></script>

<script>
$(document).ready(function() {
    $('#summernote').summernote({
        height: 400,              // Editor height
        tabsize: 2,               // Tab spacing
        focus: true,              // Focus editor on load
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
});
</script>
@endsection

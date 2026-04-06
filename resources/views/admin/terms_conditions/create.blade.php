@extends('admin.layouts.master')
@section('title', 'Add Terms & Conditions')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Add Terms & Conditions</h4>
    <a href="{{ route('admin.terms_conditions.index') }}" class="btn btn-dark btn-sm">← Back</a>
</div>

<div class="card shadow-sm border-0 p-4">
    <form action="{{ route('admin.terms_conditions.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-bold">Title</label>
            <input type="text" name="title" class="form-control" placeholder="Enter title" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Content</label>
            <textarea name="content" class="form-control summernote" rows="6"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Status</label>
            <select name="status" class="form-select">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>

        <button type="submit" class="btn btn-dark">Save</button>
    </form>
</div>

@endsection

@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 200
        });
    });
</script>
@endsection

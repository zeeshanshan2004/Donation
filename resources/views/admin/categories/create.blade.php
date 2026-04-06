@extends('admin.layouts.master')

@section('title', 'Add New Category')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Add New Category</h4>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-dark btn-sm">← Back</a>
</div>

<div class="card shadow-sm border-0 p-4">
    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-bold">Category Name</label>
            <input type="text" name="name" class="form-control" placeholder="Enter category name" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Description</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Enter category description"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Status</label>
            <select name="status" class="form-select">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Category Image</label>
            <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(event)">
            <div class="mt-3">
                <img id="preview" src="#" alt="Preview" style="display:none; width:100px; height:100px; object-fit:cover; border-radius:6px; border:1px solid #ccc;">
            </div>
        </div>

        <button type="submit" class="btn btn-dark">Save Category</button>
    </form>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    preview.src = URL.createObjectURL(event.target.files[0]);
    preview.style.display = 'block';
}
</script>
@endsection

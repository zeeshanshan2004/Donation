@extends('admin.layouts.master')

@section('title', 'Edit Category')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Edit Category</h4>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-dark btn-sm">← Back</a>
</div>

<div class="card shadow-sm border-0 p-4">
    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label fw-bold">Category Name</label>
            <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ $category->description }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Status</label>
            <select name="status" class="form-select">
                <option value="1" {{ $category->status == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ $category->status == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Current Image</label><br>
            @if ($category->image)
                <img src="{{ asset('uploads/categories/' . $category->image) }}" alt="Current Image" style="width:100px; height:100px; object-fit:cover; border-radius:6px; border:1px solid #ccc;">
            @else
                <span class="text-muted">No image uploaded</span>
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Change Image</label>
            <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(event)">
            <div class="mt-3">
                <img id="preview" src="#" alt="Preview" style="display:none; width:100px; height:100px; object-fit:cover; border-radius:6px; border:1px solid #ccc;">
            </div>
        </div>

        <button type="submit" class="btn btn-dark">Update Category</button>
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

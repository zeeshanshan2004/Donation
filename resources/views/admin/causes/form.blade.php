@extends('admin.layouts.master')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.js"></script>
<script>
$('.summernote').summernote({height:150});
</script>

<form 
    action="{{ $cause ? route('admin.causes.update', $cause->id) : route('admin.causes.store') }}" 
    method="POST" 
    enctype="multipart/form-data"
    class="p-4 bg-white rounded shadow-sm"
    style="max-width: 900px; margin:auto;"
>
    @csrf
    @if($cause)
        @method('PUT')
    @endif

    <div class="mb-4 text-center">
        <h4 class="fw-bold">{{ $cause ? 'Edit Cause' : 'Create New Cause' }}</h4>
        <hr class="w-25 mx-auto">
    </div>

    <div class="form-group mb-3">
    <label for="category_id">Category</label>
    <select name="category_id" id="category_id" class="form-control" required>
        <option value="">-- Select Category --</option>
        @foreach($categories as $id => $name)
            <option value="{{ $id }}" {{ isset($cause) && $cause->category_id == $id ? 'selected' : '' }}>
                {{ $name }}
            </option>
        @endforeach
    </select>
</div>


    {{-- Title --}}
    <div class="mb-3">
        <label class="form-label fw-semibold">Title</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $cause->title ?? '') }}" required>
    </div>

    {{-- Image --}}
    <div class="mb-3">
        <label class="form-label fw-semibold">Image</label>
        <input type="file" name="image" class="form-control">
        @if($cause && $cause->image)
            <div class="mt-2">
                <img src="{{ asset('uploads/causes/'.$cause->image) }}" alt="cause image" width="100" height="100" class="rounded">
            </div>
        @endif
    </div>

    {{-- Target --}}
    <div class="mb-3">
        <label class="form-label fw-semibold">Target Amount</label>
        <input type="number" name="target" class="form-control" value="{{ old('target', $cause->target ?? '') }}" required>
    </div>

    {{-- Raised --}}
    <div class="mb-3">
        <label class="form-label fw-semibold">Raised Amount</label>
        <input type="number" name="raised" class="form-control" value="{{ old('raised', $cause->raised ?? '') }}">
    </div>

    {{-- Heading --}}
    <div class="mb-3">
        <label class="form-label fw-semibold">Heading</label>
        <input type="text" name="heading" class="form-control" value="{{ old('heading', $cause->heading ?? '') }}">
    </div>

    {{-- By (Author / Organization) --}}
    <div class="mb-3">
        <label class="form-label fw-semibold">By (Author)</label>
        <input type="text" name="author" class="form-control" value="{{ old('author', $cause->author ?? '') }}">
    </div>
    
    {{-- By (Author Image) --}}
    <div class="mb-3">
        <label class="form-label fw-semibold">Author Image</label>
        <input type="file" name="author_image" class="form-control">
        @if($cause && $cause->author_image)
            <div class="mt-2">
                <img src="{{ asset('uploads/author_image/'.$cause->author_image) }}" alt="cause image" width="100" height="100" class="rounded">
            </div>
        @endif
    </div>

    {{-- Days Left --}}
    <div class="mb-3">
        <label class="form-label fw-semibold">Days Left</label>
        <input type="number" name="days_left" class="form-control" value="{{ old('days_left', $cause->days_left ?? '') }}">
    </div>

    {{-- Featured Toggle --}}
<!-- <div class="mb-3">
    <label class="form-label fw-semibold">Featured</label>
    <select name="is_featured" class="form-select">
        <option value="0" {{ old('is_featured', $cause->is_featured ?? 0) == 0 ? 'selected' : '' }}>No</option>
        <option value="1" {{ old('is_featured', $cause->is_featured ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
    </select>
</div> -->



    {{-- Status Toggle --}}
    <!-- <div class="mb-3">
        <label class="form-label fw-semibold">Status</label>
        <select name="status" class="form-select">
            <option value="1" {{ old('status', $cause->status ?? '') == 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('status', $cause->status ?? '') == 0 ? 'selected' : '' }}>Inactive</option>
        </select>
    </div> -->

    {{-- Description --}}
    <div class="mb-3">
        <label class="form-label fw-semibold">Description</label>
        <textarea name="description" rows="5" class="form-control">{{ old('description', $cause->description ?? '') }}</textarea>
    </div>

    {{-- Submit Button --}}
    <div class="text-end">
        <button type="submit" class="btn btn-dark px-4">
            {{ $cause ? 'Update Cause' : 'Create Cause' }}
        </button>
    </div>
</form>



@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.js"></script>
<script>
$(document).ready(function() {
    $('.summernote').summernote({ height: 150 });
});
</script>
@endpush
@endsection


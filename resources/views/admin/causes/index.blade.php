@extends('admin.layouts.master')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-dark fs-4">All Causes</h1>
        <a href="{{ route('admin.causes.create') }}" class="btn btn-dark btn-sm shadow-sm">Add New Cause</a>
    </div>

    <!-- 🔍 Filters -->
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by title or author...">
        </div>
        <div class="mb-3">
    <label class="form-label">Featured</label>
    <select name="featured" class="form-select">

        <option value="0" {{ old('featured', $cause->featured ?? 0) == 0 ? 'selected' : '' }}>No</option>
        <option value="1" {{ old('featured', $cause->featured ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
    </select>
</div>

        <div class="col-md-2">
            <button class="btn btn-dark w-100">Filter</button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('admin.causes.index') }}" class="btn btn-secondary w-100">Reset</a>
        </div>
    </form>

    <!-- 📋 Data Table -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Target</th>
                        <th>Raised</th>
                        <th>Days Left</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse($causes as $index => $cause)
                        <tr>
                            <td>{{ $causes->firstItem() + $index }}</td>
                            <td>
                                @if($cause->image)
                                    <img src="{{ asset('uploads/causes/'.$cause->image) }}" width="60" height="50" class="rounded">
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="fw-semibold text-start">{{ $cause->title }}</td>
                            <td>{{ $cause->author ?? '-' }}</td>
                            <td>${{ number_format($cause->target, 0) }}</td>
                            <td>${{ number_format($cause->raised, 0) }}</td>
                            <td>{{ $cause->days_left ?? '-' }}</td>
                            <!-- <td>
                                <span class="badge {{ $cause->status ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $cause->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td> -->

                        <td>
                            <div class="form-check form-switch d-flex justify-content-center">
                                <input class="form-check-input toggle-status" type="checkbox" data-id="{{ $cause->id }}" {{ $cause->status ? 'checked' : '' }}>
                            </div>
                        </td>



                           <!-- <td>
    <span class="badge {{ $cause->is_featured ? 'bg-primary' : 'bg-light text-dark border' }}">
        {{ $cause->is_featured ? 'Yes' : 'No' }}
    </span>
</td> -->

                <td class="text-center">
                    <div class="form-check form-switch d-flex justify-content-center">
                        <input class="form-check-input toggle-featured" type="checkbox" data-id="{{ $cause->id }}" {{ $cause->featured ? 'checked' : '' }}>
                    </div>
                </td>



                            <td>
                                <a href="{{ route('admin.causes.edit', $cause->id) }}" class="btn btn-sm btn-dark">Edit</a>
                                <form action="{{ route('admin.causes.destroy', $cause->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-dark">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">No causes found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- 🔽 Pagination -->
            <div class="mt-3 d-flex justify-content-end">
                {{ $causes->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.table thead th {
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: .5px;
}
.table td {
    vertical-align: middle;
    font-size: 0.9rem;
}
.btn-dark {
    background-color: #1c1c1c;
    color: #fff;
}
.btn-outline-dark {
    border-color: #1c1c1c;
    color: #1c1c1c;
}
.btn-outline-dark:hover {
    background-color: #1c1c1c;
    color: #fff;
}
</style>


<script>
document.querySelectorAll('.toggle-status').forEach(function(switchEl) {
    switchEl.addEventListener('change', function() {
        const causeId = this.dataset.id;
        const status = this.checked ? 1 : 0;

        fetch(`https://checkyourproject.info/Kelsie-Lichtenfeld/admin/causes/${causeId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status })
        })
        .then(res => res.json())
        .then(data => {
            if(!data.success) {
                alert(data.message || 'Something went wrong!');
                this.checked = !this.checked; // revert if fail
            }
        })
        .catch(err => {
            alert('Something went wrong!');
            this.checked = !this.checked; // revert if error
        });
    });
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.toggle-featured').forEach(function(el){
        el.addEventListener('change', function() {
            const causeId = this.dataset.id; 

            fetch(`https://checkyourproject.info/Kelsie-Lichtenfeld/admin/causes/${causeId}/toggle-featured`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(!data.success){
                    alert('Something went wrong!');
                    this.checked = !this.checked; // revert toggle
                }
            })
            .catch(() => {
                alert('Something went wrong!');
                this.checked = !this.checked; // revert toggle
            });
        });
    });
});
</script>




@endsection

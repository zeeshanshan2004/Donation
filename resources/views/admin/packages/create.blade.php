@extends('admin.layouts.master')
@section('title', 'Add New Package')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Add New Package</h4>
        <a href="{{ route('admin.packages.index') }}" class="btn btn-dark btn-sm">← Back</a>
    </div>

    <div class="card shadow-sm border-0 p-4">
        <form action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Package Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter package name" required>
                </div>

             <div class="col-md-6 mb-3">
    <label class="form-label fw-bold">Package Icon</label>
    {{-- 'accept="image/*"' dalne se sirf photos show hongi selection window mein --}}
    <input type="file" name="icon" class="form-control" accept="image/png, image/jpeg, image/jpg" required>
    <small class="text-muted">Select a PNG or JPG icon.</small>
</div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Package Amount</label>
                    <input type="number" step="0.01" name="amount" class="form-control" placeholder="0.00" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Referral Required (numbers)</label>
                    <input type="number" name="referral_required" class="form-control" placeholder="e.g. 5" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tax Percentage (%)</label>
                    <input type="number" step="0.01" name="tax_percentage" class="form-control" value="6.00" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Community Share</label>
                    <input type="number" step="0.01" name="community_share" class="form-control" value="0.00" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-dark">Save Package</button>
        </form>
    </div>
@endsection
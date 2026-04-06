@extends('admin.layouts.master')
@section('title', 'Edit Package')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Edit Package: {{ $package->name }}</h4>
        <a href="{{ route('admin.packages.index') }}" class="btn btn-dark btn-sm">← Back</a>
    </div>

    <div class="card shadow-sm border-0 p-4">
        <form action="{{ route('admin.packages.update', $package->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Package Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $package->name }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Update Icon</label>
                    <div class="d-flex align-items-center gap-3">
                        @if($package->icon)
                            <img src="{{ asset('storage/' . $package->icon) }}" width="50" class="rounded border">
                        @endif
                        <input type="file" name="icon" class="form-control" accept="image/*">
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Package Amount</label>
                    <input type="number" step="0.01" name="amount" class="form-control" value="{{ $package->amount }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Referral Required</label>
                    <input type="number" name="referral_required" class="form-control" value="{{ $package->referral_required }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tax Percentage (%)</label>
                    <input type="number" step="0.01" name="tax_percentage" class="form-control" value="{{ $package->tax_percentage }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Community Share</label>
                    <input type="number" step="0.01" name="community_share" class="form-control" value="{{ $package->community_share }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select">
                        <option value="1" {{ $package->status ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$package->status ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-dark">Update Package</button>
        </form>
    </div>
@endsection
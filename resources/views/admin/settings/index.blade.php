@extends('admin.layouts.master')

@section('title', 'Admin Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-gear-fill me-2"></i> System Settings</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        @method('POST')

                        <div class="mb-4">
                            <label for="signup_fee" class="form-label fw-bold">Signup Fee ($)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control" id="signup_fee" name="signup_fee" 
                                    value="{{ $settings['signup_fee'] ?? '50' }}" required>
                            </div>
                            <div class="form-text text-muted">The base amount every user must pay after registration.</div>
                        </div>

                        <div class="mb-4">
                            <label for="referral_discount_percentage" class="form-label fw-bold">Referral Discount (%)</label>
                            <div class="input-group">
                                <input type="number" step="1" class="form-control" id="referral_discount_percentage" 
                                    name="referral_discount_percentage" value="{{ $settings['referral_discount_percentage'] ?? '10' }}" 
                                    required min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="form-text text-muted">Discount percentage applied if the user signs up via a referral code.</div>
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-dark btn-lg py-3 rounded-3 shadow">
                                <i class="bi bi-save me-2"></i> Update Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<style>
    .card {
        transition: transform 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .form-control:focus {
        border-color: #000;
        box-shadow: 0 0 0 0.25rem rgba(0, 0, 0, 0.1);
    }
</style>
@endsection

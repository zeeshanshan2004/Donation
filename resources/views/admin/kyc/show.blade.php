@extends('admin.layouts.master')

@section('title', 'KYC Details')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0">KYC Submission Details</h4>
        <a href="{{ route('admin.kyc.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="row">
        <!-- User Information Card -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">User Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">User Name:</th>
                            <td>{{ $submission->user->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $submission->user->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if($submission->status == 'approved')
                                    <span class="badge bg-success px-3 py-2">Approved</span>
                                @elseif($submission->status == 'pending')
                                    <span class="badge bg-warning px-3 py-2">Pending</span>
                                @else
                                    <span class="badge bg-danger px-3 py-2">Rejected</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Submitted On:</th>
                            <td>{{ $submission->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                        @if($submission->reviewed_at)
                            <tr>
                                <th>Reviewed On:</th>
                                <td>{{ $submission->reviewed_at->format('d M Y, h:i A') }}</td>
                            </tr>
                            <tr>
                                <th>Reviewed By:</th>
                                <td>{{ $submission->reviewer->name ?? 'N/A' }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <!-- KYC Details Card -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">KYC Details</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Full Legal Name:</th>
                            <td>{{ $submission->full_legal_name }}</td>
                        </tr>
                        <tr>
                            <th>Date of Birth:</th>
                            <td>{{ \Carbon\Carbon::parse($submission->date_of_birth)->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Country:</th>
                            <td>{{ $submission->country_of_residence }}</td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td>{{ $submission->residential_address }}</td>
                        </tr>
                        <tr>
                            <th>ID Type:</th>
                            <td>
                                <span
                                    class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $submission->photo_id_type)) }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents Row -->
    <div class="row">
        <!-- Photo ID Card -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Photo ID Document</h5>
                </div>
                <div class="card-body text-center">
                    @if($submission->photo_id_path)
                        @php
                            $photoPath = $submission->photo_id_path;
                            if (!str_starts_with($photoPath, 'storage/')) {
                                $photoPath = 'storage/' . $photoPath;
                            }
                        @endphp
                        <img src="{{ asset($photoPath) }}" alt="Photo ID"
                            class="img-fluid rounded shadow" style="max-height: 400px; cursor: pointer;"
                            onclick="window.open('{{ asset($photoPath) }}', '_blank')">
                        <div class="mt-3">
                            <a href="{{ asset($photoPath) }}" target="_blank"
                                class="btn btn-sm btn-dark">
                                <i class="bi bi-download"></i> View Full Size
                            </a>
                        </div>
                    @else
                        <p class="text-muted">No photo ID uploaded</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Face Photo Card -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Face Photo</h5>
                </div>
                <div class="card-body text-center">
                    @if($submission->face_photo_path)
                        @php
                            $facePath = $submission->face_photo_path;
                            if (!str_starts_with($facePath, 'storage/')) {
                                $facePath = 'storage/' . $facePath;
                            }
                        @endphp
                        <img src="{{ asset($facePath) }}" alt="Face Photo"
                            class="img-fluid rounded shadow" style="max-height: 400px; cursor: pointer;"
                            onclick="window.open('{{ asset($facePath) }}', '_blank')">
                        <div class="mt-3">
                            <a href="{{ asset($facePath) }}" target="_blank"
                                class="btn btn-sm btn-dark">
                                <i class="bi bi-download"></i> View Full Size
                            </a>
                        </div>
                    @else
                        <p class="text-muted">No face photo uploaded</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Agreement Row -->
    <div class="row">
        <!-- Agreement File Card -->
        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Signed Agreement Document</h5>
                </div>
                <div class="card-body text-center">
                    @if($submission->agreement_path)
                        @php
                            $agPath = $submission->agreement_path;
                            if (!str_starts_with($agPath, 'storage/')) {
                                $agPath = 'storage/' . $agPath;
                            }
                            $isPdf = Str::endsWith(strtolower($agPath), '.pdf');
                        @endphp

                        @if($isPdf)
                            <div class="py-5 bg-light rounded shadow-sm border mb-3">
                                <i class="bi bi-file-earmark-pdf text-danger" style="font-size: 4rem;"></i>
                                <h5 class="mt-3">PDF Document Uploaded</h5>
                            </div>
                        @else
                            <img src="{{ asset($agPath) }}" alt="Agreement Document"
                                class="img-fluid rounded shadow mb-3" style="max-height: 400px; cursor: pointer;"
                                onclick="window.open('{{ asset($agPath) }}', '_blank')">
                        @endif

                        <div>
                            <a href="{{ asset($agPath) }}" target="_blank"
                                class="btn btn-dark px-4 py-2">
                                <i class="bi bi-download me-2"></i> View / Download Agreement
                            </a>
                        </div>
                    @else
                        <p class="text-muted py-3">No agreement document uploaded yet. Waiting for User Step 2 completion.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Rejection Reason (if rejected) -->
    @if($submission->status == 'rejected' && $submission->rejection_reason)
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card shadow-sm border-0 rounded-4 border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">Rejection Reason</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $submission->rejection_reason }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Action Buttons (only for pending submissions) -->
    @if($submission->status == 'pending')
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body text-center py-4">
                        <h5 class="mb-4">Review Actions</h5>
                        <form action="{{ route('admin.kyc.approve', $submission->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg px-5 me-3"
                                onclick="return confirm('Are you sure you want to approve this KYC?')">
                                <i class="bi bi-check-circle"></i> Approve KYC
                            </button>
                        </form>
                        <button type="button" class="btn btn-danger btn-lg px-5" data-bs-toggle="modal"
                            data-bs-target="#rejectModal">
                            <i class="bi bi-x-circle"></i> Reject KYC
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="rejectModalLabel">Reject KYC Submission</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.kyc.reject', $submission->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="rejection_reason" class="form-label">Rejection Reason <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="4" required
                                minlength="10" placeholder="Please provide a detailed reason for rejection..."></textarea>
                            <small class="text-muted">Minimum 10 characters required</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endsection
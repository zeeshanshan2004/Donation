@extends('admin.layouts.master')

@section('title', 'KYC Verifications')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0">KYC Verifications</h4>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">ID</th>
                            <th class="py-3">User</th>
                            <th class="py-3">Full Name</th>
                            <th class="py-3">Country</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Submitted</th>
                            <th class="py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions as $submission)
                            <tr>
                                <td class="ps-4 fw-bold">#{{ $submission->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <div class="fw-bold text-dark">{{ $submission->user->name ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $submission->user->email ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $submission->full_legal_name }}</td>
                                <td>{{ $submission->country_of_residence }}</td>
                                <td>
                                    @if($submission->status == 'approved')
                                        <span class="badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill">Approved</span>
                                    @elseif($submission->status == 'pending')
                                        <span class="badge bg-warning-subtle text-warning border border-warning px-3 py-2 rounded-pill">Pending</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-2 rounded-pill">Rejected</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $submission->created_at->format('d M Y, h:i A') }}</td>
                                <td>
                                    <a href="{{ route('admin.kyc.show', $submission->id) }}" class="btn btn-sm btn-dark">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    No KYC submissions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($submissions->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $submissions->links() }}
            </div>
        @endif
    </div>
@endsection

@extends('admin.layouts.master')

@section('title', 'Donations History')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0">Donations History</h4>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">ID</th>
                            <th class="py-3">Transaction ID</th>
                            <th class="py-3">User</th>
                            <th class="py-3">Cause</th>
                            <th class="py-3">Amount</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($donations as $donation)
                            <tr>
                                <td class="ps-4 fw-bold">#{{ $donation->id }}</td>
                                <td>
                                    <code class="text-primary">{{ $donation->transaction_id ?? 'N/A' }}</code>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <div class="fw-bold text-dark">{{ $donation->user->name ?? 'Unknown' }}</div>
                                            <small class="text-muted">{{ $donation->user->email ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="badge bg-light text-dark border">{{ Str::limit($donation->cause->title ?? 'N/A', 30) }}</span>
                                </td>
                                <td class="fw-bold text-success">
                                    ${{ number_format($donation->amount / 100, 2) }}
                                </td>
                                <td>
                                    @if($donation->status == 'paid')
                                        <span
                                            class="badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill">Paid</span>
                                    @elseif($donation->status == 'pending')
                                        <span
                                            class="badge bg-warning-subtle text-warning border border-warning px-3 py-2 rounded-pill">Pending</span>
                                    @else
                                        <span
                                            class="badge bg-danger-subtle text-danger border border-danger px-3 py-2 rounded-pill">Failed</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $donation->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    No donations found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($donations->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $donations->links() }}
            </div>
        @endif
    </div>
@endsection
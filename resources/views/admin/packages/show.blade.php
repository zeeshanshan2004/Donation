@extends('admin.layouts.master')
@section('title', $package->name . ' - Buyers List')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">

        {{-- Header --}}
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('admin.packages.index') }}" class="btn btn-dark btn-sm me-3">← Back</a>
            <h4 class="mb-0 flex-grow-1 text-center">Total Users</h4>
        </div>

        {{-- Current Package Tab --}}
        <div class="d-flex flex-wrap gap-2 mb-3">
            <span class="btn btn-sm rounded-pill px-3 py-1 fw-semibold"
                  style="background-color:#c9a84c; color:#fff; border:none;">
                {{ $package->name }} ${{ number_format($package->amount, 0) }}
            </span>
        </div>

        {{-- Subtitle --}}
        <p class="text-muted mb-3" style="font-size:14px;">
            Package <span style="color:#c9a84c; font-weight:600;">{{ $package->name }}</span>
            &middot; Total <span style="color:#c9a84c; font-weight:600;">{{ $buyers->total() }}</span> buyers
        </p>

        {{-- Buyers List --}}
        @if($buyers->isEmpty())
            <div class="text-center text-muted py-5">
                <i class="bi bi-people fs-1 d-block mb-2"></i>
                No buyers found.
            </div>
        @else
            @php $offset = ($buyers->currentPage() - 1) * $buyers->perPage(); @endphp

            <div class="d-flex flex-column gap-2">
                @foreach($buyers as $buyer)
                    @php
                        $user     = optional($buyer->user);
                        $position = $offset + $loop->iteration;
                        $initial  = strtoupper(substr($user->name ?? '?', 0, 1));
                        $joinDate = $user->created_at ? $user->created_at->format('M d Y') : 'N/A';
                        $status   = $buyer->status ?? 'pending';
                        $badgeClass = $status === 'completed' ? 'bg-success' : ($status === 'active' ? 'bg-primary' : 'bg-secondary');
                    @endphp
                    <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border bg-white shadow-sm">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                                 style="width:44px;height:44px;background-color:#c9a84c;font-size:18px;flex-shrink:0;">
                                {{ $initial }}
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:15px;">{{ $user->name ?? 'Unknown' }}</div>
                                <div class="text-muted" style="font-size:12px;">
                                    Position #{{ $position }} &middot; Joined {{ $joinDate }}
                                </div>
                                <div class="mt-1">
                                    <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                                    @if($buyer->completed_at)
                                        <small class="text-muted ms-1">{{ $buyer->completed_at->format('d M Y') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="fw-bold" style="color:#c9a84c;font-size:16px;">
                            +${{ number_format($buyer->amount, 2) }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 d-flex justify-content-center">
                <nav>
                    <ul class="pagination pagination-sm">
                        {{-- Previous --}}
                        @if($buyers->onFirstPage())
                            <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $buyers->previousPageUrl() }}">&laquo;</a></li>
                        @endif

                        {{-- Pages --}}
                        @foreach($buyers->getUrlRange(1, $buyers->lastPage()) as $page => $url)
                            <li class="page-item {{ $page == $buyers->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}" style="{{ $page == $buyers->currentPage() ? 'background-color:#c9a84c;border-color:#c9a84c;' : '' }}">{{ $page }}</a>
                            </li>
                        @endforeach

                        {{-- Next --}}
                        @if($buyers->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $buyers->nextPageUrl() }}">&raquo;</a></li>
                        @else
                            <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                        @endif
                    </ul>
                </nav>
            </div>
        @endif

    </div>
</div>
@endsection

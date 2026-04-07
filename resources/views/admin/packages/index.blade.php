@extends('admin.layouts.master')
@section('title', 'Packages')
@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ url()->previous() }}" class="btn btn-dark btn-sm">
                        ← Back
                    </a>
                    <h4 class="mb-0">All Packages</h4>
                </div>
                <a href="{{ route('admin.packages.create') }}" class="btn btn-dark btn-sm">+ Add New Package</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                               <th>Icon</th>
                            <th>ID</th>
                            <th>Package Name</th>
                            <th>Pkg Amount</th>
                            <th>Referral Req</th>
                            <th>Total Collected</th>
                            <th>Tax %</th>
                            <th>Tax Amt</th>
                            <th>Comm Share</th>
                            <th>Your Payout</th>
                            <th>Status</th>
                            <th>Actions</th>
                         
                      
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($packages as $package)

                            <tr>
                                
                                <td>{{ $package->id }}</td>
                                                              <td>
    @if($package->icon)
        <img src="{{ asset('storage/' . $package->icon) }}" alt="icon" width="40" height="40" style="border-radius:5px; object-fit:cover; border:1px solid #ddd;">
    @else
        <span class="badge bg-secondary">No Icon</span>
    @endif
</td>   
                                <td>{{ $package->name }}</td>
                                <td>{{ number_format($package->amount, 2) }}</td>
                                <td>{{ $package->referral_required }}</td>
                                <td>{{ number_format($package->total_collected, 2) }}</td>
                                <td>{{ $package->tax_percentage }}%</td>
                                <td><span class="text-danger">{{ number_format($package->tax, 2) }}</span></td>
                                <td>{{ number_format($package->community_share, 2) }}</td>
                                <td><span class="text-success fw-bold">{{ number_format($package->payout, 2) }}</span></td>
                                <td>
                                    <span class="badge {{ $package->status ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $package->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
   
                                <td>
                                    <a href="{{ route('admin.packages.edit', $package->id) }}"
                                        class="btn btn-dark btn-xs">Edit</a>
                                        <a href="{{ route('admin.packages.show', $package->id) }}" class="btn btn-dark btn-xs">View</a>
                                    <form action="{{ route('admin.packages.destroy', $package->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-dark btn-xs"
                                            onclick="return confirm('Delete this package?')">Delete</button>
                                    </form>
                                </td>
          </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $packages->links() }}
            </div>
        </div>
    </div>

    <style>
        .btn-xs {
            padding: 1px 5px;
            font-size: 12px;
        }
    </style>
@endsection
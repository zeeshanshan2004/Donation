@extends('frontend.layouts.app')


@section('content')
<div class="container py-4">
    <h1 class="mb-4">All Causes</h1>
    <div class="row">
        @foreach($causes as $cause)
            <div class="col-md-4 mb-3">
                <div class="card h-100 shadow-sm">
                    @if($cause->image)
                        <img src="{{ asset('uploads/causes/'.$cause->image) }}" class="card-img-top" height="200">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $cause->title }}</h5>
                        <p class="small text-muted">{{ $cause->heading }}</p>
                        <a href="{{ route('frontend.causes.show', $cause->id) }}" class="btn btn-dark btn-sm">View Details</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-3">
        {{ $causes->links() }}
    </div>
</div>
@endsection

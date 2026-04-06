@extends('frontend.layouts.app')


@section('content')
<div class="container py-4">
    <a href="{{ route('frontend.causes.index') }}" class="btn btn-outline-dark mb-3">← Back</a>

    <div class="card shadow-sm">
        @if($cause->image)
            <img src="{{ asset('uploads/causes/'.$cause->image) }}" class="card-img-top" height="300">
        @endif
        <div class="card-body">
            <h2>{{ $cause->title }}</h2>
            <p class="text-muted">{{ $cause->heading }}</p>
            <p>{{ $cause->description }}</p>

            <p><strong>Target:</strong> ${{ $cause->target }}</p>
            <p><strong>Raised:</strong> ${{ $cause->raised }}</p>
            <p><strong>Days Left:</strong> {{ $cause->days_left }}</p>
        </div>
    </div>
</div>
@endsection

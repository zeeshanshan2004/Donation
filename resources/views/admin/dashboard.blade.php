@extends('admin.layouts.master')

@section('title', 'Dashboard')

@section('content')
    <h4 class="fw-bold mb-4 text-dark"></h4>

    <div class="row g-4">
        @php
            $cards = [
                ['title' => 'Users', 'desc' => 'Manage all registered users', 'route' => 'admin.users.index', 'icon' => 'bi-people'],
                ['title' => 'FAQs', 'desc' => 'Manage frequent questions', 'route' => 'admin.faqs.index', 'icon' => 'bi-question-circle'],
                ['title' => 'Categories', 'desc' => 'Manage content categories', 'route' => 'admin.categories.index', 'icon' => 'bi-folder'],
                ['title' => 'Causes', 'desc' => 'Manage all donation campaigns', 'route' => 'admin.causes.index', 'icon' => 'bi-heart'],
                ['title' => 'KYC Verifications', 'desc' => 'Review pending KYC submissions', 'route' => 'admin.kyc.index', 'icon' => 'bi-shield-check'],
                ['title' => 'Packages', 'desc' => 'Manage donation packages', 'route' => 'admin.packages.index', 'icon' => 'bi-box-seam'],
            ];
        @endphp

        @foreach($cards as $card)
            <div class="col-md-4 col-lg-3">
                <div class="card text-center shadow-sm border-0 h-100 card-hover p-3 p-md-4">
                    <i class="bi {{ $card['icon'] }} mb-2" style="font-size:2rem;color:#f1c40f;"></i>
                    <h5 class="card-title mb-2">{{ $card['title'] }}</h5>
                    <p class="card-text text-muted small">{{ $card['desc'] }}</p>
                    <a href="{{ route($card['route']) }}" class="btn btn-dark btn-sm mt-2">View {{ $card['title'] }}</a>
                </div>
            </div>
        @endforeach
    </div>



    <style>
        .card-hover {
            transition: transform 0.3s, box-shadow 0.3s;
            border-radius: 12px;
            background-color: #fff;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .btn-dark {
            background-color: #000;
            border-color: #000;
            border-radius: 20px;
            font-weight: 500;
            padding: 5px 15px;
            transition: all 0.3s;
        }

        .btn-dark:hover {
            background-color: #333;
            border-color: #333;
        }
    </style>
@endsection
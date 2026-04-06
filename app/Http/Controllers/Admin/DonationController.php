<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function index()
    {
        $donations = Donation::with(['user', 'cause'])->latest()->paginate(10);
        return view('admin.donations.index', compact('donations'));
    }
}

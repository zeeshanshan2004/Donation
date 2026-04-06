<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Faq;
use App\Models\Category;
use App\Models\TermCondition;
use App\Models\Cause;
use App\Models\Package;

class DashboardController extends Controller
{
    public function index()
    {
        // Real counts from DB
        $userCount = User::count();
        $faqCount = Faq::count();
        $categoryCount = Category::count();
        $termsCount = TermCondition::count();
        $causeCount = Cause::count();
        $packageCount = Package::count();

        return view('admin.dashboard', compact('userCount', 'faqCount', 'categoryCount', 'termsCount', 'causeCount', 'packageCount'));
    }
}

<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cause;

class CauseController extends Controller
{
    public function index()
    {
        $causes = Cause::where('status', 1)->latest()->paginate(9);
        return view('frontend.causes.index', compact('causes'));
    }

    public function show($id)
    {
        $cause = Cause::findOrFail($id);
        return view('frontend.causes.show', compact('cause'));
    }
}

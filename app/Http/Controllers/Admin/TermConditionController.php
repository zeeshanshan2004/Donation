<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TermCondition;
use Illuminate\Http\Request;

class TermConditionController extends Controller
{
    // Edit single Terms & Conditions row
    public function edit()
    {
        $terms_condition = TermCondition::first();
        return view('admin.terms_conditions.edit', compact('terms_condition'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $terms_condition = TermCondition::first();
        $terms_condition->update([
            'content' => $request->content
        ]);

        return redirect()->route('admin.terms_conditions.edit')->with('success', 'Terms & Conditions updated successfully!');
    }
}

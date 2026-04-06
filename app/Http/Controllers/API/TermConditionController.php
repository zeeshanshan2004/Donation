<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TermCondition;

class TermConditionController extends Controller
{
    public function index()
    {
        $terms = TermCondition::select('id', 'title', 'content')->get();

        // remove HTML tags if you only want plain text
        $terms->transform(function ($item) {
            $item->content = strip_tags($item->content);
            return $item;
        });

        return response()->json([
            'status' => true,
            'message' => 'Terms & Conditions fetched successfully',
            'data' => $terms
        ]);
    }
}

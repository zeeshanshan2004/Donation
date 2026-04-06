<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class CauseController extends Controller
{
    // ======================
    // Get all causes (list)
    // ======================
   public function index(Request $request)
{
    $query = Cause::select(
        'id',
        'category_id',
        'title',
        'heading',
        'author',
        'author_image',
        'image',
        'target',
        'raised',
        'days_left',
        'description',
        'is_featured',
        'status',
        'featured',
        'created_at',
        'updated_at'
    )
    ->where('status', true) // ✅ Sirf active causes
    ->orderBy('id', 'desc');

    // ✅ Filter 1: Featured causes
    if ($request->has('featured') && $request->featured == 'true') {
        $query->where('is_featured', true);
    }

    // ✅ Filter 2: Category ke hisab se
    if ($request->has('category_id') && is_numeric($request->category_id)) {
        $query->where('category_id', $request->category_id);
    }

    $causes = $query->get();

    return response()->json([
        'status' => true,
        'message' => 'Causes fetched successfully',
        'total' => $causes->count(),
        'data' => $causes
    ]);
}



    // ======================
    // Get single cause detail
    // ======================
    public function show($id)
    {
        $cause = Cause::find($id);

        if (!$cause) {
            return response()->json([
                'status' => false,
                'message' => 'Cause not found',
            ], 404);
        }

        // image ka full URL add
        // $cause->image = $cause->image 
        //     ? URL::to('uploads/causes/' . $cause->image)
        //     : null;

        return response()->json([
            'status' => true,
            'message' => 'Cause details fetched successfully',
            'data' => $cause
        ]);
    }
}

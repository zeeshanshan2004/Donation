<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cause;
use App\Models\Category;
use Illuminate\Http\Request;

class CauseController extends Controller
{
    public function index(Request $request)
    {
        $query = Cause::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        if ($request->filled('featured')) {
            $featured = $request->featured == 'yes' ? 1 : 0;
            $query->where('featured', $featured);
        }

        $causes = $query->orderByDesc('id')->paginate(10);

        return view('admin.causes.index', compact('causes'))
            ->with([
                'search' => $request->search,
                'featuredFilter' => $request->featured,
            ]);
    }

 public function create()
{
    $cause = null;
    $categories = Category::pluck('name', 'id'); // categories list
    return view('admin.causes.form', compact('cause', 'categories'));
}

    public function store(Request $request)
{
    $request->validate([
        'category_id' => 'required|exists:categories,id',
        'title' => 'required|string|max:255',
        'heading' => 'nullable|string',
        'author' => 'nullable|string|max:100',
        'author_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'target' => 'required|numeric',
        'raised' => 'nullable|numeric',
        'days_left' => 'nullable|integer',
        'is_featured' => 'nullable|in:0,1',
        'status' => 'nullable|in:0,1',
        'description' => 'nullable|string',
    ]);

    $data = $request->only([
        'category_id', 'title', 'heading', 'author', 'target', 'raised',
        'days_left', 'featured', 'status', 'description', 'is_featured'
    ]);

    $data['featured'] = (int) $request->input('featured', 0);
    $data['status']   = (int) $request->input('status', 1);

    if ($request->hasFile('image')) {
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('uploads/causes'), $imageName);
        $data['image'] = $imageName;
    }
    
     if ($request->hasFile('author_image')) {
        $authorImageName = time() . '.' . $request->author_image->extension();
        $request->image->move(public_path('uploads/author_image'), $authorImageName);
        $data['author_image'] = $authorImageName;
    }

    Cause::create($data);

    return redirect()->route('admin.causes.index')->with('success', 'Cause created successfully!');
}


   public function edit(Cause $cause)
{
    $categories = Category::pluck('name', 'id');
    return view('admin.causes.form', compact('cause', 'categories'));
}

    
    public function update(Request $request, Cause $cause)
{ 
    // return $request;
    $request->validate([
        'category_id' => 'required|exists:categories,id', // ✅ NEW
        'title' => 'required|string|max:255',
        'heading' => 'nullable|string',
        'author' => 'nullable|string|max:100',
        'author_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'target' => 'required|numeric',
        'raised' => 'nullable|numeric',
        'days_left' => 'nullable|integer',
        'is_featured' => 'nullable|in:0,1',
        'status' => 'nullable|in:0,1',
    ]);

    $data = $request->only([
        'category_id', // ✅ NEW
        'title', 'heading', 'author', 'target', 'raised',
        'days_left', 'is_featured', 'status', 'description'
    ]);

    $data['featured'] = (int) $request->input('featured', 0);
    $data['status']   = (int) $request->input('status', 1);

    if ($request->hasFile('image')) {
        if ($cause->image && file_exists(public_path('uploads/causes/' . $cause->image))) {
            unlink(public_path('uploads/causes/' . $cause->image));
        }

        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('uploads/causes'), $imageName);
        $data['image'] = $imageName;
    }
    
     if ($request->hasFile('author_image')) {
        if ($cause->author_image && file_exists(public_path('uploads/author_image/' . $cause->author_image))) {
            unlink(public_path('uploads/author_image/' . $cause->author_image));
        }

        $authorImageName = time() . '.' . $request->author_image->extension();
        $request->author_image->move(public_path('uploads/author_image'), $authorImageName);
        $data['author_image'] = $authorImageName;
    }

    $cause->update($data);

    return redirect()->route('admin.causes.index')->with('success', 'Cause updated successfully!');
}



//     public function toggleFeatured(Request $request, Cause $cause)
// {
//     $request->validate([
//         'featured' => 'required|boolean',
//     ]);

//     $cause->featured = $request->featured;
//     $cause->save();

//     return response()->json(['success' => true]);
// }


// =======================
// TOGGLE FEATURED
// =======================
public function toggleFeatured(Cause $cause)
{
    $cause->featured = !$cause->featured; // 1 to 0 or 0 to 1
    $cause->save();

    return response()->json([
        'success' => true,
        'featured' => $cause->featured
    ]);
}



public function toggleStatus(Cause $cause)
{
    try {
        $cause->status = !$cause->status; // toggle status
        $cause->save();

        return response()->json([
            'success' => true,
            'status' => $cause->status
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong!'
        ], 500);
    }
}



    public function destroy(Cause $cause)
    {
        if ($cause->image && file_exists(public_path('uploads/causes/' . $cause->image))) {
            unlink(public_path('uploads/causes/' . $cause->image));
        }

        $cause->delete();

        return redirect()->route('admin.causes.index')->with('success', 'Cause deleted successfully!');
    }
}



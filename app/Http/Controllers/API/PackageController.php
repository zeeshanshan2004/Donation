<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    // API: Saare packages dikhane ke liye
  public function index()
{
    // 1. Pehle database se packages lein
    $packages = Package::where('status', true)->latest()->get();


    $packages->transform(function ($package) {
        if ($package->icon) {
          
            $package->icon = asset('storage/'. $package->icon);
        }
        return $package;
    });

    // 3. Phir JSON response bhej dein
    return response()->json([
        'status' => true,
        'message' => 'Packages fetched successfully',
        'data' => $packages
    ]);
}

    // Admin/API: Naya Package Save karne ke liye
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'amount' => 'required|numeric',
            'icon' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);




        if ($request->hasFile('icon')) {
    $file = $request->file('icon');
    $filename = time() . '_' . $file->getClientOriginalName();
    
    // Sirf 'icons' folder mein save karein (storage/app/public/icons)
    $path = $file->storeAs('icons', $filename, 'public');
    
    // Database mein sirf 'icons/filename.png' save hoga
    $data['icon'] = $path; 
}
        $data = $request->all();

        

        $package = Package::create($data);

        // Agar aap browser se use kar rahe hain to redirect karein, warna JSON bhej dein
        if ($request->wantsJson()) {
            return response()->json(['status' => true, 'data' => $package], 201);
        }

        return redirect()->back()->with('success', 'Package Created with Icon!');
    }

    // Single Package dikhane ke liye
    public function show($id)
    {
        $package = Package::find($id);

        if (!$package) {
            return response()->json(['status' => false, 'message' => 'Not found'], 404);
        }

        if ($package->icon) {
            $package->icon = asset('/' . $package->icon);
        }

        return response()->json(['status' => true, 'data' => $package]);
    }
}
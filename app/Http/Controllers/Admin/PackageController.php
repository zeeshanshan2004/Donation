<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packages = Package::latest()->paginate(10);
        return view('admin.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.packages.create');
    }

    public function show(Package $package)
    {
        $buyers = $package->orders()->with('user')->latest()->paginate(15);
        $allPackages = Package::orderBy('name')->get();
        return view('admin.packages.show', compact('package', 'buyers', 'allPackages'));
    }

    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0',
        'referral_required' => 'required|integer|min:0',
        'tax_percentage' => 'required|numeric|min:0|max:100',
        'community_share' => 'required|numeric|min:0',
        'icon' => 'nullable|image|mimes:jpg,png,jpeg|max:2048', // Validation add karein
    ]);

    $data = $request->all();

    // Image upload logic
    if ($request->hasFile('icon')) {
        $file = $request->file('icon');
        $filename = time() . '_' . $file->getClientOriginalName();
        // File ko storage mein save karein aur path variable mein lein
        $path = $file->storeAs('icons', $filename, 'public');
        $data['icon'] = $path; 
    }

    Package::create($data); // Ab $request->all() ki jagah $data use karein

    return redirect()->route('admin.packages.index')->with('success', 'Package Created!');
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Package $package)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'referral_required' => 'required|integer|min:0',
            'tax_percentage' => 'required|numeric|min:0|max:100',
            'community_share' => 'required|numeric|min:0',
            'status' => 'nullable|boolean',
        ]);

        $package->update($request->all());

        return redirect()->route('admin.packages.index')->with('success', 'Package updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package)
    {
        $package->delete();
        return redirect()->route('admin.packages.index')->with('success', 'Package deleted successfully!');
    }
}

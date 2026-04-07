<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\PackageOrder;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::latest()->paginate(10);
        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.create');
    }

   public function show(Request $request, Package $package)
{
    $buyers = PackageOrder::with('user')
        ->where('package_id', $package->id)
        ->latest()
        ->paginate(15);

    return view('admin.packages.show', compact('package', 'buyers'));
}

    public function store(Request $request)
    {
        $request->validate([
            'name'               => 'required|string|max:255',
            'amount'             => 'required|numeric|min:0',
            'referral_required'  => 'required|integer|min:0',
            'tax_percentage'     => 'required|numeric|min:0|max:100',
            'community_share'    => 'required|numeric|min:0',
            'icon'               => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('icon')) {
            $file     = $request->file('icon');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path     = $file->storeAs('icons', $filename, 'public');
            $data['icon'] = $path;
        }

        Package::create($data);

        return redirect()->route('admin.packages.index')->with('success', 'Package Created!');
    }

    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'amount'            => 'required|numeric|min:0',
            'referral_required' => 'required|integer|min:0',
            'tax_percentage'    => 'required|numeric|min:0|max:100',
            'community_share'   => 'required|numeric|min:0',
            'status'            => 'nullable|boolean',
        ]);

        $package->update($request->all());

        return redirect()->route('admin.packages.index')->with('success', 'Package updated successfully!');
    }

    public function destroy(Package $package)
    {
        $package->delete();
        return redirect()->route('admin.packages.index')->with('success', 'Package deleted successfully!');
    }
}

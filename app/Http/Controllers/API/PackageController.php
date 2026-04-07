<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    // API: Saare packages dikhane ke liye
  public function index(Request $request)
{
    $packages = Package::where('status', true)
        ->withCount('orders')
        ->latest()
        ->get();

    $packages->transform(function ($package) {
        if ($package->icon) {
            $package->icon = asset('storage/' . $package->icon);
        }
        $package->buyers_count = $package->orders_count;
        return $package;
    });

    return response()->json([
        'status'  => true,
        'message' => 'Packages fetched successfully',
        'data'    => $packages
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
        $package = Package::withCount('orders')->find($id);

        if (!$package) {
            return response()->json(['status' => false, 'message' => 'Not found'], 404);
        }

        if ($package->icon) {
            $package->icon = asset('storage/' . $package->icon);
        }

        $buyers = $package->orders()->with('user:id,name,email,created_at')
            ->latest()
            ->get()
            ->map(function ($order, $index) {
                return [
                    'position'     => $index + 1,
                    'user_name'    => optional($order->user)->name ?? 'Unknown',
                    'user_email'   => optional($order->user)->email ?? 'N/A',
                    'joined'       => optional($order->user)->created_at?->format('M d Y') ?? 'N/A',
                    'amount'       => number_format($order->amount, 2),
                    'status'       => $order->status,
                    'completed_at' => $order->completed_at?->format('d M Y'),
                ];
            });

        return response()->json([
            'status'  => true,
            'data'    => [
                'package'      => $package,
                'buyers_count' => $package->orders_count,
                'buyers'       => $buyers,
            ]
        ]);
    }
    public function flowchart()
{
    // 1. Saare packages uthao aur unke orders ke zariye users ko load karo
    $packages = Package::with(['orders.user'])->where('status', true)->get();

    // 2. Data ko transform karo taake response saaf ho
    $data = $packages->map(function($package) {
        return [
            'id' => $package->id,
            'package_name' => $package->name,
            'amount' => $package->amount,
            'icon' => $package->icon ? asset('storage/' . $package->icon) : null,
            
            // Is package ke andar hi users ki list (Dropdown ke liye)
            'users_list' => $package->orders->map(function($order) {
                return [
                    'user_id'   => $order->user->id ?? null,
                    'user_name' => $order->user->name ?? 'Unknown',
                    'joined_at' => $order->created_at->format('d M, Y'),
                    'status'    => 'Active'
                ];
            })
        ];
    });

    return response()->json([
        'status' => true,
        'message' => 'All packages with their user lists',
        'data' => $data
    ]);
}
}
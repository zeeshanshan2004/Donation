# Implementation Plan: Package Buyers List

## Overview

Fix the broken View button in the packages index, add the `orders()` relationship to the Package model, add `show()` to PackageController, and create the buyers list Blade view.

## Tasks

- [x] 1. Fix View button route in `resources/views/admin/packages/index.blade.php`
  - Change `route('admin.packages.destroy', $package->id)` to `route('admin.packages.show', $package->id)` on the View anchor tag
  - _Requirements: 1.1, 1.2_

- [x] 2. Add `orders()` hasMany relationship to `app/Models/Package.php`
  - Add `public function orders()` returning `$this->hasMany(\App\Models\PackageOrder::class, 'package_id')`
  - _Requirements: 4.1, 4.2_

- [x] 3. Add `show(Package $package)` method to `app/Http/Controllers/Admin/PackageController.php`
  - Load buyers via `$package->orders()->with('user')->latest()->paginate(15)`
  - Fetch `$allPackages = Package::orderBy('name')->get()` for the tab bar
  - Return `view('admin.packages.show', compact('package', 'buyers', 'allPackages'))`
  - _Requirements: 3.1, 3.2, 3.3_

- [x] 4. Create `resources/views/admin/packages/show.blade.php`
  - Extend `admin.layouts.master`, set title to "[Package Name] - Buyers List"
  - Back button top-left linking to `admin.packages.index`
  - "Total Users" heading centered
  - Package filter tabs: pill buttons for each package in `$allPackages`, active tab gold bg (`#c9a84c`), others outlined; each links to `admin.packages.show` for that package
  - Subtitle: "Package {{ $package->name }} · Total {{ $buyers->total() }} buyers"
  - Loop `$buyers`: avatar circle (first letter of `optional($buyer->user)->name`, gold bg), name bold, "Position #{{ $loop->iteration + (($buyers->currentPage()-1) * 15) }} · Joined {{ optional($buyer->user)->created_at?->format('F d Y') }}", amount right in gold (`+${{ number_format($buyer->amount, 2) }}`)
  - Status badge: `bg-success` for `completed`, `bg-primary` for `active`, `bg-secondary` otherwise; show `completed_at` in `d M Y` format when not null
  - Empty state message "No buyers found" when `$buyers->isEmpty()`
  - Bootstrap 5 pagination: `{{ $buyers->links() }}`
  - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.5, 5.1, 5.2_

- [x] 5. Checkpoint — verify all four files are wired correctly
  - Ensure all tests pass, ask the user if questions arise.

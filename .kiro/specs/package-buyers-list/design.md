# Design Document: Package Buyers List

## Overview

This feature adds a dedicated buyers list page to the admin panel for each package. It fixes a broken "View" button (currently pointing to the `destroy` route) and introduces a new `show()` action in `PackageController` that renders a paginated list of users who purchased a given package.

The UI follows the reference design: pill-shaped package filter tabs at the top, buyer cards with avatar initials, position number, join date, and purchase amount — all styled with a gold/yellow accent (`#c9a84c`) on a dark Bootstrap 5 admin layout.

---

## Architecture

The change is confined to the admin panel layer. No new routes are needed — `Route::resource('packages', ...)` already registers `admin.packages.show` (GET `/admin/packages/{package}`).

```
Browser
  └─ GET /admin/packages/{id}
       └─ PackageController@show(Package $package)
            ├─ $package->orders()->with('user')->paginate(15)
            └─ view('admin.packages.show', compact('package', 'buyers', 'allPackages'))
```

**Files changed:**
- `app/Models/Package.php` — add `orders()` hasMany
- `app/Http/Controllers/Admin/PackageController.php` — add `show()` method
- `resources/views/admin/packages/index.blade.php` — fix View button route
- `resources/views/admin/packages/show.blade.php` — new view (created)

---

## Components and Interfaces

### 1. Package Model — `orders()` relationship

Add a `hasMany` relationship so buyers can be fetched via Eloquent:

```php
public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
{
    return $this->hasMany(\App\Models\PackageOrder::class, 'package_id');
}
```

### 2. PackageController — `show()` method

```php
public function show(Package $package)
{
    $buyers     = $package->orders()->with('user')->latest()->paginate(15);
    $allPackages = Package::orderBy('name')->get();
    return view('admin.packages.show', compact('package', 'buyers', 'allPackages'));
}
```

- Route model binding handles 404 automatically when package doesn't exist.
- `$allPackages` is passed so the tab bar can render all packages with the active one highlighted.
- `latest()` orders by `created_at DESC` so newest buyers appear first.

### 3. index.blade.php — Fix View button

Change:
```blade
<a href="{{ route('admin.packages.destroy', $package->id) }}" class="btn btn-dark btn-xs">View</a>
```
To:
```blade
<a href="{{ route('admin.packages.show', $package->id) }}" class="btn btn-dark btn-xs">View</a>
```

### 4. show.blade.php — New Blade View

**Layout sections:**

- **Back button** — top-left, links to `admin.packages.index`
- **Title** — "Total Users" centered at top
- **Package filter tabs** — pill buttons, one per package; active tab styled with gold background (`#c9a84c`), others outlined
- **Subtitle line** — "Package [Name] · Total [N] buyers"
- **Buyers list** — card-style rows per buyer:
  - Avatar circle: first letter of user name, gold background
  - Left: username bold, subtitle "Position #N · Joined [Month Day, Year]"
  - Right: amount in gold (`+$XXX.XX`)
- **Empty state** — centered message when no buyers
- **Pagination** — Bootstrap pagination below the list

---

## Data Models

### Package (existing, extended)

| Field | Type | Notes |
|---|---|---|
| id | int | PK |
| name | string | Display name |
| amount | decimal | Package price |
| status | boolean | Active/Inactive |
| icon | string\|null | Storage path |

New relationship: `orders()` → hasMany `PackageOrder` on `package_id`

### PackageOrder (existing, unchanged)

| Field | Type | Notes |
|---|---|---|
| id | int | PK |
| user_id | int | FK → users |
| package_id | int | FK → packages |
| amount | decimal:2 | Paid amount |
| progress | mixed | Progress value |
| status | string | `active`, `completed`, or other |
| completed_at | datetime\|null | Completion timestamp |
| created_at | datetime | Purchase date |

Existing relationships: `user()` belongsTo User, `package()` belongsTo Package.

### User (existing, read-only)

Fields used: `name`, `email`, `created_at`

---

## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system — essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

### Property 1: Buyer rows contain all required columns

*For any* package with one or more orders, the rendered show view should contain each buyer's name, email, purchase amount, order status, and purchase date (`created_at`).

**Validates: Requirements 2.2**

---

### Property 2: Pagination caps page size at 15

*For any* package with more than 15 orders, the buyers collection passed to the view on page 1 should contain exactly 15 items.

**Validates: Requirements 2.4**

---

### Property 3: orders() relationship returns all associated orders with user

*For any* package with N associated PackageOrders (each linked to a User), calling `$package->orders()->with('user')->get()` should return exactly N records, each with a non-null `user` relation loaded.

**Validates: Requirements 4.1, 4.2**

---

### Property 4: Status badge CSS class matches status value

*For any* PackageOrder, the rendered status badge should use `bg-success` when status is `completed`, `bg-primary` when status is `active`, and `bg-secondary` for any other value.

**Validates: Requirements 5.1**

---

### Property 5: Completed date renders in d M Y format

*For any* PackageOrder where `completed_at` is not null, the rendered output should contain the completion date formatted as `d M Y` (e.g. "15 Jan 2025").

**Validates: Requirements 5.2**

---

## Error Handling

| Scenario | Handling |
|---|---|
| Package ID not found | Laravel route model binding returns HTTP 404 automatically |
| Package has no orders | Empty state message rendered instead of table |
| User relation missing on order | `optional($buyer->user)` used in view to avoid null errors |
| Pagination beyond last page | Laravel paginator returns empty collection gracefully |

---

## Testing Strategy

### Dual Testing Approach

Both unit/feature tests and property-based tests are required. They are complementary:
- Feature tests verify specific examples and edge cases
- Property tests verify universal correctness across randomized inputs

### Feature Tests (PHPUnit)

These cover specific examples and edge cases:

**Example 1 — View button href (Req 1.1)**
Render the index view with a package and assert the "View" anchor `href` equals `route('admin.packages.show', $package->id)`.

**Example 2 — Page title (Req 2.1)**
GET `/admin/packages/{id}` and assert response contains the package name.

**Example 3 — Empty state (Req 2.3)**
Create a package with no orders, GET the show route, assert response contains "No buyers found".

**Example 4 — Back button (Req 2.5)**
GET the show route and assert response contains an anchor with `href` equal to `route('admin.packages.index')`.

**Example 5 — Controller passes data (Req 3.1)**
Call `PackageController@show` and assert the view receives `$buyers` where each item has a loaded `user` relation.

**Example 6 — 404 on missing package (Req 3.3)**
GET `/admin/packages/99999` and assert HTTP 404 response.

### Property-Based Tests (Pest + `spatie/pest-plugin-faker` or `phpunit-quickcheck`)

Recommended library: [`spatie/pest-plugin-faker`](https://github.com/spatie/pest-plugin-faker) for data generation within Pest, or a dedicated PBT library such as [`eris`](https://github.com/giorgiosironi/eris) for PHP.

Each property test must run a minimum of **100 iterations**.

Tag format: `Feature: package-buyers-list, Property {N}: {property_text}`

**Property Test 1 — Buyer rows contain all required columns**
```
// Feature: package-buyers-list, Property 1: buyer rows contain all required columns
// For any package with N orders, rendered view contains name/email/amount/status/date for each
```
Generate random packages with 1–50 random orders each. Render the show view. Assert each buyer's name, email, amount, status, and `created_at` date appear in the output.

**Property Test 2 — Pagination caps at 15**
```
// Feature: package-buyers-list, Property 2: pagination caps page size at 15
// For any package with >15 orders, page 1 contains exactly 15 items
```
Generate packages with 16–100 random orders. Call `$package->orders()->paginate(15)`. Assert `$result->count() === 15`.

**Property Test 3 — orders() relationship round-trip**
```
// Feature: package-buyers-list, Property 3: orders() returns all associated orders with user
// For any package with N orders, orders()->with('user')->get() returns N records with loaded user
```
Create a package and N (1–30) orders each with a user. Call the relationship. Assert count equals N and every item has `relationLoaded('user') === true` and `user !== null`.

**Property Test 4 — Status badge CSS class**
```
// Feature: package-buyers-list, Property 4: status badge CSS class matches status value
// For any order status, rendered badge uses correct Bootstrap class
```
Generate random status strings from `['completed', 'active', 'pending', 'cancelled', random_string]`. Render the badge partial. Assert `bg-success` for `completed`, `bg-primary` for `active`, `bg-secondary` for all others.

**Property Test 5 — Completed date format**
```
// Feature: package-buyers-list, Property 5: completed_at renders in d M Y format
// For any non-null completed_at, rendered output contains date in d M Y format
```
Generate random `completed_at` datetime values. Render the date display. Assert the output matches `\d{1,2} [A-Z][a-z]{2} \d{4}` regex pattern.

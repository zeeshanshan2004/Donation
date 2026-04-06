# Requirements Document

## Introduction

Yeh feature Laravel admin panel mein packages ke liye ek "buyers list" view add karta hai. Abhi packages index page pe "View" button galat route (`destroy`) pe point kar raha hai. Is feature ke zariye View button click karne pe ek dedicated page show hoga jisme us package ko khareedne wale tamam users ki list hogi — unka naam, email, purchase amount, order status, aur purchase date ke saath.

## Glossary

- **Admin_Panel**: Laravel-based web admin interface jo admin users use karte hain
- **Package**: Ek purchasable product entity jisme `name`, `amount`, `referral_required`, `tax_percentage`, `community_share`, `status`, aur `icon` fields hain
- **PackageOrder**: Ek record jo kisi user ki package purchase ko represent karta hai — fields: `user_id`, `package_id`, `amount`, `progress`, `status`, `completed_at`
- **Buyer**: Woh User jisne koi Package kharida ho (PackageOrder ke zariye)
- **Buyers_List**: Ek specific package ke tamam Buyers ki paginated tabular list
- **PackageController**: Admin controller jo packages ke CRUD operations handle karta hai (`app/Http/Controllers/Admin/PackageController.php`)
- **Package_Model**: Eloquent model `App\Models\Package`
- **PackageOrder_Model**: Eloquent model `App\Models\PackageOrder`

---

## Requirements

### Requirement 1: View Button ka Sahi Route

**User Story:** As an admin, I want the View button on the packages index page to point to the correct route, so that I don't accidentally trigger a delete action.

#### Acceptance Criteria

1. THE Admin_Panel SHALL render the "View" button in packages index table as a link pointing to `admin.packages.show` route with the package ID.
2. WHEN the "View" button is clicked, THE Admin_Panel SHALL NOT trigger the `destroy` action.

---

### Requirement 2: Package Buyers List Page

**User Story:** As an admin, I want to see a list of all users who bought a specific package, so that I can track package sales and buyer details.

#### Acceptance Criteria

1. WHEN an admin navigates to `admin/packages/{id}` (show route), THE Admin_Panel SHALL display a dedicated page titled "[Package Name] - Buyers List".
2. THE Admin_Panel SHALL display the following columns in the buyers table: User Name, User Email, Purchase Amount, Order Status, Purchase Date (`created_at`).
3. WHEN a package has no buyers, THE Admin_Panel SHALL display a message "Koi buyer nahi mila" (or "No buyers found") in place of the table.
4. THE Admin_Panel SHALL paginate the buyers list with 15 records per page.
5. THE Admin_Panel SHALL display a "Back to Packages" button that navigates back to `admin.packages.index`.

---

### Requirement 3: PackageController show() Method

**User Story:** As a developer, I want a `show()` method in PackageController, so that the buyers list page has a proper controller action.

#### Acceptance Criteria

1. THE PackageController SHALL contain a `show(Package $package)` method that loads the package's buyers via `PackageOrder` with eager-loaded `user` relationship.
2. WHEN the `show()` method is called, THE PackageController SHALL pass the `$package` and paginated `$buyers` (15 per page) to the view `admin.packages.show`.
3. IF the package does not exist, THEN THE Admin_Panel SHALL return a 404 response.

---

### Requirement 4: Package Model Relationship

**User Story:** As a developer, I want the Package model to have an `orders()` relationship, so that buyers can be fetched via Eloquent.

#### Acceptance Criteria

1. THE Package_Model SHALL define a `orders()` hasMany relationship to `PackageOrder_Model` using `package_id` foreign key.
2. WHEN `$package->orders()->with('user')` is called, THE Package_Model SHALL return all PackageOrder records associated with that package along with their related User.

---

### Requirement 5: Order Status Display

**User Story:** As an admin, I want to see the order status clearly in the buyers list, so that I can distinguish between active, completed, and pending orders.

#### Acceptance Criteria

1. THE Admin_Panel SHALL display the `status` field of each PackageOrder as a colored badge: `completed` = green, `active` = blue, any other value = grey.
2. WHEN `completed_at` is not null, THE Admin_Panel SHALL display the completion date in `d M Y` format in a tooltip or separate column.

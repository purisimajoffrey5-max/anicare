# AniCare-Allacapan — Detailed User Manual (Admin, Farmer, Miller, Resident)

> This manual explains how to use the **ANI-CARE ALLACAPAN** web-based system in the current codebase.

---

## 1) System Overview

ANI-CARE ALLACAPAN is a role-based web application for:
- **Admin**: approve user accounts, manage inventory, distributions, and announcements.
- **Farmer**: post rice/palay products, request milling, and manage orders from residents.
- **Miller**: set availability (OPEN/CLOSED), approve milling requests, schedule milling, and complete milling work.
- **Resident**: browse marketplace listings, checkout orders, and track order delivery using maps.

---

## 2) Accessing the System

### Public Homepage
- **URL**: `/` (Route: `PagesController@welcome`)
- Purpose: provides overview and a **Login** entry point.

### Login
- **URL**: `/login`
- **What you need**: username + password.
- **Behavior**:
  - If the account is not approved (`is_approved != 1`), login is blocked with an error.

### Register (Create Account)
- **URL**: `/register`
- **Required fields** (from register form):
  - `fullname`, `username`, `barangay`, `role` (resident/farmer/miller), `password` (+ confirmation)
  - Optional: `email`, `latitude`, `longitude`

> **Important**: All roles except `admin` require **admin approval before login**.

---

## 3) Role-Based Access Rules (Quick Reference)

The system uses role checks and route groups:
- Admin routes: prefix `admin`, middleware `role:admin`.
- Farmer routes: prefix `farmer`.
- Miller routes: prefix `miller`.
- Resident routes: prefix `resident`.

Unauthorized access is blocked with **HTTP 403**.

---

## 4) Admin User Manual

### Admin Dashboard
- **URL**: `/admin/dashboard`
- **Main options**:
  - Farmers & Millers
  - Inventory
  - Distribution
  - User Approvals
  - Marketplace
  - Announcements
  - Notifications

### A) Approve User Accounts
- **URL**: `/admin/approvals`

**What the page shows**
- Filter/search for users.
- Table of residents/farmers/millers with **PENDING/APPROVED** status.

**Actions**
- **Approve**: activates the account (`is_approved = 1` in user record).
- **Revoke**: returns the account to pending.

### B) Manage Farmers & Millers Accounts
- **URL**: `/admin/farmers-millers`
- **URL for a user details**: `/admin/farmers-millers/{id}`

**What you can do**
- Search and filter by role/status.
- View a user profile.
- Approve or revoke approval.
- Delete user records.

### C) Manage Inventory
- **URL**: `/admin/inventory`

**What you can see**
- Total stock summary cards (rice/palay).
- Inventory items table with:
  - name, product type (palay/rice), stock, price/kg, status, notes.

**Assign Miller (Palay awaiting milling)**
- If an inventory item is **palay** and status indicates it is awaiting milling, the page may show:
  - **Assign Miller** (goes to `admin.inventory.assign.form`)

### D) Distribution Management
- **URL**: `/admin/distribution`

**What you can do**
- Filter distributions by:
  - search term
  - status (pending/scheduled/completed/cancelled)
  - barangay
- Create new distribution records:
  - Beneficiary name/email/barangay
  - rice quantity (kg)
  - scheduled date/time (optional)
  - notes

**Distribution workflow**
- Pending → Scheduled → Completed
- For pending records, you can schedule.
- For scheduled records, you can mark as completed.

### E) Admin Marketplace
- **URL**: `/admin/market`

**Purpose**
- View all active rice/palay marketplace posts.
- See millers OPEN/CLOSED.

**Admin checkout from marketplace**
- For each product, there is an **Order Now** action:
  - goes to `/admin/checkout/{id}`

### F) Announcements
- **Active list**: `/admin/announcements`
- **Library (archived)**: `/admin/announcements-library`

**Create an announcement**
- Fill **Title** and **Message**.
- Submit form.

**Archive/Restore/Delete**
- Active announcements can be marked as completed (archived).
- Archived announcements can be restored or permanently deleted.

---

## 5) Farmer User Manual

### Farmer Dashboard
- **URL**: `/farmer/dashboard`

**Main options**
- My Farm Profile
- Request Milling
- My Requests
- Post Rice Product
- My Rice Products

### A) View/Update Farm Profile
- **URL**: `/farmer/profile`

**Update fields**
- Address, barangay, municipality, province
- Farm size (hectares)
- Contact number

#### Set Farm Location (Map)
- Same page includes a map picker.
- Save coordinates using:
  - POST `/farmer/location`

> The saved latitude/longitude is used for distance calculations and map displays.

### B) Post a Rice/Palay Product
- **URL (form)**: `/farmer/products/create`
- **URL (submit)**: POST `/farmer/products/create`

**Fields**
- Product Name
- Type: rice or palay
- Price per kg
- Kilos available
- Photo: optional

**Result**
- Creates a `RiceProduct` record with `is_active = 1`.

### C) Manage My Rice Products
- **URL**: `/farmer/products`

**Actions per product**
- **Toggle** product active/inactive
- **Out of Stock**: sets `kilos_available = 0` and `is_active = 0`
- **Re-stock**: update kilos available; re-activates if kilos > 0
- **Delete**: removes product and deletes photo from storage (if present)

### D) Request Milling
- **URL (create)**: `/farmer/milling/request`
- **URL (submit)**: POST `/farmer/milling/request`

**How it works**
1. The page loads a list of millers with `is_open` and (if available) map location.
2. You input:
   - `kilos` to mill
   - optional notes
   - choose a `miller_id`
3. The system blocks requests if selected miller is **CLOSED**.

**Map assistance**
- The UI shows open/closed millers on a Leaflet map.
- You can select a miller by clicking a marker or pressing **Select**.

### E) My Milling Requests
- **URL**: `/farmer/milling/requests`

Shows a table of your requests:
- kilos
- status (pending/approved/rejected/completed)
- notes
- requested at

### F) Manage Orders from Residents
- **URL**: `/farmer/orders`

Shows orders placed by residents to your products.

**Order status actions**
- If **PENDING**:
  - **Approve**
- If status is **PENDING or APPROVED**:
  - **Complete**
- If status is not completed/cancelled:
  - **Cancel** (stock returned to product in backend logic)

---

## 6) Miller User Manual

### Miller Dashboard
- **URL**: `/miller/dashboard`

**Important feature**: Service availability
- In the header, there is a button to set **OPEN/CLOSED**.

### A) Milling Requests
- **URL**: `/miller/requests`

**Default view**
- Shows requests with status `pending` and `assigned`.

**Filters**
- Filter by status: pending/approved/rejected/completed or all.

**Actions by request status**
- If **pending**:
  - Approve → sets `status=approved` and assigns `miller_id` to the current miller.
  - Reject → sets `status=rejected` and assigns `miller_id`.
- If **assigned**:
  - Accept → sets `status=approved` only if assigned to the current miller.
  - Reject → sets `status=rejected`.
- If **approved**:
  - Complete → marks the request completed.

#### Completing a milling request (important backend effects)
When a milling request is completed and it is for a **palay** inventory item, the system:
- updates inventory item status (milled)
- sets palay stock to 0
- estimates rice yield using `config('milling.conversion_rate', 0.65)`
- creates a new inventory item for **rice** with estimated kilos available
- creates in-app notifications for admin users

### B) Schedule Milling
- **URL**: `/miller/schedule`

**Shows**
- approved and assigned requests for the current miller.

**Actions**
- Set `scheduled_at` using `datetime-local` input.
- Complete/Accept buttons appear depending on request status.

> Page auto-refreshes every ~30 seconds.

### C) Milling Reports
- **URL**: `/miller/reports`

Shows completed requests:
- requester name
- product/milling request name
- kilos
- scheduled time
- completed time

### D) Miller Profile & Location
- **URL**: `/miller/profile`

Allows the miller to set latitude/longitude on a map.
- Saved coordinates appear to residents in the resident dashboard and map.

---

## 7) Resident User Manual

### Resident Dashboard
- **URL**: `/resident/dashboard`

**Header tabs**
- Marketplace
- My Orders
- My Profile

**Main features**
- Announcements list
- Open millers indicator (counts + list)
- Latest farmer posts preview
- Allacapan Farmers & Millers map:
  - fetched from `/resident/map-data`
  - farmer markers (green)
  - miller markers (blue) and show OPEN/CLOSED

### A) Marketplace (Browse Products)
- **URL**: `/resident/marketplace`

**Search**
- Query `q` allows filtering by:
  - product name
  - product type
  - farmer name/fullname/username

**Product cards** include:
- photo (if available)
- name
- type
- seller
- price per kg
- kilos available

**Action**
- **Buy Now** opens checkout:
  - `/resident/checkout/{product_id}`

**Availability rule**
- Buy Now is disabled when kilos_available <= 0.

### B) Checkout (Place an Order)
- **URL (form)**: `/resident/checkout/{id}`
- **URL (submit)**: POST `/resident/checkout/{id}`

**Fields**
- Buyer name
- contact number
- quantity (kg)
- fulfillment type: delivery or pickup
- Delivery address (required if delivery)
- Pickup address (required if pickup)
- Payment method: gcash/bank_transfer/cash_on_delivery/cash_on_pickup
- Notes (optional)

**Stock validation**
- The system locks product row during order placement and checks:
  - product is active
  - sufficient stock is available
- Then it deducts kilos_available and creates an order with status `pending`.

### C) My Orders
- **URL**: `/resident/orders`

Table includes:
- order id
- product name
- farmer
- kilos
- total price
- status
- ordered at

**Track**
- Track button opens order detail/track page:
  - `/resident/orders/{id}`

### D) Track Order Delivery
- **URL**: `/resident/orders/{id}`

**What you see**
- Order summary (product, farmer, quantity, total)
- Current status message (pending/approved/completed/cancelled)
- Leaflet map with markers:
  - rider location (based on farmer/resident lat/lng and order status)
  - farmer location (if available)
  - your saved location (if available)

### E) Resident Profile (Update Info + Set Location)
- **URL**: `/resident/profile`

**Update fields**
- fullname
- email (optional)

**Location picker**
- Map click saves delivery coordinates:
  - POST `/resident/profile` with latitude/longitude

> These coordinates support order tracking and map display.

---

## 8) Common Troubleshooting

### 1) “Account still pending admin approval”
- Fix: ask admin to approve your account (Admin → Approvals / Farmers & Millers).

### 2) “Unauthorized” (403)
- Fix: log in with the correct role.

### 3) Order fails with validation errors
- For residents:
  - ensure quantity <= available stock
  - ensure delivery/pickup address is provided based on fulfillment type

### 4) Product is not available
- Products become inactive when stock is out.

---

## 9) Suggested Workflow Examples (End-to-End)

### Resident → Farmer → Miller → Delivery Tracking
1. Resident registers & gets admin approval.
2. Resident logs in → Marketplace → Buy Now.
3. Resident fills checkout → order status becomes **PENDING**.
4. Farmer views orders → approves → status becomes **APPROVED**.
5. Miller views milling-related requests → schedules & completes.
6. Resident checks `/resident/orders/{id}` for tracking map and status.

---

## 10) Notes About “Chat”

The landing page mentions “Chat & Transaction Logs”, but in the inspected controllers/views provided here, there is **no explicit chat module shown**.

---

## Appendix A — Key Routes (Quick List)

- Public:
  - `/` welcome
  - `/login`, `/register`

- Resident:
  - `/resident/dashboard`
  - `/resident/marketplace`
  - `/resident/checkout/{id}`
  - `/resident/orders`
  - `/resident/orders/{id}`
  - `/resident/profile`
  - `/resident/map-data`

- Farmer:
  - `/farmer/dashboard`
  - `/farmer/profile`
  - `/farmer/location`
  - `/farmer/products`
  - `/farmer/products/create`
  - `/farmer/milling/request`
  - `/farmer/milling/requests`
  - `/farmer/orders`

- Miller:
  - `/miller/dashboard`
  - `/miller/profile`
  - `/miller/requests`
  - `/miller/schedule`
  - `/miller/reports`

- Admin:
  - `/admin/dashboard`
  - `/admin/approvals`
  - `/admin/farmers-millers`
  - `/admin/inventory`
  - `/admin/distribution`
  - `/admin/market`
  - `/admin/announcements`
  - `/admin/announcements-library`

---

_End of manual._


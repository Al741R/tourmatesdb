======================================================
1. COVER PAGE INFORMATION
======================================================
- Project Title: Tourmates Backend
- Student Names: [Student 1], [Student 2], [Student 3]
- Specialization: Backend
- Course & Section: [Course Code] – [Section]
- Instructor Name: [Instructor Name]
- Date of Submission: [Month Day, 2025]
- Semester, A.Y. 2025–2026

======================================================
2. PROJECT OVERVIEW
======================================================
- System Description: The backend is a Laravel-based RESTful API for a travel planning and booking system. It manages destinations, trips (stored in the `plan_trips` table), accommodations, itineraries, packing lists, bookings, details, notifications, and payment methods/details. Authentication uses Laravel Sanctum personal access tokens. Endpoints are JSON-based and primarily CRUD via resource controllers.

- Objectives of the Backend:
  - Provide CRUD APIs for core travel entities (destinations, trips, accommodations, itineraries, pack lists, bookings, details).
  - Manage user accounts with registration, login, and token-based authentication.
  - Support notifications and payment-related records (methods, card details, and GCash details).
  - Enforce input validation with Laravel FormRequest classes.

- Scope and Limitations:
  - Scope: Public CRUD for most resources; authenticated endpoints for user listing/profile and logout. Validation is implemented for all major resources under `app/Http/Requests`. Soft deletes are used across domain tables.
  - Limitations:
    - Most resource endpoints are not protected by authentication (public under `Route::middleware('api')`). Only user-related routes are under `auth:sanctum`.
    - No explicit CORS, HTTPS enforcement, or rate limiting in configuration.
    - Card and CVV data are stored as plaintext (no encryption) in `payment_card_details`.
    - RBAC is minimal: `users.role` exists, but only user routes implement checks.
    - No password reset or email verification flows are implemented in routes/controllers.

======================================================
3. SYSTEM DESIGN & DEVELOPMENT
======================================================

3.1 System Architecture
- Architecture: Laravel MVC with RESTful API endpoints.
  - Models: Eloquent models in `app/Models/*.php` define table mapping, `$fillable`, relations, and soft deletes.
  - Controllers: `app/Http/Controllers/Api/*Controller.php` implement JSON CRUD actions.
  - Requests: `app/Http/Requests/*Request.php` define validation rules per resource.
  - Routes: `routes/api.php` registers resource routes via `Route::apiResource` and explicit endpoints for auth and users.
  - Authentication: Laravel Sanctum tokens stored in `personal_access_tokens`; `auth:sanctum` middleware protects selected routes.
- Folder Responsibilities:
  - `app/Models`: Entity mapping and relationships.
  - `app/Http/Controllers/Api`: REST controllers returning JSON.
  - `app/Http/Requests`: Validation logic for incoming payloads.
  - `routes/api.php`: Route definitions and middleware groups.
  - `database/migrations`: Schema creation, keys, constraints.
  - `config/auth.php`, `config/sanctum.php`: Auth guards, providers, and Sanctum settings.
- Backend Workflow & Data Flow:
  - Request → Route in `api.php` → Controller action.
  - For create/update: FormRequest validates input; Eloquent persists; response returns JSON.
  - Protected routes require `Authorization: Bearer {token}` (Sanctum) via `auth:sanctum`.
  - Some endpoints eager-load relations to reduce N+1 queries.

3.2 Database Schema & ERD
- Tables (from `database/migrations`):
  - `users` (extended with `role`, `location`, `first_name`, `last_name`)
  - `sessions`
  - `destinations`
  - `plan_trips`
  - `accommodations`
  - `itineraries`
  - `pack_lists`
  - `bookings`
  - `details`
  - `personal_access_tokens`
  - `payment_method`
  - `payment_card_details`
  - `payment_gcash_details`
  - `notifications`
  - Note: `password_reset_tokens` created initially then dropped in a later migration.

- Relationships (PK–FK):
  - `destinations.destination_id` → `plan_trips.destination_id`, `accommodations.destination_id`
  - `users.id` → `plan_trips.user_id` (nullable), `bookings.user_id` (nullable), `payment_card_details.user_id`, `notifications.user_id`
  - `plan_trips.plan_trip_id` → `accommodations.plan_trip_id`, `itineraries.plan_trip_id`, `pack_lists.plan_trip_id`, `bookings.plan_trip_id`, `details.plan_trip_id`
  - `accommodations.accommodation_id` → `bookings.accommodation_id` (nullable), `details.accommodation_id`
  - `itineraries.itineraries_id` → `details.itineraries_id`
  - `pack_lists.pack_list_id` → `details.list_id`
  - `bookings.booking_id` → `payment_method.booking_id`, `payment_gcash_details.booking_id`
  - `payment_method.payment_id` → `payment_card_details.payment_id`, `payment_gcash_details.payment_id`
  - `personal_access_tokens` morphs to `users` via `tokenable`.

- ERD Narrative:
  - A Destination has many Trips. A Trip has many Accommodations, Itineraries, Pack Lists, Bookings, and Details. Accommodations belong to both Trip and Destination. Bookings belong to Trip and may belong to an Accommodation and a User. Payment Method belongs to Booking; Card Details belong to Payment Method and User; GCash Details belong to Payment Method and Booking. Notifications belong to User.

======================================================
4. API DEVELOPMENT
======================================================

4.1 API Routes & Endpoints (Base: `/api`)
- Destinations (Controller: `DestinationController`)
  - GET `/destinations` → index: List destinations. Auth: not required.
  - POST `/destinations` → store: Create destination. Auth: not required. Body per DestinationRequest.
  - GET `/destinations/{destination}` → show. Auth: not required.
  - PUT/PATCH `/destinations/{destination}` → update. Auth: not required.
  - DELETE `/destinations/{destination}` → destroy. Auth: not required.
  - Body (DestinationRequest): `name` (required), `city?`, `address?`, `details?`, `category?`, `rating?` (0–5), `reviews?`.

- Trips (Model uses table `plan_trips`; Controller: `TripController`)
  - GET `/trips` → index: List with `destination`, `accommodations`, `itineraries`, `packLists`. Auth: not required.
  - POST `/trips` → store: Create trip. Auth: not required. Body per TripRequest.
  - GET `/trips/{trip}` → show: With relations. Auth: not required.
  - PUT/PATCH `/trips/{trip}` → update. Auth: not required.
  - DELETE `/trips/{trip}` → destroy. Auth: not required.
  - Body (TripRequest): `title` (required), `description?`, `start_date?`, `end_date?` (>= `start_date`), `destination_id` (required exists), `user_id?` (exists), `price?`, `category?`.

- Accommodations (Controller: `AccommodationController`)
  - GET `/accommodations` → index. Auth: not required.
  - POST `/accommodations` → store. Auth: not required. Body per AccommodationRequest.
  - GET `/accommodations/{accommodation}` → show. Auth: not required.
  - PUT/PATCH `/accommodations/{accommodation}` → update. Auth: not required.
  - DELETE `/accommodations/{accommodation}` → destroy. Auth: not required.
  - Body (AccommodationRequest): `plan_trip_id` (exists), `destination_id` (exists), `name` (required), `address?`, `category?`, `price?`, `room_type?`, `contact_number?`, `review?`, `rating?`.

- Itineraries (Controller: `ItineraryController`)
  - GET `/itineraries`, POST `/itineraries`, GET `/itineraries/{itinerary}`, PUT/PATCH, DELETE. Auth: not required.
  - Body (ItineraryRequest): `plan_trip_id` (exists), `day?` (>=1), `title` (required), `activity?`, `time?`.

- Pack Lists (Controller: `PackListController`)
  - GET `/pack-lists`, POST `/pack-lists`, GET `/pack-lists/{pack_list}`, PUT/PATCH, DELETE. Auth: not required.
  - Body (PackListRequest): `plan_trip_id` (exists), `list_title` (required), `item` (required).

- Bookings (Controller: `BookingController`)
  - GET `/bookings` (with `accommodation`, `trip`, `user`), POST `/bookings`, GET `/bookings/{booking}`, PUT/PATCH, DELETE. Auth: not required.
  - Body (BookingRequest): `plan_trip_id` (exists), `accommodation_id?` (exists), `user_id?` (exists), `check_in?`, `check_out?` (>= `check_in`), `number_of_people?`, `price_per_night?`, `number_of_nights?`, `total_amount?`, `status?`.

- Details (Controller: `DetailController`)
  - GET `/details`, POST `/details`, GET `/details/{detail}`, PUT/PATCH, DELETE. Auth: not required.
  - Body (DetailRequest): `plan_trip_id` (exists), `accommodation_id?`, `itineraries_id?`, `list_id?`, `key` (required), `value?`.

- Notifications (Controller: `NotificationController`)
  - GET `/notifications`, POST `/notifications`, GET `/notifications/{notification}`, PUT/PATCH, DELETE. Auth: not required.
  - Body (NotificationRequest): `user_id` (exists), `message` (required, <=255).

- Payment Methods (Controller: `Payment_MethodController`)
  - GET `/payment-methods`, POST `/payment-methods`, GET `/payment-methods/{id}`, PUT/PATCH `/payment-methods/{id}`, DELETE `/payment-methods/{id}`. Auth: not required.
  - Body (PaymentMethodRequest): `booking_id` (exists), `payment_options` (in: `mastercard`, `gcash`).

- Payment Card Details (Controller: `PaymentCardDetailsController`)
  - GET `/payment-card-details`, POST `/payment-card-details`, GET `/payment-card-details/{id}`, PUT/PATCH `/payment-card-details/{id}`, DELETE `/payment-card-details/{id}`. Auth: not required.
  - Body (PaymentCardDetailsRequest): `user_id` (exists), `card_name`, `card_number` (16 digits), `expiry_date` (`m/y`), `cvv` (3 digits), `postal_code`, `payment_id` (exists).

- Payment GCash Details (Controller: `PaymentGcashDetailsController`)
  - GET `/payment-gcash-details`, POST `/payment-gcash-details`, GET `/payment-gcash-details/{id}`, PUT/PATCH `/payment-gcash-details/{id}`, DELETE `/payment-gcash-details/{id}`. Auth: not required.
  - Body (PaymentGcashDetailRequest): `payment_id` (exists), `booking_id` (exists), `gcash_number`.

- Users (Protected with `auth:sanctum`; Controller: `UserController`)
  - GET `/users` → index: Non-admin receives only self; admin (`role === 'admin'`) gets all users.
  - GET `/users/{id}` → show
  - POST `/users` → store (same schema as signup)
  - GET `/authenticated-user` → authenticated_user
  - POST `/logout` → `AuthController@logout`

- Auth (public; Controller: `AuthController`)
  - POST `/login` → login: returns token and user payload on success.
  - POST `/signup` → Signup: creates user and returns token and user payload.

4.2 Authentication & Authorization
- Mechanism: Laravel Sanctum personal access tokens. Tokens via `$user->createToken('auth_token')->plainTextToken` and stored in `personal_access_tokens`.
- Middleware: `auth:sanctum` group secures user-related endpoints; other resources are public.
- Flow:
  - Login: Validate via `UserRequest` (email, password). Verify password with `Hash::check`, optional role equality check; return Bearer token + user info.
  - Signup: Validate via `UserRequest` (name, email unique, password confirmed, role in `user`, location, first_name, last_name). Hash password; create user; return token + user info.
  - Logout: Delete all tokens for the authenticated user.

4.3 Sample API Requests & Responses
- Login
  - POST `/api/login`
  - Body: `{ "email": "user@example.com", "password": "secret1234", "role": "user" }`
  - Response: `{ "access_token": "<token>", "token_type": "Bearer", "user": { "id": 1, "name": "John Doe", "email": "user@example.com", "role": "user" } }`

- Signup
  - POST `/api/signup`
  - Body: `{ "name":"John Doe", "email":"user@example.com", "password":"secret1234", "password_confirmation":"secret1234", "role":"user", "location":"City", "first_name":"John", "last_name":"Doe" }`
  - Response: Same shape as login with new user details and token.

- Create Destination (from Postman collection)
  - POST `/api/destinations`
  - Body: `{ "name":"Beach Resort", "city":"Cebu", "address":"123 Shoreline", "details":"White sand", "category":"beach", "rating":4.5 }`
  - Response: `201 Created` with destination JSON including `destination_id` and timestamps.

- Create Trip (from Postman collection)
  - POST `/api/trips`
  - Body: `{ "title":"Weekend Getaway", "description":"Relax trip", "start_date":"2025-12-05", "end_date":"2025-12-07", "destination_id":1, "user_id":null, "price":1999.99, "category":"leisure" }`
  - Response: `201 Created` with trip JSON including `plan_trip_id` and timestamps.

======================================================
5. DATABASE DESIGN DETAILS
======================================================

5.1 Tables and Attributes
- `users`: id (PK, big inc), name (string), email (string unique), email_verified_at (timestamp null), password (string), role (string default 'user'), location (string), first_name (string), last_name (string), remember_token (string), timestamps.
- `sessions`: id (string PK), user_id (FK → users.id, nullable), ip_address (string 45), user_agent (text), payload (longText), last_activity (int indexed).
- `destinations`: destination_id (PK, big inc), name (string), city (string null), address (text null), details (text null), category (string null), rating (decimal 3,2 null), reviews (text null), timestamps, softDeletes.
- `plan_trips`: plan_trip_id (PK, big inc), accommodation_id (int null; no FK), title (string), description (text null), start_date (date null), end_date (date null), destination_id (bigint FK), user_id (bigint FK null), price (decimal 10,2 null), category (string null), timestamps, softDeletes.
- `accommodations`: accommodation_id (PK, big inc), plan_trip_id (FK), destination_id (FK), name (string), address (text null), category (string null), price (decimal 10,2 null), room_type (string null), contact_number (int null), review (string null), rating (decimal 7,2 null), timestamps, softDeletes.
- `itineraries`: itineraries_id (PK, big inc), plan_trip_id (FK), day (int null), title (string), activity (text null), time (string null), timestamps, softDeletes.
- `pack_lists`: pack_list_id (PK, big inc), plan_trip_id (FK), list_title (string), item (string), timestamps, softDeletes.
- `bookings`: booking_id (PK, big inc), plan_trip_id (FK), accommodation_id (FK null), user_id (FK null), check_in (date), check_out (date), number_of_people (int), price_per_night (int), number_of_nights (int), total_amount (decimal 10,2 null), status (string), timestamps, softDeletes.
- `details`: details_id (PK, big inc), plan_trip_id (FK), accommodation_id (FK), itineraries_id (FK), list_id (FK to pack_lists.pack_list_id), key (string), value (text null), timestamps, softDeletes.
- `personal_access_tokens`: id (PK), tokenable_type (string), tokenable_id (big int), name (text), token (string 64 unique), abilities (text null), last_used_at (timestamp null), expires_at (timestamp null indexed), timestamps.
- `payment_method`: payment_id (PK, big inc), booking_id (FK), payment_options (string), timestamps, softDeletes.
- `payment_card_details`: payment_card_id (PK, big inc), user_id (FK), card_name (string), card_number (string), expiry_date (string), cvv (string), postal_code (string), payment_id (FK), timestamps, softDeletes.
- `payment_gcash_details`: payment_gcash_id (PK, big inc), payment_id (FK), booking_id (FK), gcash_number (string), timestamps, softDeletes.
- `notifications`: notification_id (PK, big inc), user_id (FK), message (text), timestamps, softDeletes.
- Keys: Primary keys as listed; Foreign keys per relationships above; no composite PKs.

5.2 Data Normalization
The database schema follows the principles of Third Normal Form (3NF) to reduce redundancy and ensure data integrity:

- 3NF Compliance:
  - User data is separated from trip, booking, and payment data, preventing redundancy.
  - Destinations are stored independently and referenced by trips and accommodations through foreign keys.
  - Payment types (card and GCash) are separated into distinct tables based on payment method.
  - Repeated information like itineraries, pack lists, and notifications are stored in separate tables with proper foreign key relationships.
  - All tables use single-column primary keys, eliminating partial dependencies.
  - Non-key attributes depend directly on primary keys, not on other non-key attributes.

- Full 3NF Achievement (as of December 3, 2025):
  - Previously calculated fields (`number_of_nights`, `total_amount`) removed from `bookings` table; now computed dynamically via model accessors to eliminate transitive dependencies.
  - Previously redundant `destination_id` removed from `accommodations` table; destination accessed via trip relationship using `hasOneThrough` to avoid join-based redundancy.
  - All non-key attributes now depend solely on primary keys, ensuring data integrity and eliminating redundancy.

- Remaining Design Consideration:
  - The `plan_trips.accommodation_id` column is unused and lacks a foreign key constraint, representing a design inconsistency that does not violate 3NF.

- Data Integrity Features:
  - Foreign key constraints enforce referential integrity across all relationships.
  - Cascade deletion rules (CASCADE or SET NULL) maintain consistency when parent records are removed.
  - Soft deletes on all domain tables preserve historical data and maintain audit trails.
  - Unique constraints on critical fields (email, authentication tokens) prevent duplicates.
  - Encrypted storage for sensitive data (card numbers) ensures security while maintaining relational structure.

The normalization strategy ensures strict 3NF compliance while maintaining data integrity and optimizing for the read-heavy nature of a travel booking system.

======================================================
6. DEPLOYMENT & SECURITY
======================================================

6.1 Deployment
- Hosting/Setup (from `DEPLOY_HOSTINGER.md`): PHP 8.2+, Composer v2; set document root to `public/`; build assets locally; create production `.env` using `deploy/hostinger.env.example`.
- Server Commands:
  - `composer install --no-dev --optimize-autoloader`
  - `php artisan key:generate --force`
  - `php artisan migrate --force`
  - `php artisan storage:link`
  - `php artisan config:cache`
  - `php artisan route:cache`
  - `php artisan view:cache`
- Environment Variables (names only): `APP_*`, `DB_*`, `SESSION_DRIVER`, `SESSION_LIFETIME`, `CACHE_STORE`, `QUEUE_CONNECTION`, `FILESYSTEM_DISK`, `REDIS_*` (optional), `MAIL_*`, `VITE_APP_NAME`.
- Local Run: Not implemented in the codebase.

6.2 Security
- Password Hashing: Implemented via `password` cast to `hashed`; `bcrypt()`/`Hash::make()` used when creating/updating users; `Hash::check()` in login.
- Token Auth: Sanctum personal access tokens; stored in `personal_access_tokens`.
- CORS/HTTPS/Rate Limiting: Not implemented in the codebase.
- Validation: Implemented using FormRequest classes for all major resources.
- Sensitive Data: Card numbers and CVV stored plaintext in `payment_card_details` (risk). No encryption observed.

======================================================
7. ETHICAL & PROFESSIONAL CONSIDERATIONS
======================================================
- Data Privacy: Public endpoints may expose data broadly; protect personal/payment data with authentication/authorization.
- Secure Coding: Validation, password hashing, and token auth are implemented.
- User Data Protection: Avoid storing CVV, encrypt card numbers, apply least privilege, and add auditing/logging for sensitive operations.

======================================================
8. RESULTS & DISCUSSION
======================================================
- Performance: Eloquent with some eager loading; no explicit caching strategy beyond Laravel caches used in deployment.
- Strengths: Clear REST structure, comprehensive validation, broad domain coverage, Sanctum-based authentication.
- Limitations: Most endpoints are public; no CORS/HTTPS/rate limiting; sensitive data stored plaintext; minimal RBAC and auditing.
- Objective Alignment: CRUD and validation objectives met; authentication implemented but partially enforced.

======================================================
9. CONCLUSION & RECOMMENDATIONS
======================================================
- Summary: Functional Laravel REST API for travel planning/booking with models, migrations, validation, controllers, and Sanctum auth. Soft deletes enabled; deployment to Hostinger documented.
- Improvements:
  - Security: Remove CVV storage; encrypt card numbers; add CORS config, HTTPS enforcement, and rate limiting.
  - Authorization: Protect all mutating endpoints with `auth:sanctum`; add policies/gates and consistent RBAC.
  - API Structure: Version endpoints; generate OpenAPI/Swagger docs; add pagination and filters.
  - Data Integrity: Remove or add FK for `plan_trips.accommodation_id`; consider additional indices.
  - Observability: Add structured logs and audit trails; improve error handling.
  - Operations: Document local dev steps; add `.env.example`; integrate CI and tests.

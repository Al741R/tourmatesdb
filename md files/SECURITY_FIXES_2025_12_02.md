# Security and Architecture Fixes - December 2, 2025

## Overview
This document details critical security and architectural improvements implemented in the Tourmates backend system.

## ✅ Implemented Fixes

### 1. Payment GCash Details - Removed Redundant Foreign Key
**Issue:** `payment_gcash_details` table had both `payment_id` and `booking_id`, creating data redundancy.

**Solution:**
- ✅ Removed `booking_id` column from `payment_gcash_details` table
- ✅ Updated model to remove `booking_id` from fillable array
- ✅ Access booking through relationship: `$gcashDetails->payment->booking`
- ✅ Updated validation to remove `booking_id` requirement
- ✅ Improved GCash number validation to enforce Philippine mobile format (09XXXXXXXXX)

**Files Modified:**
- `database/migrations/2025_11_15_110534_create_payment_gcash_details_table.php`
- `app/Models/Payment_Gcash_Details.php`
- `app/Http/Requests/PaymentGcashDetailRequest.php`

### 2. Payment Card Details - Security Hardening
**Issue:** CVV was being stored (PCI-DSS violation), card numbers stored as plaintext.

**Solution:**
- ✅ Removed CVV column entirely (CVV should never be stored)
- ✅ Added encrypted cast to `card_number` field for automatic encryption/decryption
- ✅ Updated validation to use 4-digit year format (MM/YYYY instead of MM/YY)
- ✅ Improved card number validation to support various card types (13-19 digits)
- ✅ Added helpful validation messages

**Files Modified:**
- `database/migrations/2025_11_15_110521_create_payment_card_details_table.php`
- `app/Models/Payment_Card_Details.php`
- `app/Http/Requests/PaymentCardDetailsRequest.php`

### 3. Details Table - Fixed Column Nullability
**Issue:** Migration defined columns as NOT NULL, but validation allowed nullable values.

**Solution:**
- ✅ Made `accommodation_id`, `itineraries_id`, and `list_id` nullable in migration
- ✅ Now matches validation rules consistently

**Files Modified:**
- `database/migrations/2025_11_12_000016_create_details_table.php`

### 4. Added Missing Model Relationships
**Issue:** Models lacked proper relationships for eager loading and data access.

**Solution:**
- ✅ Added `paymentMethods()` relationship to `Booking` model
- ✅ Added `cardDetails()` and `gcashDetails()` to `Payment_Method` model
- ✅ Added `notifications()`, `savedCards()`, `bookings()`, and `trips()` to `User` model

**Files Modified:**
- `app/Models/Booking.php`
- `app/Models/Payment_Method.php`
- `app/Models/User.php`

### 5. Secured Payment Endpoints
**Issue:** All payment endpoints were public, exposing sensitive financial data.

**Solution:**
- ✅ Moved payment routes under `auth:sanctum` middleware
- ✅ Also protected notifications routes (user-specific data)
- ✅ Requires Bearer token authentication for all payment operations

**Files Modified:**
- `routes/api.php`

### 6. Database Migration for Existing Databases
**Created:** New migration to safely apply changes to existing databases.

**File:** `database/migrations/2025_12_02_082512_update_payment_tables_security_fixes.php`

**What it does:**
- Removes CVV column if it exists
- Removes booking_id column and foreign key from payment_gcash_details
- Makes details table columns nullable
- Includes rollback functionality

## 🔄 Migration Instructions

### For Fresh Installations:
```bash
php artisan migrate:fresh --seed
```

### For Existing Databases:
```bash
# Backup your database first!
php artisan migrate
```

The new migration will:
1. Check if columns exist before attempting to drop them
2. Safely modify existing tables
3. Preserve existing data

## 📝 API Changes

### Breaking Changes for Clients:

1. **GCash Details Endpoint** - Remove `booking_id` from request body:
   ```json
   // OLD (will fail)
   {
     "payment_id": 1,
     "booking_id": 1,
     "gcash_number": "09171234567"
   }
   
   // NEW (correct)
   {
     "payment_id": 1,
     "gcash_number": "09171234567"
   }
   ```

2. **Card Details Endpoint** - Remove `cvv` from request body, use 4-digit year:
   ```json
   // OLD (will fail)
   {
     "user_id": 1,
     "card_name": "John Doe",
     "card_number": "4111111111111111",
     "expiry_date": "12/25",
     "cvv": "123",
     "postal_code": "1000",
     "payment_id": 1
   }
   
   // NEW (correct)
   {
     "user_id": 1,
     "card_name": "John Doe",
     "card_number": "4111111111111111",
     "expiry_date": "12/2025",
     "postal_code": "1000",
     "payment_id": 1
   }
   ```

3. **Payment Endpoints** - Now require authentication:
   ```javascript
   // Add Authorization header to all payment requests
   headers: {
     'Authorization': 'Bearer YOUR_ACCESS_TOKEN',
     'Content-Type': 'application/json'
   }
   ```

## 🔐 Security Improvements

1. **CVV Never Stored** - Complies with PCI-DSS requirements
2. **Card Numbers Encrypted** - Automatic encryption using Laravel's encrypted cast
3. **Authentication Required** - Payment data only accessible to authenticated users
4. **Better Validation** - Enforces proper formats for sensitive data

## 🎯 Relationship Access Examples

### Access booking through GCash details:
```php
$gcashDetails = Payment_Gcash_Details::find(1);
$booking = $gcashDetails->payment->booking;
```

### Load payment details with booking:
```php
$booking = Booking::with(['paymentMethods.cardDetails', 'paymentMethods.gcashDetails'])->find(1);
```

### Get user's saved cards:
```php
$user = User::with('savedCards')->find(1);
$cards = $user->savedCards;
```

## ⚠️ Important Notes

1. **Card Number Encryption**: The `encrypted` cast will automatically encrypt card numbers on save and decrypt on retrieval. Ensure your `APP_KEY` is set properly in `.env`.

2. **CVV Processing**: If you need to process CVV for payment gateway integration, handle it in memory only - never store it in the database.

3. **Existing Data**: The migration will drop the CVV column, permanently deleting any CVV data. This is intentional for security compliance.

4. **Authentication**: Frontend applications must now obtain and send Bearer tokens for payment operations.

## 📚 Additional Recommendations (Not Yet Implemented)

These are recommended for future implementation:

1. **API Versioning** - Use `/api/v1/` prefix for all routes
2. **Rate Limiting** - Add throttling to prevent brute force attacks
3. **CORS Configuration** - Add proper CORS setup for production
4. **Audit Logging** - Log all payment operations for compliance
5. **Payment Gateway Integration** - Consider using Stripe, PayMongo, or PayMaya for PCI compliance
6. **Database Indices** - Add indices on frequently queried foreign keys
7. **Soft Delete Handling** - Implement `withTrashed()` where appropriate
8. **Authorization Policies** - Add Laravel policies for fine-grained access control
9. **Pagination** - Add pagination to all index endpoints
10. **Input Sanitization** - Add XSS protection to all text inputs

## 🧪 Testing Recommendations

Before deploying to production:

1. Test all payment endpoints with authentication
2. Verify card number encryption/decryption works correctly
3. Test GCash validation with Philippine mobile numbers
4. Verify relationships load correctly
5. Test migration rollback functionality
6. Update Postman collection with new authentication headers
7. Update frontend to handle new validation formats

## 📞 Support

If you encounter any issues after applying these fixes, check:
1. `.env` file has valid `APP_KEY`
2. Database migrations ran successfully
3. Frontend sends proper authentication headers
4. Request payloads match new validation rules

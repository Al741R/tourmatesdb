# Tourmates Database - Entity Relationship Diagram

```mermaid
erDiagram
    USERS ||--o{ PLAN_TRIPS : creates
    USERS ||--o{ BOOKINGS : makes
    USERS ||--o{ NOTIFICATIONS : receives
    USERS ||--o{ PAYMENT_CARD_DETAILS : saves
    
    DESTINATIONS ||--o{ PLAN_TRIPS : "featured in"
    DESTINATIONS ||--o{ ACCOMMODATIONS : "located at"
    
    PLAN_TRIPS ||--o{ ACCOMMODATIONS : includes
    PLAN_TRIPS ||--o{ ITINERARIES : contains
    PLAN_TRIPS ||--o{ PACK_LISTS : has
    PLAN_TRIPS ||--o{ BOOKINGS : receives
    PLAN_TRIPS ||--o{ DETAILS : describes
    
    ACCOMMODATIONS ||--o{ BOOKINGS : "booked via"
    ACCOMMODATIONS ||--o{ DETAILS : describes
    
    ITINERARIES ||--o{ DETAILS : describes
    
    PACK_LISTS ||--o{ DETAILS : describes
    
    BOOKINGS ||--|| PAYMENT_METHOD : "paid through"
    
    PAYMENT_METHOD ||--o| PAYMENT_CARD_DETAILS : "uses card"
    PAYMENT_METHOD ||--o| PAYMENT_GCASH_DETAILS : "uses gcash"
    
    USERS {
        bigint id PK
        string name
        string email UK
        timestamp email_verified_at
        string password
        string remember_token
        timestamp created_at
        timestamp updated_at
    }
    
    DESTINATIONS {
        bigint destination_id PK
        string name
        string city
        text address
        text details
        string category
        decimal rating
        text reviews
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }
    
    PLAN_TRIPS {
        bigint plan_trip_id PK
        int accommodation_id "unused"
        string title
        text description
        date start_date
        date end_date
        bigint destination_id FK
        bigint user_id FK
        decimal price
        string category
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }
    
    ACCOMMODATIONS {
        bigint accommodation_id PK
        bigint plan_trip_id FK
        bigint destination_id FK
        string name
        text address
        string category
        decimal price
        string room_type
        int contact_number
        string review
        decimal rating
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }
    
    ITINERARIES {
        bigint itineraries_id PK
        bigint plan_trip_id FK
        int day
        string title
        text activity
        string time
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }
    
    PACK_LISTS {
        bigint pack_list_id PK
        bigint plan_trip_id FK
        string list_title
        string item
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }
    
    BOOKINGS {
        bigint booking_id PK
        bigint plan_trip_id FK
        bigint accommodation_id FK
        bigint user_id FK
        date check_in
        date check_out
        int number_of_people
        int price_per_night
        int number_of_nights "computed"
        decimal total_amount "computed"
        string status
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }
    
    DETAILS {
        bigint details_id PK
        bigint plan_trip_id FK
        bigint accommodation_id FK
        bigint itineraries_id FK
        bigint list_id FK
        string key
        text value
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }
    
    PAYMENT_METHOD {
        bigint payment_id PK
        bigint booking_id FK
        string payment_options
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }
    
    PAYMENT_CARD_DETAILS {
        bigint payment_card_id PK
        bigint user_id FK
        string card_name
        string card_number "encrypted"
        string expiry_date
        string postal_code
        bigint payment_id FK
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }
    
    PAYMENT_GCASH_DETAILS {
        bigint payment_gcash_id PK
        bigint payment_id FK
        string gcash_number
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }
    
    NOTIFICATIONS {
        bigint notification_id PK
        bigint user_id FK
        text message
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }
```

## Key Features:

1. **3NF Compliance**: All transitive dependencies eliminated as of December 3, 2025
2. **Soft Deletes**: All domain tables include `deleted_at` for audit trails
3. **Encryption**: Card numbers are encrypted at application level
4. **Computed Fields**: `number_of_nights` and `total_amount` in bookings are calculated dynamically
5. **Referential Integrity**: Foreign key constraints with CASCADE and SET NULL rules
6. **Flexible Payment System**: Supports both card and GCash payment methods

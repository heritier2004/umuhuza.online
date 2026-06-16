# Rwanda Marketplace System

GitHub: https://github.com/heritier2004

A production-ready PHP/MySQL marketplace app with:
- homepage and listing search flow
- provider registration/login
- request submission without login
- provider and admin dashboards
- database schema for users, listings, requests, plans, payments, notifications, and location hierarchy

## Setup
1. Create MySQL database `rwanda_marketplace`.
2. Import `database/schema.sql`.
3. Update `app/config/database.php` with your DB credentials.
4. Run with any PHP server:
   php -S localhost:8000
5. Open http://localhost:8000/

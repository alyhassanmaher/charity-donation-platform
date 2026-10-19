# Charity Donation Platform: Relief for Children in Gaza

PHP web platform for a charity supporting children in Gaza, built for the Web Development course at ESLSCA University.

## What it does
**For visitors**
- Account registration and login, with passwords hashed using PHP's `password_hash`
- Donation form and donation records
- Aid shop with a cart and order history for food packages, medical supplies and school supplies
- Vaccine catalogue with availability status
- Upcoming relief activities and events
- About and contact pages

**For administrators**
- Separate admin login
- Manage activities, donations, products, users and vaccines
- Admin reports

## Tech
PHP · HTML · CSS · JavaScript, with flat-file storage in `.txt` files instead of a database

## Run
```bash
php -S localhost:8000
```
Then open `http://localhost:8000/Home.php`.

The admin accounts in `admin.txt` are placeholders. Change them before using the site anywhere real. The repo ships with empty user, donation, contact and order files.

# Invoice System

A Laravel 11 point-of-sale and invoicing system modeled after a National Book Store branch receipt. Supports admin and customer roles, live cart totals, PDF receipt generation, and stock management.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 11 (PHP 8.2+) |
| Frontend | Blade templates, vanilla JS, Vite |
| Database | SQLite (default) or MySQL/MariaDB |
| PDF | barryvdh/laravel-dompdf |
| Auth | Custom session-based (no Sanctum/Breeze) |

---

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+ and npm
- SQLite (default) **or** MySQL/MariaDB (e.g. via XAMPP)

---

## Local Setup

### 1. Get the project

```bash
git clone <repo-url> invoice-system
cd invoice-system
```

Or place the folder directly under `htdocs/FinalProject/invoice-system` if using XAMPP.

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

The default configuration uses **SQLite** — no separate database server needed. The database file is created automatically on first migration.

**To use MySQL/MariaDB instead** (e.g. XAMPP), update `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=invoice_system
DB_USERNAME=root
DB_PASSWORD=
```

Create the `invoice_system` database in phpMyAdmin before running migrations.

### 4. Run migrations and seed demo data

```bash
php artisan migrate --seed
```

This creates all tables and inserts two demo accounts plus sample products (see **Default Accounts** below).

To reset the database and re-seed at any time:

```bash
php artisan migrate:fresh --seed
```

### 5. Build front-end assets

```bash
npm run dev       # watch mode during development
# or
npm run build     # one-time production build
```

### 6. Start the development server

```bash
php artisan serve
```

Open [http://localhost:8000](http://localhost:8000) in your browser.

> **XAMPP users:** You can also access the app via Apache at `http://localhost/FinalProject/invoice-system/public` if you have configured your virtual host to point there. Running `php artisan serve` from inside the `invoice-system/` directory is the simpler approach.

---

## Default Accounts

| Role | Email | Password |
|---|---|---|
| Admin | cashier@venticbranch.test | password |
| Customer | jade@venticbranch.test | password |

> **Important:** Change these passwords before deploying to any shared or production environment.

---

## Features

### Admin

- **POS / Checkout** — build a cart, select buyer, enter cash, generate a receipt
- **Invoices** — view, edit, and delete all receipts; stock is restored on delete
- **Products** — create, edit, and delete products; tracks name, price, stock, category, and description
- **Customers** — create, edit, and delete customer profiles (name, address, contact number)
- **PDF Export** — download any receipt as a thermal-style PDF

### Customer

- **Shop / POS** — browse products and place an order; receipt is automatically linked to their account
- **My Receipts** — view their own purchase history and download PDF copies
- No access to Products or Customers management

---

## URL Reference

| URL | Access | Description |
|---|---|---|
| `/` | Any | Redirects to POS if logged in, login page otherwise |
| `/login` | Guest | Login form |
| `/register` | Guest | Self-registration (creates a linked Customer record) |
| `/pos` | Auth | POS / create invoice |
| `/dashboard` | Auth | Invoice history and daily sales summary |
| `/invoices/{id}` | Auth | Receipt detail view |
| `/invoices/{id}/pdf` | Auth | Download receipt as PDF |
| `/invoices/{id}/edit` | Admin | Edit a receipt |
| `/products` | Admin | Product list |
| `/customers` | Admin | Customer list |

---

## Roles & Middleware

Role is stored as `is_admin` (boolean) on the `users` table.

- Admin-only routes (Products, Customers) use the `admin` middleware (`App\Http\Middleware\EnsureAdmin`).
- Mixed-access routes (Invoices) apply per-action checks inside `InvoiceController` to scope data to the authenticated customer when needed.

To promote a registered user to admin, set `is_admin = 1` in the database directly, or add the user in `DatabaseSeeder.php` before running `php artisan migrate:fresh --seed`.

---

## Database Schema

| Table | Key Columns |
|---|---|
| `users` | `id`, `name`, `email`, `password`, `is_admin`, `customer_id` |
| `customers` | `id`, `name`, `address`, `contact_number` |
| `products` | `id`, `name`, `price`, `stock_quantity`, `category`, `description` |
| `invoices` | `id`, `trx_no`, `serial_no`, `clerk`, `term_no`, `invoice_date`, `amount_due`, `cash`, `change`, `vat_sales`, `vat`, `total_sales`, `customer_id` |
| `invoice_items` | `id`, `invoice_id`, `product_id`, `item_name`, `quantity`, `price`, `amount` |

Stock is decremented when an invoice is created and restored when it is deleted or edited.

---

## Running Tests

```bash
php artisan test
```

---

## Troubleshooting

| Problem | Fix |
|---|---|
| `php artisan` not found | Make sure you are inside the `invoice-system/` directory |
| Blank page / 500 error | Check `storage/logs/laravel.log`; confirm `APP_KEY` is set in `.env` |
| SQLite file missing | Run `php artisan migrate` — it creates `database/database.sqlite` automatically |
| Assets not loading | Run `npm run build` or `npm run dev` |
| Permission denied on `storage/` | `chmod -R 775 storage bootstrap/cache` (Linux/macOS only) |
| MySQL: "database not found" | Create the database in phpMyAdmin before migrating |
| PDF download fails | Ensure `barryvdh/laravel-dompdf` is installed (`composer install`) and `storage/` is writable |

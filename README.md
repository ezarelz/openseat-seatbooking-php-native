```md
# 🪑 Church Seat Booking System (PHP Native)

A simple and reliable **seat booking web application** built with **native PHP + MySQL**, designed to manage church service seat quotas fairly and safely.

This project focuses on **data integrity, concurrency safety, and clarity**, avoiding unnecessary frameworks while applying production-grade practices such as database transactions and row-level locking.

---

## ✨ Features

- 📊 Display seat quota per service
- 🧍 Simple registration (Name, Phone, Email optional)
- 🔒 **Concurrency-safe booking**
  - Prevents double booking using database transactions
  - Uses `SELECT ... FOR UPDATE`
- 📱 Mobile-friendly & lightweight
- ⚙️ Environment-based configuration using `.env`
- 📧 Email notification support (EmailJS-ready)

---

## 🧠 Why Native PHP?

This project intentionally uses **plain PHP** to demonstrate:

- Clear understanding of **server-side rendering**
- Strong fundamentals without framework abstractions
- Explicit control over **transactions & locking**
- Easy deployment on shared hosting or VPS

---

## 🗂️ Project Structure
```

WEB-GEREJA-SEAT-PHP/
├── config/
│ └── db.php # PDO connection & env loader
├── .env # Environment variables (ignored by git)
├── .env.example # Env template
├── index.php # Welcome page
├── quota.php # Seat availability per service
├── daftar.php # Booking form & submission
├── cek.php # Booking verification
├── test-db.php # DB connection test
└── .gitignore

````

---

## 🔧 Requirements

- PHP 8.0+
- MySQL / MariaDB
- Web server (Apache / Nginx / XAMPP)
- PDO enabled

---

## ⚙️ Installation

### 1️⃣ Clone repository
```bash
git clone https://github.com/yourusername/church-seat-booking-php.git
cd church-seat-booking-php
````

### 2️⃣ Setup environment

Copy `.env.example` to `.env`:

```env
DB_HOST=localhost
DB_NAME=gereja_seat
DB_USER=youruser
DB_PASS=yourpass

EMAILJS_PUBLIC_KEY=your_public_key_here
EMAILJS_SERVICE_ID=your_service_id_here
EMAILJS_TEMPLATE_ID=your_template_id_here
```

### 3️⃣ Database

Create database and required tables (example):

```sql
CREATE DATABASE gereja_seat;
```

> Table structure depends on your implementation
> (services, registrations, etc.)

---

## 🔐 Concurrency Handling (Important)

To prevent race conditions when multiple users book at the same time, this app uses:

- `BEGIN TRANSACTION`
- `SELECT ... FOR UPDATE`
- Real-time seat count validation
- Commit / rollback strategy

This ensures:

- ❌ No overbooking
- ✅ Accurate seat availability
- ✅ Data consistency under load

---

## 🧪 Testing

Test database connection:

```bash
http://localhost/test-db.php
```

---

## 🚀 Deployment Notes

- Compatible with **shared hosting**
- `.env` file is ignored by git
- Can be deployed to:

  - InfinityFree
  - VPS (Hestia / Nginx)
  - Local XAMPP

---

## 📌 Future Improvements

- Admin dashboard
- Export attendee list
- Service scheduling
- Rate limiting
- CSRF protection
- Authentication (optional)

---

## 👤 Author

**Manggala Eleazar**

---

> “Simple systems, built with care, scale better than complex ones built in haste.”

```


```

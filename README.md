# 🛡️ PHP Laravel 12 - Abusive IP Protection System

This project is built using **Laravel 12** and is designed to improve website security by blocking **abusive or malicious IP addresses**.

The system allows administrators to **block or unblock specific IP addresses** through an admin dashboard. A middleware checks every incoming request and prevents access if the user's IP address is found in the blocked list.

---

# 🚀 Features

* **IP Blocking System**
  Administrators can block any IP address from accessing the website.

* **Middleware Protection**
  Every incoming request is checked against the blocked IP list.

* **Admin Dashboard**
  A simple interface built with **Tailwind CSS** for managing blocked IP addresses.

* **Instant Unblock**
  Blocked IP addresses can be removed from the list with a single action.

* **Secure Request Filtering**
  Blocked users receive a **403 Forbidden response** when attempting to access the site.

---

# 🛠️ Setup Instructions

## 1️⃣ Project Setup

Run the following commands in the project directory:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

---

## 2️⃣ Database Configuration

Open the `.env` file and configure your database.

```env
DB_DATABASE=your_database
DB_USERNAME=root
DB_PASSWORD=
```

Run the database migrations:

```bash
php artisan migrate
```

---

# 📂 Key Components

## 📋 Model

`app/Models/AbusiveIp.php`

This model stores blocked IP addresses in the database using fields such as:

* `ip_address`
* `reason`

These fields are defined as **fillable properties**.

---

## 🛡️ Middleware

`app/Http/Middleware/CheckAbusiveIp.php`

This middleware checks the IP address of every incoming request.

If the IP address exists in the blocked list:

* The request is stopped
* A **403 Forbidden response** is returned

---

## 🎮 Controller

`app/Http/Controllers/IpManagementController.php`

Main controller methods:

* **index()**
  Displays the list of blocked IP addresses.

* **store()**
  Adds a new IP address to the blocked list.

* **destroy()**
  Removes an IP address from the blocked list.

---

# 🌐 Routes

| Method | Route             | Description                      |
| ------ | ----------------- | -------------------------------- |
| GET    | `/admin/ips`      | View the admin dashboard         |
| POST   | `/admin/ips`      | Add a new blocked IP             |
| DELETE | `/admin/ips/{id}` | Remove an IP from the block list |

---

# 🔍 How to Test

### 1️⃣ Start Laravel Server

```bash
php artisan serve
```

---

### 2️⃣ Open Admin Panel

Open the following URL in your browser:

```
http://localhost:8000/admin/ips
```

---

### 3️⃣ Test IP Blocking

1. Add your own IP address (example: `127.0.0.1`) to the blocked list.
2. Open the home page.
3. You should now see a **403 Forbidden error**.

---

# 🚑 Emergency Unblock

If you accidentally block your own IP address, you can remove all blocked IPs using **Laravel Tinker**.

Run:

```bash
php artisan tinker
```

Then execute:

```php
App\Models\AbusiveIp::truncate();
```

This will remove all blocked IP records from the database.

---

# 💻 Tech Stack

| Technology | Description    |
| ---------- | -------------- |
| Framework  | Laravel 12     |
| Backend    | PHP 8.2+       |
| Styling    | Tailwind CSS   |
| Database   | MySQL / SQLite |

---

# 🔒 Security Benefits

This system helps to:

* Prevent malicious access
* Reduce server abuse
* Protect against repeated attacks
* Improve website security

---

# 🤝 Contribution

Contributions are welcome.

1. Fork the repository
2. Create a new feature branch
3. Commit your changes
4. Submit a Pull Request

---



# Output
<img width="773" height="233" alt="image" src="https://github.com/user-attachments/assets/28301931-9d81-452a-addd-b1bbfbebc88d" />
<img width="699" height="188" alt="image" src="https://github.com/user-attachments/assets/71c6b3d8-6775-47b1-b4db-afbbded094cb" />

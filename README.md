# 💊 MedLog

### Medicine Inventory & Stock Management System

MedLog is a web-based Medicine Inventory Management System built using **Core PHP and MySQL**.
It helps manage medicine records, monitor stock levels, and generate low stock alerts efficiently.

🌐 **Live Demo:** [https://medlog.icu/Home](https://medlog.icu/Home)
💻 **GitHub Repository:** [https://github.com/raihan-hosen/Medlog](https://github.com/raihan-hosen/Medlog)

---

## 🚀 Features

* 🔐 Secure Admin Authentication (Session-Based Login)
* ➕ Add New Medicines
* ✏ Update Medicine Information
* ❌ Delete Medicines
* 📦 Real-Time Stock Management
* ⚠ Low Stock Alert System
* 📊 Dashboard Summary Overview
* 🎨 Responsive User Interface (Bootstrap)

---

## 🛠 Tech Stack

| Layer    | Technology Used           |
| -------- | ------------------------- |
| Backend  | PHP (Core PHP)            |
| Database | MySQL                     |
| Frontend | HTML, CSS, Bootstrap      |
| Hosting  | Shared Hosting Deployment |

---

## 📸 Application Modules

### 🔹 Authentication

* Secure login system for admin
* Session validation
* Protected routes

### 🔹 Medicine Management

* Add medicines
* Edit details
* Delete records
* Update stock quantity

### 🔹 Dashboard

* Total medicines count
* Low stock indicator
* Clean overview layout

---

## 🗄 Database Structure

Main table fields:

* `Medicine_Name`
* `Quantity`
* `Low_Limit`
* `Price`
* `Manufacturer`
* `Expiry_Date`

---

## ⚙ Installation Guide (Local Setup)

1. Clone the repository:

```
git clone https://github.com/raihan-hosen/Medlog.git
```

2. Move project folder to:

```
htdocs (for XAMPP)
```

3. Create a database in phpMyAdmin.

4. Import the SQL file (if available).

5. Configure database credentials in:

```
db.php
```

6. Start Apache & MySQL.

7. Open in browser:

```
http://localhost/Medlog
```

---

## 🔐 Default Admin Credentials

```
Email: admin@example.com
Password: MyPassword123
```

*(Change credentials after deployment for security.)*

---

## 📈 Learning Outcomes

This project strengthened my understanding of:

* Backend development with PHP
* CRUD operations
* Relational database design
* Session handling & authentication
* Debugging production errors
* Hosting and deployment

---

## 🔮 Future Improvements

* Expiry date alert system
* Sales tracking module
* Reporting dashboard
* Role-based authentication (Admin / Staff)
* REST API version

---

## 👨‍💻 Author

**Raihan Hosen**
CSE Student
Full-Stack Web Development Enthusiast

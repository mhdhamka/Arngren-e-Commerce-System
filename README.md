<div align="center">

# Arngren E-Commerce Platform

**A robust, full-featured online shopping platform specializing in unique appliances, gadgets, and electronics.**

[Live Demo](https://localhost:3000) · [Report Bug](https://github.com) · [Request Feature](https://github.com)

![License](https://img.shields.io/badge/license-MIT-blue.svg)
![PHP](https://img.shields.io/badge/PHP-8.x-777bb4?logo=php)
![MySQL](https://img.shields.io/badge/Database-MySQL-4479a1?logo=mysql)

</div>

---

## Overview

**Arngren E-Commerce Platform** is a secure, multi-tier web application built to streamline online shopping for gadgets and appliances. It features separate administrative and user workflows, comprehensive product inventory management, secure user authentication with password hashing, and real-time login tracking status.

---

## Platform Preview

<div align="center">

![Arngren Platform Interface](../../assets/images/arngren.png)

*User add to cart*

</div>

---

## Key Features

* **Role-Based Access Control:** Distinct authentication portals and dashboard states for regular Users and Administrators.
* **Secure Authentication Engine:** Built with password hashing (`password_hash`), session management, and automated `logStatus` tracking for active sessions.
* **Interactive Account Registration:** Form validation with real-time feedback, error handling, and session state preservation.
* **Admin Management Tools:** Dashboard interfaces for monitoring activities, catalog management, and tracking platform records.
* **Responsive Design:** Optimized layout ensuring a smooth shopping and administrative experience across desktop and mobile screens.

---

## Tech Stack

* **Backend:** PHP, MySQL 
* **Frontend:** HTML, CSS, JavaScript, Bootstrap 
* **Environment:** XAMPP (Apache, PHP, MySQL)

---

## Project Structure

```text
arngren/
├── assets/
│   ├── css/                    # Custom stylesheets (login.css, dashboard styles)
│   ├── js/ 
│   └── images/                 # Platform logos and media assets
├── config/
│   └── db_carngren.php         # Database connection and core functions
├── src/
│   ├── auth/                   # Authentication scripts (Login, Logout, Registration)
│   ├── config/                 # Authentication scripts (Login, Logout, Registration)
│   ├── user/                   # Authentication scripts (Login, Logout, Registration)
│   └── admin/                  # Administrative dashboards and management modules
├── .gitignore
└── README.md
```
---

If you found this project interesting, consider giving it a star!

Crafted with ⚡ by Mohammad Hamka Izzuddin

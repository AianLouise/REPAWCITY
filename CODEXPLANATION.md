# REPAWCITY — Codebase Documentation

A PHP-based pet adoption and shelter management website built for **RePaw City**, a pet shelter located in **#135 Purok 3, Balsik, Hermosa, Bataan, Philippines 2111**.

This document provides a comprehensive walkthrough of the project structure, database schema, key workflows, and file-by-file explanations.

---

## Table of Contents

1. [Project Overview](#1-project-overview)
2. [Tech Stack](#2-tech-stack)
3. [Project Structure](#3-project-structure)
4. [Database Schema](#4-database-schema)
5. [Configuration & Shared Components](#5-configuration--shared-components)
6. [Public Pages](#6-public-pages)
7. [Appointment Booking Flow (6 Steps)](#7-appointment-booking-flow-6-steps)
8. [Admin Panel](#8-admin-panel)
9. [CSS & Assets](#9-css--assets)
10. [JavaScript & External Libraries](#10-javascript--external-libraries)
11. [Security Notes & Known Limitations](#11-security-notes--known-limitations)
12. [How to Run / Local Setup](#12-how-to-run--local-setup)

---

## 1. Project Overview

**RePaw City** is a shelter website that allows visitors to:
- Browse adoptable dogs and cats with filtering (type, sex, weight, age)
- View detailed pet profiles
- Book appointments (Adopt, Donate, Visit, Volunteer) through a 6-step wizard
- Donate via Bank Transfer, GCash, or Cash
- Read news/blog articles about pet care and shelter updates
- View volunteer opportunities and requirements
- Register/login to manage their profile and view appointment statuses
- Contact the team and view shelter information

An **admin panel** provides full CRUD operations for:
- Managing pets (add, edit, delete, set featured)
- Managing news articles (add, edit, delete, set headline)
- Managing user accounts (edit, delete, promote/demote admin)
- Viewing and managing appointments (accept/cancel with a calendar)

---

## 2. Tech Stack

| Layer | Technology |
|-------|-----------|
| **Backend** | PHP 8.2 (procedural, no framework) |
| **Database** | MySQL / MariaDB 10.4 |
| **Frontend** | HTML5, CSS3, Vanilla JavaScript |
| **CSS Approach** | One dedicated CSS file per page (under `css/`) |
| **JS Libraries** | jQuery 3.6, Bootstrap 4.5, FullCalendar 3.10, Font Awesome 6, Google reCAPTCHA (loaded only on some pages) |
| **Fonts** | Google Fonts: *Acme*, *Sigmar* |
| **Server** | Apache (XAMPP/WAMP style, localhost:3306) |

---

## 3. Project Structure

```
repawcity/
├── *.php                      # Root-level public & admin pages (flat structure)
├── css/                       # Page-specific stylesheets (one per page)
├── database/
│   └── repawcity.sql          # MySQL database dump (schema + seeded data)
├── function/                  # Shared/included PHP components
│   ├── authcode.php           # Registration & login logic
│   ├── config.php             # Database connection
│   ├── footer.php             # Reusable site footer
│   ├── navbar.php             # Reusable navigation bar
│   ├── save_selected_date.php # AJAX endpoint (session date picker)
│   └── userfunction.php       # Pet filtering query logic
├── image/                     # All site images, icons, logos
│   ├── book-appointment/      # Progress bar step images, captcha image
│   ├── LoginSignup/           # Login/signup background slideshow images
│   ├── news/                  # News category images
│   ├── social media/          # FB, IG, TikTok icons
│   ├── Success Stories/       # Success story images
│   ├── team/                  # Team member profile photos
│   └── volunteer/             # Volunteer page images & PDFs
├── script/
│   └── script.js              # Show/hide password toggle
└── upload/                    # User-uploaded images (pets & news)
    └── news/                  # Uploaded news images
```

---

## 4. Database Schema

Database name: **`repawcity`** — Host: `localhost:3306` (dump uses `localhost:3307`), User: `admin`, Pass: `admin123`

### 4.1 `user` — User accounts

| Column       | Type         | Description                              |
|--------------|--------------|------------------------------------------|
| `user_id`    | int(11) PK   | Auto-increment                           |
| `fname`      | varchar(250) | First name                               |
| `lname`      | varchar(250) | Last name                                |
| `email`      | varchar(250) | Login email (unique check in code)       |
| `password`   | varchar(250) | **Plain text (no hashing!)**             |
| `user_type`  | varchar(10)  | `1` = Admin, `2` = Regular user          |
| `created_at` | timestamp    | Auto-set to current timestamp            |

### 4.2 `pets` — Adoptable pets

| Column       | Type         | Description                                        |
|--------------|--------------|----------------------------------------------------|
| `pets_id`    | int(11) PK   | Auto-increment                                     |
| `name`       | varchar(250) | Pet name                                           |
| `type`       | varchar(250) | `Dog` or `Cat`                                     |
| `breed`      | varchar(250) | Breed (e.g., Labrador Retriever, Persian, Aspin)   |
| `sex`        | varchar(250) | `Male` or `Female`                                 |
| `weight`     | varchar(250) | Weight range (e.g., `Less than 5 lbs`, `5-10 lbs`) |
| `age`        | varchar(250) | Age range (e.g., `6 months to 5 years`)            |
| `date`       | date         | Date of rescue                                     |
| `about`      | text         | Description/story about the pet                    |
| `image`      | varchar(250) | Filename stored in `upload/`                       |
| `is_featured`| varchar(10)  | `0`–`4`: `0`=not featured, `1`–`4` = featured slot |
| `user_id`    | int(11) FK   | References `user.user_id` (admin who added the pet)|

### 4.3 `news` — News/blog articles

| Column          | Type         | Description                                      |
|-----------------|--------------|--------------------------------------------------|
| `news_id`       | int(11) PK   | Auto-increment                                   |
| `title`         | varchar(250) | Article title                                    |
| `details`       | mediumtext   | Full article body                                |
| `image`         | varchar(250) | Filename stored in `upload/news/`                |
| `date_published`| datetime     | Auto-set to current timestamp on insert/update   |
| `is_featured`   | tinyint(1)   | `1` = headline news (only one at a time), else `0`|
| `user_id`       | int(11) FK   | References `user.user_id` (admin who created it) |

### 4.4 `appointment` — Booked appointments

| Column            | Type          | Description                                       |
|-------------------|---------------|---------------------------------------------------|
| `appointment_id`  | int(11) PK    | Auto-increment                                    |
| `appointment_type`| varchar(255)  | `Adopt`, `Donate`, `Visit`, or `Volunteer`        |
| `appointment_date`| date          | Scheduled date                                    |
| `time_slot`       | varchar(250)  | `Morning Session` (9–11:30 AM) or `Afternoon Session` (1–4:30 PM) |
| `first_name`      | varchar(255)  | —                                                 |
| `middle_name`     | varchar(250)  | —                                                 |
| `last_name`       | varchar(255)  | —                                                 |
| `mobile_number`   | varchar(20)   | —                                                 |
| `home_address`    | varchar(255)  | —                                                 |
| `email_address`   | varchar(255)  | —                                                 |
| `user_id`         | int(11) FK    | References `user.user_id` — the logged-in user    |
| `status`          | varchar(10)   | `Pending`, `Accepted`, or `Cancelled`             |
| `message`         | varchar(1000) | Notification message sent to user (auto-generated)|

### 4.5 Foreign Keys

- `appointment.user_id` → `user.user_id` (ON DELETE CASCADE, ON UPDATE CASCADE)
- `news.user_id` → `user.user_id` (ON DELETE CASCADE, ON UPDATE CASCADE)
- `pets.user_id` → `user.user_id` (defined in schema indexes; note: constraint only explicitly declared for `appointment` and `news` in the SQL dump)

### 4.6 Seeded Data (from dump)

- **1 admin user:** Aian Louise Alfaro — `admin@gmail.com` / `1234`
- **18 pets:** 9 dogs + 9 cats (featured slots 1–4: Cookies=1, Yuchi=2, Xuan=3, Una=4)
- **3 news articles** (1 featured/headline)

---

## 5. Configuration & Shared Components

### 5.1 `function/config.php`

Establishes the `mysqli` connection:

```php
$servername = "localhost:3306";
$username = "admin";
$password = "admin123";
$dbname = "repawcity";
$conn = new mysqli($servername, $username, $password, $dbname);
```

This file is `require`d at the top of nearly every page.

### 5.2 `function/authcode.php`

Handles both registration and login via `$_POST` `name="register"` / `name="login"`:

**Registration**
- Escapes inputs with `mysqli_real_escape_string`
- Checks if email already exists; rejects duplicates
- Verifies password === confirm password
- Inserts new user with `user_type = 2`
- Redirects to `loginpage.php` on success

**Login**
- Queries by email + password (plain text comparison)
- `user_type == 1` → redirects to `admin-dashboard.php` with "Logged In Successfully as Admin"
- `user_type == 2` → redirects to `home.php`
- Stores `$_SESSION['auth'] = true` and `$_SESSION['auth_user'] = ['id', 'fname', 'lname', 'email']`

### 5.3 `function/navbar.php`

Reusable header for all public pages:
- Logo → `index.php`
- Links: Home, Adopt, Donate, News, Volunteer
- Dropdown "About Us": Success Stories, FAQ, Contact, Team, References
- **Logged in:** Profile dropdown (Edit Profile, Change Password, and dynamically listed appointments by type linking to `notification.php`) + Logout button
- **Logged out:** Log In and Sign Up buttons
- Mobile responsive hamburger menu (`toggleMenu()`)

### 5.4 `function/footer.php`

Reusable footer with:
- Quick links (Adopt a Dog, Adopt a Cat, Donate, Success Stories, Volunteer, News)
- About Us blurb
- Address + Google Maps link
- Plain-text contact info (phone, email)
- Information section (Mission, FAQ/Assistance, Privacy Policy, Terms of Use)
- Social media icons (Facebook, Instagram, TikTok)
- Scroll-to-top arrow

### 5.5 `function/userfunction.php`

Defines the pet-filter query builder used to filter pets by `type`, `sex`, `weight`, and `age` from `$_GET` params. Fetches all matching rows into `$pet_data`.

### 5.6 `function/save_selected_date.php`

AJAX POST endpoint that stores a selected calendar date into `$_SESSION['selectedDate']`.

---

## 6. Public Pages

### 6.1 `index.php` — Landing Page
- Static landing page with the RePaw City logo
- Background images (`landingbg.png`, `landingtext.png`, `landingfooter.png`)
- Minimal navigation (Home, Adopt, Donate, News, Volunteer)

### 6.2 `home.php` — Home/Dashboard
- Requires DB connection + starts session
- Quick action cards: **Find a Dog** (`adoptpage.php?type=Dog`), **Find a Cat** (`adoptpage.php?type=Cat`), **Donate**, **Volunteer**
- **Featured Pets section:** loops `is_featured = 1..4` and displays up to 4 featured pet cards linking to `adoptprofile.php?id=X`
- **Donate CTA section** with 6 bullet points about donation impact

### 6.3 `adoptpage.php` — Pet Listing & Filtering
- Shows all pets with a **sort/filter menu**
- Filters: Pet Type (Dog/Cat), Sex, Weight range, Age range
- Filters are applied via POST form with auto-submit on change; type can also come from GET (e.g., `adoptpage.php?type=Dog`)
- Each pet card links to `adoptprofile.php?id=<pets_id>`
- "Book Appointment" button → `book-appointment.php` (if logged in) or `loginpage.php` (if not)

### 6.4 `adoptprofile.php` — Pet Detail Page
- Accepts `?id=<pets_id>` from URL
- Displays pet photo, name, type, breed, sex, weight, age, date of rescue, about/story
- "Contact us to Meet <name>" button → booking flow or login redirect

### 6.5 `donatepage.php` — Donations
- Informational text about the impact of donations
- Three donation methods:
  - **Bank Transfer** (QR code + account 0036-4007-0350)
  - **GCash Transfer** (QR code + account 0912-345-6789)
  - **Cash** (contact us to arrange drop-off)

### 6.6 `news.php` — News/Articles Listing
- **Pet Care Tips slideshow** (auto-rotating images every 4 seconds)
- **Headline story:** fetches the one news item with `is_featured = 1`, truncates details to ~200–300 chars
- **Latest news cards:** all news ordered by `date_published DESC` with relative time ("x hours ago", "x days ago", etc.) and truncated previews
- Each card links to `news-page.php?news_id=<id>`

### 6.7 `news-page.php` — Individual News Article
- Accepts `?news_id=<id>`, fetches full article from DB
- Shows title, published date, social share icons, and full content with line breaks preserved

### 6.8 `volunteer.php` — Volunteer Page
- Intro images and volunteer registration CTA (links to booking flow if logged in)
- Lists: Volunteer Requirements (4), Opportunities (3), Benefits (3)
- CTA button → `book-appointment.php` (logged in) or `loginpage.php`
- Contains downloadable PDF/DOCX files in `image/volunteer/`

### 6.9 Auth Pages

**`loginpage.php`**
- Slideshow background (3 rotating images)
- Email + password form posting to `function/authcode.php`
- Password show/hide toggle via `script/script.js`
- Link to signup page

**`signuppage.php`**
- Slideshow background
- Fields: First Name, Last Name, Email, Password, Confirm Password
- Posts to `function/authcode.php` with `name="register"`

### 6.10 User Profile Pages

**`edit-profile.php`**
- Pre-fills current `fname`, `lname`, `email` from `$_SESSION['auth_user']`
- Updates user table by email and refreshes session data

**`change-password.php`**
- Requires old password verification against DB (plain text compare)
- Updates password in DB if match

### 6.11 `notification.php` — Appointment Notification
- Reads `?appointmentId=<id>` from URL
- Fetches the `message` column from the `appointment` table
- Displays the message using `nl2br()` to preserve line breaks

### 6.12 `logout.php`
- Unsets `$_SESSION['auth']` and `$_SESSION['auth_user']`
- Redirects to `home.php`

### 6.13 Static Information Pages

| Page | Purpose |
|------|---------|
| `mission.php` | Shelter mission statement |
| `success-stories.php` | Adoption success story gallery |
| `FAQ.php` | Frequently asked questions |
| `contact.php` | Contact info, phone, email, address, Google Maps embed |
| `team.php` | Team member profile cards (5 members) |
| `reference.php` | References/resources |
| `privacy-policy.php` | Privacy policy |
| `terms-of-use.php` | Terms of use |

---

## 7. Appointment Booking Flow (6 Steps)

The booking process is a multi-page wizard using PHP `$_SESSION` to carry data across steps.

```
book-appointment.php  →  book-appointment2.php  →  book-appointment3.php
      (intro)                   (type)              (date & time)
                                                    ↓
book-appointment6.php  ←  book-appointment5.php  ←  book-appointment4.php
   (success page)          (confirmation)            (personal info)
```

| Step | File | Purpose | Data Stored in Session |
|------|------|---------|------------------------|
| 1 | `book-appointment.php` | Intro/welcome page with "not a robot" image and Get Started button | — |
| 2 | `book-appointment2.php` | Select appointment type (`Adopt`, `Donate`, `Visit`, `Volunteer`) | `$_SESSION['appointment_type']` |
| 3 | `book-appointment3.php` | FullCalendar date picker + session selector (Morning/Afternoon). Prevents duplicate bookings (shows error if slot taken). | `$_SESSION['appointment_date']`, `$_SESSION['appointment_time_slot']` |
| 4 | `book-appointment4.php` | Personal info form: first, middle, last name, mobile, home address, email | `$_SESSION['first_name']`, `$_SESSION['middle_name']`, `$_SESSION['last_name']`, `$_SESSION['mobile_number']`, `$_SESSION['home_address']`, `$_SESSION['email_address']`, `$_SESSION['status']` = `'Pending'`, `$_SESSION['message']` |
| 5 | `book-appointment5.php` | Confirmation checkboxes (availability, location, rescheduling). On submit, inserts a prepared statement row into `appointment` with `user_id` from session. | Inserts into DB, redirects to step 6 |
| 6 | `book-appointment6.php` | Success page displaying all appointment details and exit button | Reads session data |

Each step has a progress bar image (`progressbar1.png` → `progressbar5.png`) for visual tracking.

---

## 8. Admin Panel

All admin pages share a common layout: top navbar (logo + Logout) and a sidebar with 7 menu items.

### Admin Sidebar Menu
1. **Dashboard** — `admin-dashboard.php`
2. **Add Pets** — `admin-add-pets.php`
3. **Manage Pets** — `admin-manage-pets.php`
4. **Modify Featured Image** — `admin-manage-featured.php`
5. **Manage Users** — `admin-manage-user.php`
6. **Add News** — `admin-add-news.php`
7. **Manage News** — `admin-manage-news.php`

### 8.1 `admin-dashboard.php` — Appointment Dashboard
- **Stat cards:** Total Appointments, Adopt, Donate, Visit, Volunteer counts
- **FullCalendar:** shows booked dates with session titles; selected date autofills a hidden date input
- **Daily appointment tables:** Morning Session and Afternoon Session tables listing appointments for the selected date
- **Accept/Cancel buttons** for pending appointments:
  - POST AJAX to `update_status.php`
  - Accept → status `Accepted` + confirmation message
  - Cancel → status `Cancelled` + apology message
- Fully booked days (both sessions taken) display yellow on the calendar

### 8.2 `update_status.php` — AJAX Status Handler
- Accepts `appointmentId` and `status` via POST
- Updates `status` and auto-generates a `message`:
  - **Accepted:** "Your appointment is confirmed. Kindly message us within 24 hours..."
  - **Cancelled:** "We're sincerely sorry to cancel your appointment..."
- Returns JSON-ish HTTP status codes (200/400/500)

### 8.3 `admin-add-pets.php` — Add Pet Form
- Fields: name, type (Dog/Cat), breed (dropdown of ~40 breeds), sex, weight, age, date of rescue, about, image upload
- Validates image extension (`jpg`, `jpeg`, `png`) and size (≤ 2MB)
- Uploads to `upload/` with `uniqid()` filename
- Inserts row into `pets` with `user_id` from session

### 8.4 `admin-manage-pets.php` — Manage Pets
- Displays full pet table (ID, image, name, type, breed, sex, weight, age, rescue date, about)
- Clicking a row populates the edit form (via jQuery)
- **Update:** modifies pet details, optionally updates image
- **Delete:** removes pet record

### 8.5 `admin-manage-featured.php` — Featured Pets
- Clears all `is_featured` values to 0, then assigns 4 pet IDs to featured slots 1–4
- The featured pets appear on the home page (`home.php`)

### 8.6 `admin-manage-user.php` — Manage Users
- Table of all users (ID, names, email, **password in plain text**, user type, created date)
- **Update:** edit first/last name, email, optional password
- **Delete:** removes user
- **Promote:** changes `user_type` from 2 → 1 (make admin)
- **Demote:** changes `user_type` from 1 → 2 (make regular)

### 8.7 `admin-add-news.php` — Add News
- Fields: title, details, image upload
- Validates extension (`jpg`, `jpeg`, `png`) and size (≤ 3MB)
- Uploads to `upload/news/` with `uniqid()` filename
- Inserts row into `news` with `user_id` from session

### 8.8 `admin-manage-news.php` — Manage News
- Table of all news (ID, image, title, details, date published, is_featured)
- **Update:** edit title and details
- **Delete:** removes news record
- **Set as Headline:** sets `is_featured = 1` for that article and `0` for all others (only one headline at a time)

---

## 9. CSS & Assets

### 9.1 CSS Files

Each page has a dedicated stylesheet under `css/`, all sharing the Google *Acme* font and Font Awesome icons:

| CSS File | Page |
|----------|------|
| `index.css` | Landing page |
| `home.css` | Home page |
| `adoptpage.css` | Pet listing |
| `adoptprofile.css` | Pet detail |
| `book-appointment.css` → `book-appointment6.css` | Each of the 6 booking steps |
| `donate.css` | Donation page |
| `news.css` / `news-page.css` | News listing / article |
| `volunteer.css` | Volunteer page |
| `loginpage.css` / `signuppage.css` | Auth pages |
| `edit-profile.css` / `change-password.css` | Profile pages |
| `notification.css` | Appointment notification |
| `contact.css` / `team.css` / `FAQ.css` / `mission.css` | Info pages |
| `success-stories.css` / `reference.css` | More info pages |
| `privacy-policy.css` / `terms-of-use.css` | Legal pages |
| `navbar.css` / `footer.css` | Shared components |
| `admin.css` / `admin-dashboard.css` / `admin-pets.css` / `admin-featured.css` / `admin news.css` | Admin panel |

### 9.2 Image Assets

- **Landing:** `landingbg.png`, `landingtext.png`, `landingfooter.png`, backgrounds
- **Home:** `paw.png`, `pets.png`, `donatebg.png`, `doggo.png`, `catto.png`
- **Donate:** QR codes for Bank and GCash, `donDog.jpeg`, `donCat.jpeg`
- **Login/Signup:** 3 slideshow backgrounds in `image/LoginSignup/`
- **Booking:** progress bar step images + `imnotarobot.png`
- **News:** pet tips & headline images in `image/news/`
- **Team:** 5 member photos (`ALFARO.png`, `GAMBOA.png`, `IBAY.png`, `LAXAMANA.png`, `LUZANO.png`)
- **Volunteer:** images, bullet icons, PDF/DOCX requirement files
- **Uploads:** user-uploaded pet photos in `upload/` and news photos in `upload/news/`

---

## 10. JavaScript & External Libraries

### 10.1 `script/script.js`
- Toggles password visibility (`#show-password` button on login/signup forms)

### 10.2 Inline Scripts Used Across Pages

| Script | Used On | Purpose |
|--------|---------|---------|
| `toggleMenu()` | Home, navbar, index | Mobile responsive hamburger |
| `logout()` | All pages with navbar | Confirm + redirect to `logout.php` |
| Slideshow `showSlides()` | Login, Signup, News | Auto-rotating background/carousel images (4s interval) |
| FullCalendar init | `book-appointment3.php`, `admin-dashboard.php` | Date picker with booked slots |
| `updateStatus()` (AJAX) | `admin-dashboard.php` | Accept/cancel appointment via `update_status.php` |
| Row-click form population | `admin-manage-pets.php`, `admin-manage-news.php`, `admin-manage-user.php` | jQuery populates edit form from selected table row |

### 10.3 External Libraries (CDN)

- **jQuery 3.6.0** — row selection, AJAX
- **Bootstrap 4.5.2** — responsive forms, buttons, tables
- **FullCalendar 3.10.2** + **Moment.js 2.29.1** — appointment calendar
- **Font Awesome 6** / **5.15.3** — icons
- **Google reCAPTCHA** — script loaded on booking pages (though not visibly wired to forms)

---

## 11. Security Notes & Known Limitations

> ⚠️ These are observations for future improvement, not active bugs to be fixed without discussion.

### Security Issues

1. **Plain-text passwords** — stored and compared in plain text; should use `password_hash()` / `password_verify()`.
2. **SQL injection** — most queries concatenate user input directly into SQL strings. `book-appointment5.php` uses prepared statements, but most other pages do not.
3. **No session-protection on admin pages** — `admin-*.php` pages do not verify `$_SESSION['auth']` or `user_type == 1` before rendering; any user could visit admin URLs directly.
4. **File upload validation** — validates extension but not MIME type; filenames are randomized with `uniqid()` which mitigates some risk.
5. **Database credentials hardcoded** — `config.php` contains production-like credentials in plain text.
6. **No CSRF protection** — forms lack CSRF tokens.
7. **`notification.php`** directly injects `$_GET['appointmentId']` into a query without sanitization.

### Functional Limitations

1. **Appointment slots** — only two time slots (morning/afternoon) and no per-slot capacity limit beyond "exists = booked".
2. **Booking step 1** — the "I'm not a robot" checkbox is purely a static image; no actual reCAPTCHA verification.
3. **Session carries booking data** — if a user opens a second tab or abandons the wizard, session data persists.
4. **Single headline news** — only one article can be `is_featured = 1` (enforced in `admin-manage-news.php`).
5. **No pagination** — pet and news listings show all records at once.
6. **Password field shown in admin table** — `admin-manage-user.php` displays user passwords in plain text in the table.
7. **`edit-profile.php`** updates the user by email, so changing email before updating name could fail to locate the correct record.
8. **`change-password.php`** loads `auth_user` data into `fname`/`lname`/`email` fields even though the form only has password fields — that inline script populates non-existent inputs (harmless but dead code).

### Environment Notes

- The SQL dump was generated with `localhost:3307`, but `config.php` connects to `localhost:3306`. Update `config.php` (or the DB port) to match your local setup.
- Default admin credentials from seed data: **`admin@gmail.com` / `1234`**

---

## 12. How to Run / Local Setup

This is a standard PHP + MySQL application. You need a local web server environment (PHP + MySQL). Since you're on **macOS**, here are the best alternatives to XAMPP:

### Option A: MAMP (macOS-native GUI, easiest)

MAMP is a macOS app that bundles Apache (or Nginx), PHP, and MySQL with a simple GUI.

1. **Install MAMP** from <https://www.mamp.info>

2. **Copy the project** into MAMP's web root:
   ```
   /Applications/MAMP/htdocs/repawcity/
   ```

3. **Start MAMP** — click **Start Servers** in the MAMP control panel.

4. **Create the database:**
   - Open phpMyAdmin at <http://localhost:8888/phpmyadmin>
   - Click **Import** → choose `database/repawcity.sql` from this project
   - Click **Go**. This creates the `repawcity` database with all tables and seed data.

5. **Adjust DB credentials** in `function/config.php` — MAMP's MySQL uses port `8889` by default:
   ```php
   $servername = "localhost:8889";
   $username = "root";        // MAMP default user is "root"
   $password = "root";        // MAMP default password is "root"
   $dbname = "repawcity";
   ```
   > If you prefer not to use root, create a user in phpMyAdmin: username `admin`, password `admin123`, host `localhost`, with all privileges on `repawcity`.

6. **Access the site:**
   - Public pages: <http://localhost:8888/repawcity/index.php>
   - Admin login: <http://localhost:8888/repawcity/loginpage.php>
     - Email: `admin@gmail.com`
     - Password: `1234`

---

### Option B: Homebrew + PHP built-in server (lightweight, no Apache)

This uses PHP's built-in web server — no Apache/Nginx needed at all. Great for development.

1. **Install PHP and MySQL** via Homebrew:
   ```bash
   brew install php mysql
   ```

2. **Start MySQL:**
   ```bash
   brew services start mysql
   ```

3. **Create the database and user:**
   ```bash
   mysql -u root -e "CREATE DATABASE repawcity;"
   mysql -u root -e "CREATE USER 'admin'@'localhost' IDENTIFIED BY 'admin123';"
   mysql -u root -e "GRANT ALL PRIVILEGES ON repawcity.* TO 'admin'@'localhost';"
   mysql -u root repawcity < database/repawcity.sql
   ```

4. **Run PHP's built-in server** from the project root:
   ```bash
   php -S localhost:8000
   ```

5. **Open** <http://localhost:8000> in your browser.

---

### Option C: Docker (isolated, most portable)

Run both PHP and MySQL in containers without installing anything locally.

1. **Install Docker Desktop** from <https://www.docker.com/products/docker-desktop>

2. Start a MySQL container:
   ```bash
   docker run -d \
     --name repawcity-db \
     -e MYSQL_DATABASE=repawcity \
     -e MYSQL_USER=admin \
     -e MYSQL_PASSWORD=admin123 \
     -e MYSQL_ROOT_PASSWORD=root \
     -p 3306:3306 \
     mysql:8.0
   ```

3. Import the SQL dump:
   ```bash
   docker exec -i repawcity-db mysql -uadmin -padmin123 repawcity < database/repawcity.sql
   ```

4. Run the PHP built-in server (PHP must be installed at least for this step):
   ```bash
   php -S localhost:8000
   ```

5. Open <http://localhost:8000>

---

### Option D: XAMPP (only if you prefer — works on macOS/Windows)

XAMPP is a valid option if you want a single all-in-one package, but since you preferred not to use it, use MAMP, Homebrew, or Docker above instead.

---

### Quick Checklist (any option)

| Step | What to do |
|------|-----------|
| 1 | Start MySQL (MAMP GUI, `brew services start mysql`, or Docker container) |
| 2 | Import `database/repawcity.sql` (creates `repawcity` DB + tables + seed data) |
| 3 | Update `function/config.php` credentials to match your setup (port may differ!) |
| 4 | Serve the PHP files (MAMP Apache, `php -S localhost:8000`, or Docker) |
| 5 | Open `http://localhost:<port>/repawcity/index.php` |
| 6 | Log in as admin: `admin@gmail.com` / `1234` |

> ⚠️ **Important:** The config file currently uses `localhost:3306` with user `admin` / password `admin123`. If you use MAMP (default port `8889`, root/root), Homebrew MySQL (port `3306`, root/blank), or Docker (port `3306`, admin/admin123), adjust `function/config.php` accordingly.

---

## License & Origin

- GitHub repository: `https://github.com/AianLouise/REPAWCITY`
- Project appears to be an academic/portfolio project focused on a Philippine pet-adoption shelter.

# Globwocs Co. Ltd — Website Deployment Guide

## Overview
PHP/MySQL website for Globwocs Co. Ltd — 4 public pages + admin panel.
Compatible with cPanel shared hosting (PHP 7.4+ / MySQL 5.7+).

---

## Deployment Steps

### Step 1 — Upload Files
Upload the entire contents of this folder to your `public_html/` directory via:
- cPanel File Manager → Upload the zip → Extract
- OR FTP client (FileZilla, Cyberduck, etc.) → Drag and drop

The final structure should be:
```
public_html/
├── index.php
├── projects.php
├── about.php
├── contact.php
├── contact-handler.php
├── .htaccess
├── install.sql
├── assets/
├── includes/
├── uploads/
└── admin/
```

---

### Step 2 — Create MySQL Database
1. Log into cPanel → **MySQL Databases**
2. Create a new database (e.g. `youracct_globwocs`)
3. Create a new database user with a strong password
4. Add the user to the database with **All Privileges**
5. Note down: database name, username, password, host (usually `localhost`)

---

### Step 3 — Configure Database Connection
Edit `includes/db.php`:
```php
define('DB_HOST', 'localhost');            // usually localhost
define('DB_NAME', 'youracct_globwocs');    // your database name
define('DB_USER', 'youracct_dbuser');      // your database user
define('DB_PASS', 'YourStrongPassword');   // your database password
```

---

### Step 4 — Import Database Schema
1. cPanel → **phpMyAdmin** → select your database
2. Click **Import** tab
3. Choose `install.sql` → click **Go**

This creates:
- `projects` table
- `project_images` table  
- `admin_users` table (with default admin login)

---

### Step 5 — Set Directory Permissions
In cPanel File Manager, right-click each folder → Permissions:
- `uploads/` → **755**
- `uploads/projects/` → **755**

---

### Step 6 — First Login & Change Password
1. Visit `https://yourdomain.com/admin/`
2. Login with: **username:** `admin` | **password:** `password`
3. ⚠️ **Immediately** go to **Change Password** and set a strong password

---

### Step 7 — Add Your Content

#### Logo
- Add your logo file to `assets/img/logo.png`
- Or update the nav text in `includes/nav.php` to match your logo filename

#### Hero & Page Images
Place these images in `assets/img/`:

| Filename | Used On | Recommended Size |
|----------|---------|-----------------|
| `hero-1.jpg` | Homepage hero slide 1 | 1920×1080px |
| `hero-2.jpg` | Homepage hero slide 2 | 1920×1080px |
| `hero-3.jpg` | Homepage hero slide 3 | 1920×1080px |
| `hero-4.jpg` | Homepage hero slide 4 | 1920×1080px |
| `about.jpg` | Homepage about section | 900×1100px |
| `about-hero.jpg` | About page header | 1920×1080px |
| `about-mid.jpg` | About page divider | 1920×600px |
| `projects-hero.jpg` | Projects page header | 1920×1080px |
| `contact-hero.jpg` | Contact page header | 1920×600px |

**Free image sources (no attribution required):**
- https://unsplash.com — search "architecture", "building", "interior design"
- https://pexels.com — search "architecture exterior"
- https://pixabay.com — search "modern building"

Recommended search terms for architectural firm:
- "luxury modern house exterior"
- "contemporary architecture Africa"
- "architectural design office interior"
- "modern building facade"

#### Projects
1. Admin → **+ Add Project**
2. Fill in: Name, Description, Location, Year, Category, Area
3. Upload project images (drag & drop, up to 20)
4. Click the image thumbnail to select the cover image
5. Check "Feature on homepage" for 3 key projects

---

### Step 8 — Enable HTTPS
If SSL is active on your domain:
1. Edit `.htaccess`
2. Uncomment the HTTPS redirect lines (remove the `#`)

---

## Contact Form Setup
The contact form uses PHP `mail()` which works on most cPanel hosts.
If emails aren't arriving:
- Check the spam folder first
- Your host may require SMTP — install PHPMailer and update `contact-handler.php`
- Popular SMTP options: Gmail, Zoho Mail, Mailgun

---

## Page Structure

| File | Description |
|------|-------------|
| `index.php` | Homepage — hero, about, stats, services, featured projects |
| `projects.php` | Portfolio — masonry grid with filter + lightbox (DB-driven) |
| `about.php` | Story, values, services detail, memberships |
| `contact.php` | Enquiry form + contact info |
| `contact-handler.php` | AJAX form endpoint (do not delete) |
| `admin/` | Full admin panel |

---

## Admin Panel

| URL | Description |
|-----|-------------|
| `/admin/` | Dashboard |
| `/admin/projects.php` | List all projects |
| `/admin/project-add.php` | Add new project + upload images |
| `/admin/project-edit.php?id=N` | Edit project / manage images |
| `/admin/password.php` | Change admin password |

---

## Tech Requirements
- PHP 7.4+ (PHP 8.x recommended)
- MySQL 5.7+ or MariaDB 10.2+
- `mod_rewrite` enabled (standard on cPanel)
- `GD` or `Imagick` NOT required — images served as-uploaded

---

## Security Notes
- Admin is protected by PHP session authentication
- All DB queries use PDO prepared statements (SQL injection safe)
- Images validated by MIME type before upload
- PHP execution blocked in `/uploads/` via `.htaccess`
- `Options -Indexes` prevents directory browsing
- Optional: restrict `/admin/` to your IP in `admin/.htaccess`

---

## Customisation

### Updating Contact Details
Edit `includes/footer.php` and `contact.php` — phone/email appear in both.

### Changing Colours
Edit the `:root` block at the top of `assets/css/style.css`:
```css
--stone:  #c4a882;  /* gold accent — change to your brand colour */
--ink:    #0c0b09;  /* near-black background */
```

### Changing Fonts
Replace the Google Fonts import URL in `includes/head.php`:
Current: Playfair Display (display) + DM Sans (body) + DM Mono (labels)

---

*Globwocs Co. Ltd — Dream it. Design it. Build it.*

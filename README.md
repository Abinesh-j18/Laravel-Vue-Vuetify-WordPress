# Laravel back-office with a Vue + Vuetify 

A Laravel back-office with a Vue + Vuetify frontend that integrates with a WordPress blog.  
Admins can manage WordPress posts (CRUD) and assign a priority to each post, stored only in Laravel.

---

## Project Overview 

▶️ [Visit My Website](https://abinesh.me/)

- **Stack:** Laravel 12, Vue 3, Vuetify 3, WordPress REST API
- **Purpose:** Provide a secure back-office to manage WordPress blog posts with additional Laravel-only features.
- **Features:**
  - WordPress OAuth login (only admins)
  - View all posts from WordPress (published + draft)
  - Create, edit, and delete posts (synced to WordPress)
  - Assign and sort posts by a **priority** field (Laravel-only)
  - Sync changes automatically from WordPress
  - ▶️ [Watch the video on YouTube](https://www.youtube.com/watch?v=pG5QZNYZmho)


[![Watch the video](https://img.youtube.com/vi/pG5QZNYZmho/maxresdefault.jpg)](https://www.youtube.com/watch?v=pG5QZNYZmho)


---

## Requirements

- PHP 8.2+
- Composer
- Node.js 20+
- MySQL
- WordPress.com account with a blog site
- Wordpress Site (Approved WordPress  Users only) :] (https://abiproject3.wordpress.com)

---

## Project Setup (Step-by-Step)

### 1. Clone the repository
```bash
git clone https://github.com/your-username/blog-backoffice.git
cd blog-backoffice
```
### 2. Add a .gitignore
Create a .gitignore file in the project root with at least these lines:
```bash
/vendor
/node_modules
/public/build
/.env
```
### 3. Install dependencies
Backend:
```bash
composer install
```
Frontend:
```bash
npm install
```
### 4. Configure environment
```bash
cp .env.example .env
```
Edit .env and set your database and WordPress OAuth credentials:
```bash
APP_NAME=BlogBackoffice
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blog_backoffice
DB_USERNAME=root
DB_PASSWORD=

WPCLIENT_ID=YOUR_WORDPRESS_CLIENT_ID
WPCLIENT_SECRET=YOUR_WORDPRESS_CLIENT_SECRET
WP_REDIRECT_URI=http://127.0.0.1:8000/auth/callback
```
### 5. Setup the database
### 1.Open phpMyAdmin or MySQL CLI and create a database:
```bash
CREATE DATABASE blog_backoffice;
```
### 2.Run Laravel migrations:
```bash
php artisan migrate
```
### 6. Build frontend
- Development (hot reload):
```bash
npm run dev
```
- Production build:
```bash
npm run build
```
### 7. Run Laravel server
```bash
php artisan serve
```
Visit: https://abinesh.me/
---
### Important URLs

- OAuth Redirect (WordPress login / token refresh):
  http://127.0.0.1:8000/auth/redirect
  Use this whenever the WordPress token is missing or expired.

- Back-office Home:
  http://127.0.0.1:8000/backoffice/blog

- Root URL (redirects to back-office):
  http://127.0.0.1:8000
---
### Usage
### Login

- Click login → WordPress OAuth page.

- Only WordPress administrators can log in.

### Blog Management

- View posts: All posts from WordPress are listed.

- Create post: Click Add New Post, fill in the fields, save.

- Edit post: Click Edit, modify fields, save.

- Delete post: Click Delete.

- Set priority: Enter a number in the priority field → Save.

- Sorting: Posts can be sorted by priority (optional in UI).
---
  ### Project Structure

- Backend (Laravel)

  - app/Http/Controllers/BlogController.php → Handles CRUD + priority
 
  - app/Models/WpToken.php → Stores WordPress OAuth token
 
  - database/migrations → Creates wp_posts & wp_tokens tables with priority column

- Frontend (Vue + Vuetify)

  - resources/js/components/BlogManagement.vue → Main component for managing posts
 
  - resources/js/app.js → Boots Vue

    WordPress Integration

### Uses WordPress REST API v2:

- /wp/v2/sites/{site_slug}/posts

- OAuth authentication for admin users.

- Laravel-only field priority is not synced to WordPress.
  ---
### Git & Commits
Initialize Git repo:
  ```bash
  git init
  git add .
  git commit -m "Initial commit"
  ```
Push to GitHub:
```bash
git branch -M main
git remote add origin https://github.com/your-username/blog-backoffice.git
git push -u origin main
```

Incremental commits are recommended:
```bash
git add .
git commit -m "Add BlogManagement.vue with priority support"
git push
```
---
### Notes

Error Handling: API errors show alerts in the back-office.

Laravel-only features: Priority field exists only in Laravel DB.

Security: OAuth ensures only WordPress admin users can access the back-office.
---
### Author

Your Name: Abinesh Sivakumar

Contact: infoabinesh@gmail.com

GitHub: https://github.com/Abinesh-j18

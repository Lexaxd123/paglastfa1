# Morris POS (CodeIgniter 4 + MySQL)

IT0049 Web System Technologies, **TFA3: Forms, Validation, and File Upload**.

- **TFA1** showed Customers and Users from static PHP arrays.
- **TFA2** moved that data into MySQL, read through CodeIgniter Models.
- **TFA3** adds validated **New** and **Edit** forms for both, plus a **profile-picture upload** for users. Each upload is checked, turned into a 200 × 200 thumbnail, and saved in `public/uploads/avatars/`; only its filename goes into the database.

## Pages

| Method | Route                     | Controller            | What it does                                   |
|--------|---------------------------|-----------------------|------------------------------------------------|
| GET    | `/`                       | `Pages::index`        | Home page                                      |
| GET    | `/about`                  | `Pages::about`        | About page                                     |
| GET    | `/customers`              | `Customers::index`    | Customer list with **Add** / **Edit** buttons  |
| GET    | `/customers/new`          | `Customers::new`      | Empty New Customer form                        |
| POST   | `/customers/create`       | `Customers::create`   | Validates, then inserts                        |
| GET    | `/customers/edit/{id}`    | `Customers::edit`     | Edit form pre-filled from the database         |
| POST   | `/customers/update/{id}`  | `Customers::update`   | Validates, then updates                        |
| GET    | `/users`                  | `Users::index`        | User list with avatars (placeholder if none)   |
| GET    | `/users/new`              | `Users::new`          | Empty New User form                            |
| POST   | `/users/create`           | `Users::create`       | Validates (unique username), then inserts      |
| GET    | `/users/edit/{id}`        | `Users::edit`         | Edit form with profile-picture upload          |
| POST   | `/users/update/{id}`      | `Users::update`       | Validates fields + image, prepares avatar, updates |

## Validation rules

| Form      | Field          | Rules                                                                 |
|-----------|----------------|-----------------------------------------------------------------------|
| Customer  | Full Name      | required, 2–100 characters                                            |
| Customer  | Email Address  | required, valid email, max 100                                        |
| Customer  | Phone Number   | optional, max 20, digits / spaces / `+ - ( )` only                    |
| User      | Username       | required, 3–50, letters / numbers / `. _ -`, **unique** (`is_unique`) |
| User      | Full Name      | required, 2–100 characters                                            |
| User edit | Profile Picture| optional; real image (`is_image`), JPG/PNG by content and extension, max 2MB |

When a form fails validation, it is shown again with a message under each wrong field **and everything the user typed is kept**.

## Project structure (files added or changed in TFA3)

```
app/
├── Config/
│   ├── Filters.php                 # CSRF protection turned on for every POST
│   └── Routes.php                  # new / create / edit / update routes
├── Controllers/
│   ├── Customers.php               # index, new, create, edit, update
│   └── Users.php                   # same + avatar validation and preparation
├── Database/Migrations/
│   └── ..._AddFieldsToUsers.php    # adds users.avatar (same as tfa3_add_avatar.sql)
├── Models/
│   ├── CustomerModel.php
│   └── UserModel.php               # avatar added to allowedFields
└── Views/
    ├── partials/navbar.php         # shared navigation bar
    ├── customers/index.php         # Add / Edit buttons, Date Added column
    ├── customers/form.php          # one form for New and Edit
    ├── users/index.php             # Avatar column
    └── users/form.php              # one form for New and Edit (+ upload on Edit)
public/
├── css/style.css                   # form, error, and avatar styles
├── images/avatar-placeholder.svg   # shown when a user has no avatar
└── uploads/
    ├── .htaccess                   # only .jpg/.jpeg/.png can be served from here
    └── avatars/                    # prepared avatars (not committed to Git)
database/
├── pos_db.sql                      # full schema + sample data (fresh install)
└── tfa3_add_avatar.sql             # upgrade script for an existing TFA2 database
```

## Requirements

- PHP 8.2+ with the `intl`, `mbstring`, and `mysqli` extensions
- Composer
- MySQL 8 or MariaDB 10.x (XAMPP or Laragon works)

## Local setup

1. **Clone the project and install dependencies**

   ```bash
   git clone https://github.com/Lexaxd123/paglastfa1.git
   cd paglastfa1
   composer install
   ```

2. **Create the database.** Start MySQL. Then import the export with phpMyAdmin (Import tab → `database/pos_db.sql`) or from the terminal:

   ```bash
   mysql -u root -p < database/pos_db.sql
   ```

   This creates `pos_db` with the `customers` and `users` tables (including `users.avatar`) and 5 sample records in each.

   **Already have the TFA2 database?** Run only the upgrade script instead, so your records are kept:

   ```bash
   mysql -u root -p pos_db < database/tfa3_add_avatar.sql
   ```

   (or `php spark migrate`, which runs the same change.)

3. **Configure the environment.**

   ```bash
   cp .env.example .env
   ```

   Edit `.env` and set your MySQL credentials:

   ```ini
   CI_ENVIRONMENT = development
   app.baseURL = 'http://localhost:8080/'

   database.default.hostname = localhost
   database.default.database = pos_db
   database.default.username = root
   database.default.password =
   database.default.DBDriver = MySQLi
   database.default.port = 3306
   ```

4. **Run the app.**

   ```bash
   php spark serve
   ```

   Open <http://localhost:8080/customers> and <http://localhost:8080/users>.

## Deploying to InfinityFree

The live site runs on InfinityFree. The whole project is uploaded into `htdocs/`, and the root `.htaccess` sends every request to `public/` while blocking direct access to `.env`, `composer.*`, `spark`, `.sql` files, and similar. The `.htaccess` files in `app/`, `tests/`, `writable/`, `vendor/`, and `database/` deny all web access. They use `mod_rewrite` instead of `Require all denied` / `Options` because InfinityFree's restricted `AllowOverride` rejects those directives with a 500 error.

1. **Create the database.** In the InfinityFree control panel, open **MySQL Databases** and create one (for example `pos_db`). InfinityFree adds a prefix, so the full name looks like `if0_XXXXXXXX_pos_db`. Note the **MySQL hostname** (`sqlXXX.infinityfree.com`, *not* `localhost`), the username (`if0_XXXXXXXX`), and the password (your account password, shown in the control panel).
2. **Import the tables.** Open phpMyAdmin from the control panel and select the new database. Comment out the `CREATE DATABASE` and `USE` lines in `database/pos_db.sql` (InfinityFree doesn't allow either), then import the file.
3. **Upload the files.** Upload the project into `htdocs/` with the File Manager or FTP. Include the `vendor/` folder, because InfinityFree has no Composer. If you build it yourself, use `composer install --no-dev --optimize-autoloader`. Don't upload `.git/` or `tests/`.
4. **Create `.env` on the server.** Create it directly in `htdocs/`. Don't commit it.

   ```ini
   CI_ENVIRONMENT = production
   app.baseURL = 'https://your-subdomain.infinityfree.me/'

   database.default.hostname = sqlXXX.infinityfree.com
   database.default.database = if0_XXXXXXXX_pos_db
   database.default.username = if0_XXXXXXXX
   database.default.password = your_vpanel_password
   database.default.DBDriver = MySQLi
   database.default.port = 3306
   ```

5. Open `/customers` and `/users` on the live site to confirm the records load.

**Upgrading the live site from TFA2 to TFA3:** in phpMyAdmin, import `database/tfa3_add_avatar.sql` (adds the `avatar` column, keeps existing records), then upload the changed files listed in *Project structure* above. The `public/uploads/avatars/` folder must stay writable so PHP can save the prepared avatars.

InfinityFree's MySQL only accepts connections from its own web servers. You can't connect your local app to the hosted database; use phpMyAdmin to manage it instead.

## Security notes

- **Validation on the server.** Every rule runs in PHP, so it can't be skipped by editing the page in the browser. The forms read only the fields they expect, and `$allowedFields` in each Model stops anything else (like `id` or `created_at`) from being written.
- **CSRF protection** is on for every POST (`app/Config/Filters.php`). Each form includes `csrf_field()`, so a form submitted from another website is refused and nothing is saved (in production CodeIgniter redirects back; in development it shows a 403).
- **Output escaping.** Everything printed in a view goes through `esc()`, so a name like `<script>…</script>` is shown as text, not run.
- **Uploads are checked by content, not just by name.** `is_image` and `mime_in` look inside the file, and `ext_in` checks the name; a renamed `.php` file is rejected.
- **Uploads are re-drawn.** The original file is never kept. The Image service draws a fresh 200 × 200 picture with a random filename, which also drops hidden extras like GPS data or code tucked into the file.
- **The uploads folder can't run code.** `public/uploads/.htaccess` refuses anything that isn't a `.jpg`, `.jpeg`, or `.png`.
- `.env` is listed in `.gitignore`, so real database credentials never reach GitHub. Use `CI_ENVIRONMENT = production` on the live site so error pages don't reveal file paths or database details.

> **Note for GitHub's "Upload files" button:** it skips files whose names start with a dot (`.htaccess`, `.env.example`, `.gitignore`). Use Git, GitHub Desktop, or *Add file → Create new file* to add those, or the repository will not match the hosted site.

## Database schema

```sql
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    created_at DATETIME NOT NULL
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    avatar VARCHAR(255) NULL,      -- TFA3: filename of the prepared avatar
    created_at DATETIME NOT NULL
);
```

## Links

- GitHub: https://github.com/Lexaxd123/paglastfa1
- Live site: https://morrispaglas.infinityfree.me/

# Morris POS (CodeIgniter 4 + MySQL)

IT0049 Web System Technologies, **TFA2: From Arrays to a Real Database**.

TFA1 stored Customers and Users in static PHP arrays. TFA2 moves that data into a MySQL database. Each page now reads its records through a CodeIgniter **Model** with Query Builder (`findAll()`), not raw SQL.

## Pages

| Route        | Controller           | Data source                           |
|--------------|----------------------|---------------------------------------|
| `/`          | `Pages::index`       | static                                |
| `/about`     | `Pages::about`       | static                                |
| `/customers` | `Customers::index`   | `CustomerModel` → `customers` table   |
| `/users`     | `Users::index`       | `UserModel` → `users` table           |

## Project structure (files changed in TFA2)

```
app/
├── Controllers/
│   ├── Customers.php        # now uses CustomerModel->findAll()
│   └── Users.php            # now uses UserModel->findAll()
├── Models/
│   ├── CustomerModel.php    # wraps the customers table
│   └── UserModel.php        # wraps the users table
└── Views/
    ├── customers/index.php  # reads full_name instead of name
    └── users/index.php      # columns follow the users schema
database/
└── pos_db.sql               # schema + sample data (database export)
.env.example                 # template for the .env database settings
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

   This creates `pos_db` with the `customers` and `users` tables and 5 sample records in each.

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

InfinityFree's MySQL only accepts connections from its own web servers. You can't connect your local app to the hosted database; use phpMyAdmin to manage it instead.

## Security notes

- `.env` is listed in `.gitignore`, so real database credentials never reach GitHub. Commit `.env.example` instead.
- Use `CI_ENVIRONMENT = production` on the live site. Development mode shows stack traces and the debug toolbar, which can expose file paths and database details.
- Every value printed in a view goes through `esc()` to prevent XSS.
- Models restrict writable columns with `$allowedFields`, which blocks mass-assignment of fields like `id`.
- On the host, use a database user limited to this one database rather than an admin account.

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
    created_at DATETIME NOT NULL
);
```

## Links

- GitHub: https://github.com/Lexaxd123/paglastfa1
- Live site: https://morrispaglas.infinityfree.me/

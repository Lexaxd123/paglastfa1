<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>About | Morris POS</title>

    <link
        rel="stylesheet"
        href="<?= base_url('css/style.css') ?>"
    >
</head>

<body>
    <nav class="navbar">
        <a class="logo" href="<?= site_url('/') ?>">
            🛒 Morris POS
        </a>

        <ul class="nav-links">
            <li><a href="<?= site_url('/') ?>">Home</a></li>

            <li>
                <a class="active" href="<?= site_url('about') ?>">
                    About
                </a>
            </li>

            <li>
                <a href="<?= site_url('customers') ?>">Customers</a>
            </li>

            <li><a href="<?= site_url('users') ?>">Users</a></li>
        </ul>
    </nav>

    <main class="container">
        <h1 class="page-title">About Our POS System</h1>

        <p class="page-description">
            Morris POS is a simple and friendly system designed to help
            businesses manage customers, users, and daily information.
        </p>

        <section class="about-grid">
            <article class="card">
                <div class="card-icon">⚡</div>

                <h2>Fast</h2>

                <p>
                    Quickly access important customer and user information.
                </p>
            </article>

            <article class="card">
                <div class="card-icon">🔒</div>

                <h2>Reliable</h2>

                <p>
                    Organized pages make your information easier to manage.
                </p>
            </article>

            <article class="card">
                <div class="card-icon">😊</div>

                <h2>Friendly</h2>

                <p>
                    A clean and responsive design makes the system easy to use.
                </p>
            </article>
        </section>
    </main>

    <footer>
        &copy; <?= date('Y') ?> Morris POS System
    </footer>
</body>
</html>
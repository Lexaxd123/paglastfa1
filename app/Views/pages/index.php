<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>POS System</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            background:
                radial-gradient(
                    circle at top right,
                    rgba(52, 211, 153, 0.18),
                    transparent 35%
                ),
                linear-gradient(135deg, #052e16, #064e3b, #022c22);
            color: #ffffff;
            font-family: Arial, Helvetica, sans-serif;
        }

        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 20px 8%;
            background: rgba(3, 35, 25, 0.9);
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.25);
        }

        .logo {
            color: #a7f3d0;
            font-size: 25px;
            font-weight: bold;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 10px;
            list-style: none;
        }

        .nav-links a {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 8px;
            color: #ecfdf5;
            text-decoration: none;
            transition: 0.25s ease;
        }

        .nav-links a:hover,
        .nav-links a.active {
            background: #10b981;
            color: #022c22;
            transform: translateY(-2px);
        }

        .hero {
            max-width: 1150px;
            margin: auto;
            padding: 90px 8% 50px;
            text-align: center;
        }

        .badge {
            display: inline-block;
            margin-bottom: 20px;
            padding: 8px 15px;
            border: 1px solid rgba(167, 243, 208, 0.35);
            border-radius: 30px;
            background: rgba(16, 185, 129, 0.12);
            color: #a7f3d0;
            font-size: 14px;
        }

        h1 {
            margin-bottom: 18px;
            font-size: clamp(38px, 7vw, 68px);
            line-height: 1.1;
        }

        h1 span {
            color: #6ee7b7;
        }

        .hero-description {
            max-width: 700px;
            margin: 0 auto 25px;
            color: #d1fae5;
            font-size: 18px;
            line-height: 1.7;
        }

        .clock {
            margin-bottom: 35px;
            color: #a7f3d0;
            font-weight: bold;
        }

        .buttons {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .button {
            display: inline-block;
            padding: 14px 24px;
            border: 1px solid #34d399;
            border-radius: 10px;
            color: white;
            font-weight: bold;
            text-decoration: none;
            transition: 0.25s ease;
        }

        .button-primary {
            background: #10b981;
            color: #022c22;
        }

        .button:hover {
            background: #34d399;
            color: #022c22;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.25);
        }

        .features {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 22px;
            max-width: 1150px;
            margin: auto;
            padding: 30px 8% 90px;
        }

        .card {
            padding: 28px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
            transition: 0.3s ease;
        }

        .card:hover {
            border-color: #34d399;
            background: rgba(16, 185, 129, 0.14);
            transform: translateY(-8px);
        }

        .card-icon {
            margin-bottom: 15px;
            font-size: 38px;
        }

        .card h2 {
            margin-bottom: 10px;
            color: #a7f3d0;
        }

        .card p {
            margin-bottom: 20px;
            color: #d1fae5;
            line-height: 1.6;
        }

        .card a {
            color: #6ee7b7;
            font-weight: bold;
            text-decoration: none;
        }

        .card a:hover {
            color: white;
        }

        footer {
            padding: 22px;
            background: rgba(3, 35, 25, 0.9);
            color: #a7f3d0;
            text-align: center;
        }

        @media (max-width: 800px) {
            .navbar {
                flex-direction: column;
            }

            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }

            .hero {
                padding-top: 60px;
            }

            .features {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <a class="logo" href="<?= site_url('/') ?>">
            🛒 Morris POS
        </a>

        <ul class="nav-links">
            <li>
                <a class="active" href="<?= site_url('/') ?>">Home</a>
            </li>

            <li>
                <a href="<?= site_url('about') ?>">About</a>
            </li>

            <li>
                <a href="<?= site_url('customers') ?>">Customers</a>
            </li>

            <li>
                <a href="<?= site_url('users') ?>">Users</a>
            </li>
        </ul>
    </nav>

    <main>
        <section class="hero">
            <div class="badge">Simple • Fast • Reliable</div>

            <h1>
                Welcome to the
                <span>POS System</span>
            </h1>

            <p class="hero-description">
                Manage your customers and users from one friendly,
                organized, and easy-to-use dashboard.
            </p>

            <p class="clock" id="clock">
                Loading date and time...
            </p>

            <div class="buttons">
                <a
                    class="button button-primary"
                    href="<?= site_url('customers') ?>"
                >
                    View Customers
                </a>

                <a
                    class="button"
                    href="<?= site_url('users') ?>"
                >
                    View Users
                </a>
            </div>
        </section>

        <section class="features">
            <article class="card">
                <div class="card-icon">👥</div>

                <h2>Customers</h2>

                <p>
                    View customer names, email addresses, and contact
                    information.
                </p>

                <a href="<?= site_url('customers') ?>">
                    Open customers →
                </a>
            </article>

            <article class="card">
                <div class="card-icon">👤</div>

                <h2>Users</h2>

                <p>
                    View registered users and check their assigned roles.
                </p>

                <a href="<?= site_url('users') ?>">
                    Open users →
                </a>
            </article>

            <article class="card">
                <div class="card-icon">ℹ️</div>

                <h2>About</h2>

                <p>
                    Learn more about the POS system and its purpose.
                </p>

                <a href="<?= site_url('about') ?>">
                    Learn more →
                </a>
            </article>
        </section>
    </main>

    <footer>
        &copy; <?= date('Y') ?> Morris POS System. All rights reserved.
    </footer>

    <script>
        function updateClock() {
            const clock = document.getElementById('clock');

            const now = new Date();

            clock.textContent = now.toLocaleString('en-PH', {
                dateStyle: 'full',
                timeStyle: 'medium'
            });
        }

        updateClock();
        setInterval(updateClock, 1000);
    </script>
</body>
</html>
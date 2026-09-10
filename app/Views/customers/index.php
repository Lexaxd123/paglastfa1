<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Customers | Morris POS</title>

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
            <li><a href="<?= site_url('about') ?>">About</a></li>

            <li>
                <a class="active" href="<?= site_url('customers') ?>">
                    Customers
                </a>
            </li>

            <li><a href="<?= site_url('users') ?>">Users</a></li>
        </ul>
    </nav>

    <main class="container">
        <h1 class="page-title">👥 Customer List</h1>

        <p class="page-description">
            View customer names, email addresses, and phone numbers.
        </p>

        <?php if (! empty($customers)): ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer Name</th>
                            <th>Email Address</th>
                            <th>Phone Number</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($customers as $number => $customer): ?>
                            <tr>
                                <td><?= $number + 1 ?></td>

                                <td>
                                    <?= esc($customer['name']) ?>
                                </td>

                                <td>
                                    <a
                                        class="email-link"
                                        href="mailto:<?= esc($customer['email']) ?>"
                                    >
                                        <?= esc($customer['email']) ?>
                                    </a>
                                </td>

                                <td>
                                    <?= esc($customer['phone']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="empty-message">
                No customers are currently available.
            </p>
        <?php endif; ?>
    </main>

    <footer>
        &copy; <?= date('Y') ?> Morris POS System
    </footer>
</body>
</html>
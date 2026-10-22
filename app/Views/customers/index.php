<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Customers | Morris POS</title>

    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>

<body>

<nav class="navbar">
    <a class="logo" href="<?= site_url('/') ?>">
        🛒 Morris POS
    </a>

    <ul class="nav-links">
        <li>
            <a href="<?= site_url('/') ?>">Home</a>
        </li>

        <li>
            <a href="<?= site_url('about') ?>">About</a>
        </li>

        <li>
            <a class="active" href="<?= site_url('customers') ?>">
                Customers
            </a>
        </li>

        <li>
            <a href="<?= site_url('users') ?>">Users</a>
        </li>
    </ul>
</nav>

<main class="container">

    <div class="page-header">
        <div>
            <h1 class="page-title">👥 Customer List</h1>

            <p class="page-description">
                View and manage customer names, email addresses, and phone numbers.
            </p>
        </div>

        <a class="button" href="<?= site_url('customers/new') ?>">
            ➕ Add Customer
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="success-message">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (! empty($customers)): ?>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer Name</th>
                        <th>Email Address</th>
                        <th>Phone Number</th>
                        <th>Date Added</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($customers as $number => $customer): ?>

                        <?php
                            $customerName = $customer['full_name']
                                ?? $customer['name']
                                ?? '';

                            $phoneNumber = $customer['phone']
                                ?? $customer['phone_number']
                                ?? '';
                        ?>

                        <tr>
                            <td>
                                <?= $number + 1 ?>
                            </td>

                            <td>
                                <?= esc($customerName) ?>
                            </td>

                            <td>
                                <a
                                    class="email-link"
                                    href="mailto:<?= esc($customer['email'] ?? '') ?>"
                                >
                                    <?= esc($customer['email'] ?? '') ?>
                                </a>
                            </td>

                            <td>
                                <?= esc($phoneNumber) ?>
                            </td>

                            <td>
                                <?= esc($customer['date_added'] ?? 'Not available') ?>
                            </td>

                            <td>
                                <a
                                    class="edit-button"
                                    href="<?= site_url('customers/edit/' . $customer['customer_id']) ?>"
                                >
                                    ✏️ Edit
                                </a>
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
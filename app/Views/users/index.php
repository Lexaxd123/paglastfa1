<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Users | Morris POS</title>

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
                <a href="<?= site_url('customers') ?>">Customers</a>
            </li>

            <li>
                <a class="active" href="<?= site_url('users') ?>">
                    Users
                </a>
            </li>
        </ul>
    </nav>

    <main class="container">
        <h1 class="page-title">👤 User List</h1>

        <p class="page-description">
            View registered user accounts, their usernames, and when they were created.
        </p>

        <?php if (! empty($users)): ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Date Created</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($users as $number => $user): ?>
                            <tr>
                                <td><?= $number + 1 ?></td>

                                <td>
                                    <span class="role">
                                        <?= esc($user['username']) ?>
                                    </span>
                                </td>

                                <td>
                                    <?= esc($user['full_name']) ?>
                                </td>

                                <td>
                                    <?= esc(date('M d, Y g:i A', strtotime($user['created_at']))) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="empty-message">
                No users are currently available.
            </p>
        <?php endif; ?>
    </main>

    <footer>
        &copy; <?= date('Y') ?> Morris POS System
    </footer>
</body>
</html>
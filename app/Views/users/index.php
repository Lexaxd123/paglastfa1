<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Users | Morris POS</title>

    <link rel="stylesheet" href="<?= base_url('css/style.css') . '?v=' . filemtime(FCPATH . 'css/style.css') ?>">
</head>

<body>
    <?= view('partials/navbar', ['active' => 'users']) ?>

    <main class="container">
        <div class="page-header">
            <div>
                <h1 class="page-title">👤 User List</h1>

                <p class="page-description">
                    View and manage user accounts, their profile pictures, and when they were created.
                </p>
            </div>

            <a class="button" href="<?= site_url('users/new') ?>">➕ Add User</a>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="success-message">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <?php if (! empty($users)): ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Avatar</th>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Date Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($users as $number => $user): ?>
                            <?php
                            // Prepared avatar if there is one, otherwise the placeholder.
                            $avatarUrl = ! empty($user['avatar'])
                                ? base_url('uploads/avatars/' . rawurlencode($user['avatar']))
                                : base_url('images/avatar-placeholder.svg');
                            ?>
                            <tr>
                                <td><?= $number + 1 ?></td>

                                <td>
                                    <img class="avatar" src="<?= esc($avatarUrl, 'attr') ?>"
                                         alt="<?= esc($user['username'], 'attr') ?>'s avatar"
                                         width="48" height="48" loading="lazy">
                                </td>

                                <td><span class="role"><?= esc($user['username']) ?></span></td>

                                <td><?= esc($user['full_name']) ?></td>

                                <td><?= esc(date('M d, Y g:i A', strtotime($user['created_at']))) ?></td>

                                <td>
                                    <a class="edit-button" href="<?= site_url('users/edit/' . $user['id']) ?>">
                                        ✏️ Edit
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="empty-message">No users are currently available.</p>
        <?php endif; ?>
    </main>

    <footer>
        &copy; <?= date('Y') ?> Morris POS System
    </footer>
</body>
</html>

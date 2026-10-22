<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Customers | Morris POS</title>

    <link rel="stylesheet" href="<?= base_url('css/style.css') . '?v=' . filemtime(FCPATH . 'css/style.css') ?>">
</head>

<body>
    <?= view('partials/navbar', ['active' => 'customers']) ?>

    <main class="container">
        <div class="page-header">
            <div>
                <h1 class="page-title">👥 Customer List</h1>

                <p class="page-description">
                    View and manage customer names, email addresses, and phone numbers.
                </p>
            </div>

            <a class="button" href="<?= site_url('customers/new') ?>">➕ Add Customer</a>
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
                            <tr>
                                <td><?= $number + 1 ?></td>

                                <td><?= esc($customer['full_name']) ?></td>

                                <td>
                                    <a class="email-link" href="mailto:<?= esc($customer['email'], 'attr') ?>">
                                        <?= esc($customer['email']) ?>
                                    </a>
                                </td>

                                <td><?= esc($customer['phone'] ?: '—') ?></td>

                                <td><?= esc(date('M d, Y', strtotime($customer['created_at']))) ?></td>

                                <td>
                                    <a class="edit-button" href="<?= site_url('customers/edit/' . $customer['id']) ?>">
                                        ✏️ Edit
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="empty-message">No customers are currently available.</p>
        <?php endif; ?>
    </main>

    <footer>
        &copy; <?= date('Y') ?> Morris POS System
    </footer>
</body>
</html>

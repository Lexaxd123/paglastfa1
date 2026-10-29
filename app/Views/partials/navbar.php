<?php
/**
 * Shared navigation bar.
 * Usage: <?= view('partials/navbar', ['active' => 'customers']) ?>
 */
$active ??= '';

$links = [
    'home'      => ['Home', site_url('/')],
    'about'     => ['About', site_url('about')],
    'customers' => ['Customers', site_url('customers')],
    'users'     => ['Users', site_url('users')],
];
?>
<nav class="navbar">
    <a class="logo" href="<?= site_url('/') ?>">🛒 Morris POS</a>

    <ul class="nav-links">
        <?php foreach ($links as $key => [$label, $url]): ?>
            <li>
                <a <?= $key === $active ? 'class="active"' : '' ?> href="<?= $url ?>"><?= esc($label) ?></a>
            </li>
        <?php endforeach; ?>

        <?= view('partials/auth_nav', ['active' => $active]) ?>
    </ul>
</nav>

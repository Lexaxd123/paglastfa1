<?php
/**
 * TFA4: the Log In / Log Out item of the navigation bar.
 * Logged in  -> "👤 Full Name" + a Log Out button (a small POST form with a CSRF token)
 * Logged out -> a Log In link
 */
$loggedIn = session()->get('isLoggedIn') === true;
?>
<?php if ($loggedIn): ?>
    <li class="nav-user"><span>👤 <?= esc(session()->get('full_name')) ?></span></li>
    <li>
        <form class="nav-logout" method="post" action="<?= site_url('logout') ?>">
            <?= csrf_field() ?>
            <button type="submit">Log Out</button>
        </form>
    </li>
<?php else: ?>
    <li>
        <a <?= ($active ?? '') === 'login' ? 'class="active"' : '' ?> href="<?= site_url('login') ?>">Log In</a>
    </li>
<?php endif; ?>

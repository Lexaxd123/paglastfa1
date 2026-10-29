<?php
/**
 * Login form.
 *
 * @var string $username  what was typed before (the password is never sent back)
 * @var array  $errors    field => message ('login' = bad credentials / throttled)
 * @var bool   $loggedOut true right after a logout
 */
$flashError   = session()->getFlashdata('error');
$flashSuccess = session()->getFlashdata('success');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Log In | Morris POS</title>

    <link rel="stylesheet" href="<?= base_url('css/style.css') . '?v=' . filemtime(FCPATH . 'css/style.css') ?>">
</head>

<body>
    <?= view('partials/navbar', ['active' => 'login']) ?>

    <main class="container form-container login-container">
        <h1 class="page-title">🔐 Staff Login</h1>

        <p class="page-description">Log in to manage customer and user accounts.</p>

        <?php if (! empty($loggedOut)): ?>
            <div class="success-message">You have been logged out.</div>
        <?php endif; ?>

        <?php if ($flashSuccess): ?>
            <div class="success-message"><?= esc($flashSuccess) ?></div>
        <?php endif; ?>

        <?php if ($flashError): ?>
            <div class="error-summary" role="alert"><?= esc($flashError) ?></div>
        <?php endif; ?>

        <?php if (isset($errors['login'])): ?>
            <div class="error-summary" role="alert"><?= esc($errors['login']) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('login') ?>" novalidate>
            <?= csrf_field() ?>

            <div class="form-group <?= isset($errors['username']) ? 'has-error' : '' ?>">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" maxlength="50"
                       autocomplete="username" autofocus
                       value="<?= esc($username, 'attr') ?>">
                <?php if (isset($errors['username'])): ?>
                    <p class="field-error"><?= esc($errors['username']) ?></p>
                <?php endif; ?>
            </div>

            <div class="form-group <?= isset($errors['password']) ? 'has-error' : '' ?>">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" maxlength="255"
                       autocomplete="current-password">
                <?php if (isset($errors['password'])): ?>
                    <p class="field-error"><?= esc($errors['password']) ?></p>
                <?php endif; ?>
            </div>

            <div class="form-actions">
                <button type="submit" class="button">🔓 Log In</button>
            </div>
        </form>
    </main>

    <footer>
        &copy; <?= date('Y') ?> Morris POS System
    </footer>
</body>
</html>

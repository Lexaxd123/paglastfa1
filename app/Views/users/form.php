<?php
/**
 * Shared New / Edit user form.
 * The profile-picture upload only appears on the Edit form.
 *
 * @var string $title
 * @var array  $user    Existing record (edit) or the values the user typed (redisplay)
 * @var array  $errors  field => message
 */
$isEdit = isset($user['id']);
$action = $isEdit
    ? site_url('users/update/' . $user['id'])
    : site_url('users/create');

$fieldError = static fn (string $field) => isset($errors[$field])
    ? '<p class="field-error">' . esc($errors[$field]) . '</p>'
    : '';

$avatarUrl = ! empty($user['avatar'])
    ? base_url('uploads/avatars/' . rawurlencode($user['avatar']))
    : base_url('images/avatar-placeholder.svg');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= esc($title) ?> | Morris POS</title>

    <link rel="stylesheet" href="<?= base_url('css/style.css') . '?v=' . filemtime(FCPATH . 'css/style.css') ?>">
</head>

<body>
    <?= view('partials/navbar', ['active' => 'users']) ?>

    <main class="container form-container">
        <a class="back-link" href="<?= site_url('users') ?>">← Back to Users</a>

        <h1 class="page-title"><?= esc($title) ?></h1>

        <p class="page-description">Fields marked with <span class="required">*</span> are required.</p>

        <?php if (! empty($errors)): ?>
            <div class="error-summary" role="alert">
                Please fix the highlighted field<?= count($errors) > 1 ? 's' : '' ?> below.
            </div>
        <?php endif; ?>

        <!-- enctype is required for the file upload to be sent -->
        <form method="post" action="<?= $action ?>" enctype="multipart/form-data" novalidate>
            <?= csrf_field() ?>

            <div class="form-group <?= isset($errors['username']) ? 'has-error' : '' ?>">
                <label for="username">Username <span class="required">*</span></label>
                <input type="text" id="username" name="username" maxlength="50" autocomplete="off"
                       value="<?= esc($user['username'] ?? '', 'attr') ?>">
                <?= $fieldError('username') ?>
                <p class="field-hint">Letters, numbers, dots, dashes, and underscores only. Must be unique.</p>
            </div>

            <div class="form-group <?= isset($errors['full_name']) ? 'has-error' : '' ?>">
                <label for="full_name">Full Name <span class="required">*</span></label>
                <input type="text" id="full_name" name="full_name" maxlength="100"
                       value="<?= esc($user['full_name'] ?? '', 'attr') ?>">
                <?= $fieldError('full_name') ?>
            </div>

            <div class="form-group <?= isset($errors['password']) ? 'has-error' : '' ?>">
                <label for="password">
                    <?= $isEdit ? 'New Password' : 'Password' ?>
                    <?php if (! $isEdit): ?><span class="required">*</span><?php endif; ?>
                </label>
                <input type="password" id="password" name="password" maxlength="72" autocomplete="new-password">
                <?= $fieldError('password') ?>
                <p class="field-hint">
                    <?= $isEdit ? 'Leave empty to keep the current password. ' : '' ?>8 to 72 characters. It is stored as a hash, never as plain text.
                </p>
            </div>

            <div class="form-group <?= isset($errors['password_confirm']) ? 'has-error' : '' ?>">
                <label for="password_confirm">
                    Confirm Password
                    <?php if (! $isEdit): ?><span class="required">*</span><?php endif; ?>
                </label>
                <input type="password" id="password_confirm" name="password_confirm" maxlength="72" autocomplete="new-password">
                <?= $fieldError('password_confirm') ?>
            </div>

            <?php if ($isEdit): ?>
                <div class="form-group <?= isset($errors['avatar']) ? 'has-error' : '' ?>">
                    <label for="avatar">Profile Picture</label>

                    <div class="avatar-current">
                        <img class="avatar avatar-large" src="<?= esc($avatarUrl, 'attr') ?>"
                             alt="Current profile picture" width="96" height="96">
                        <span class="field-hint">
                            <?= empty($user['avatar']) ? 'No picture uploaded yet.' : 'Current picture.' ?>
                        </span>
                    </div>

                    <input type="file" id="avatar" name="avatar" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                    <?= $fieldError('avatar') ?>
                    <p class="field-hint">
                        Optional. JPG or PNG, up to 2MB. It will be cropped to a 200 × 200 square.
                        Leave empty to keep the current picture.
                    </p>
                </div>
            <?php endif; ?>

            <div class="form-actions">
                <button type="submit" class="button">
                    <?= $isEdit ? '💾 Update User' : '➕ Save User' ?>
                </button>
                <a class="button-secondary" href="<?= site_url('users') ?>">Cancel</a>
            </div>
        </form>
    </main>

    <footer>
        &copy; <?= date('Y') ?> Morris POS System
    </footer>
</body>
</html>

<?php
/**
 * Shared New / Edit customer form.
 *
 * @var string $title
 * @var array  $customer  Existing record (edit) or the values the user typed (redisplay)
 * @var array  $errors    field => message
 */
$isEdit = isset($customer['id']);
$action = $isEdit
    ? site_url('customers/update/' . $customer['id'])
    : site_url('customers/create');

// Small helper: prints the error under a field, if there is one.
$fieldError = static fn (string $field) => isset($errors[$field])
    ? '<p class="field-error">' . esc($errors[$field]) . '</p>'
    : '';
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
    <?= view('partials/navbar', ['active' => 'customers']) ?>

    <main class="container form-container">
        <a class="back-link" href="<?= site_url('customers') ?>">← Back to Customers</a>

        <h1 class="page-title"><?= esc($title) ?></h1>

        <p class="page-description">Fields marked with <span class="required">*</span> are required.</p>

        <?php if (! empty($errors)): ?>
            <div class="error-summary" role="alert">
                Please fix the highlighted field<?= count($errors) > 1 ? 's' : '' ?> below.
            </div>
        <?php endif; ?>

        <form method="post" action="<?= $action ?>" novalidate>
            <?= csrf_field() ?>

            <div class="form-group <?= isset($errors['full_name']) ? 'has-error' : '' ?>">
                <label for="full_name">Full Name <span class="required">*</span></label>
                <input type="text" id="full_name" name="full_name" maxlength="100"
                       value="<?= esc($customer['full_name'] ?? '', 'attr') ?>">
                <?= $fieldError('full_name') ?>
            </div>

            <div class="form-group <?= isset($errors['email']) ? 'has-error' : '' ?>">
                <label for="email">Email Address <span class="required">*</span></label>
                <input type="email" id="email" name="email" maxlength="100"
                       value="<?= esc($customer['email'] ?? '', 'attr') ?>">
                <?= $fieldError('email') ?>
            </div>

            <div class="form-group <?= isset($errors['phone']) ? 'has-error' : '' ?>">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" maxlength="20"
                       value="<?= esc($customer['phone'] ?? '', 'attr') ?>">
                <?= $fieldError('phone') ?>
            </div>

            <div class="form-actions">
                <button type="submit" class="button">
                    <?= $isEdit ? '💾 Update Customer' : '➕ Save Customer' ?>
                </button>
                <a class="button-secondary" href="<?= site_url('customers') ?>">Cancel</a>
            </div>
        </form>
    </main>

    <footer>
        &copy; <?= date('Y') ?> Morris POS System
    </footer>
</body>
</html>

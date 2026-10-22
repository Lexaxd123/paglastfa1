<!DOCTYPE html>
<html>
<head>
    <title><?= esc($title) ?></title>
</head>
<body>

<h1><?= esc($title) ?></h1>

<?php if (isset($validation)): ?>
    <?= $validation->listErrors() ?>
<?php endif; ?>

<a href="<?= site_url('customers') ?>">Back to Customers</a>

<form method="post"
      action="<?= isset($customer['customer_id'])
          ? site_url('customers/update/' . $customer['customer_id'])
          : site_url('customers/create') ?>">

    <?= csrf_field() ?>

    <p>
        <label>Full Name</label><br>
        <input type="text"
               name="name"
               value="<?= esc($customer['name'] ?? '') ?>">
    </p>

    <p>
        <label>Email</label><br>
        <input type="email"
               name="email"
               value="<?= esc($customer['email'] ?? '') ?>">
    </p>

    <p>
        <label>Phone Number</label><br>
        <input type="text"
               name="phone_number"
               value="<?= esc($customer['phone_number'] ?? '') ?>">
    </p>

    <button type="submit">
        <?= isset($customer['customer_id']) ? 'Update Customer' : 'Save Customer' ?>
    </button>
</form>

</body>
</html>
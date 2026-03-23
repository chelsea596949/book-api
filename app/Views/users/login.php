<h2><?= esc($title) ?></h2>

<?= session()->getFlashdata('error') ?>
<?= validation_list_errors() ?>

<form action="/api/login" method="post">
    <?= csrf_field() ?>

    <label for="uid">user_name</label>
    <input type="input" name="uid" value="<?= set_value('uid') ?>" required>
    <br>

    <label for="password">Password</label>
    <input type="password" name="password" required>
    <br>

    <input type="submit" name="submit" value="Login">
</form>
<h2><?= esc($title) ?></h2>

<?= session()->getFlashdata('error') ?>
<?= validation_list_errors() ?>

<form action="/api/books" method="post">
    <?= csrf_field() ?>

    <label for="title">Title</label>
    <input type="input" name="title" value="<?= set_value('title') ?>" required>
    <br>

    <label for="author_name">Author Name</label>
    <input type="text" name="author_name" value="<?= set_value('author_name') ?>" required>
    <br>

    <label for="price">Price</label>
    <input type="number" name="price" value="<?= set_value('price') ?>" min="0" step="0.01">
    <br>

    <label for="year">Year</label>
    <input type="number" name="year" value="<?= set_value('year') ?>" min="1" max="<?= date('Y') ?>" step="1">
    <br>

    <input type="submit" name="submit" value="Create books item">
</form>
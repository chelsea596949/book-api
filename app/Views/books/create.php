<?= session()->getFlashdata('error') ?>
<?= validation_list_errors() ?>

<div class="d-flex flex-column align-items-center justify-content-center vh-100"">
    <h1 class="display-4 fw-bold text-white mb-4"><?= esc($title) ?></h1>

    <form action="/api/books" method="post" enctype="multipart/form-data" style="width: 100%; max-width: 400px;" class="text-white">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="<?= set_value('title') ?>" required>
        </div>

        <div class="mb-3">
            <label for="author_name" class="form-label">Author Name</label>
            <input type="text" name="author_name" id="author_name" class="form-control" value="<?= set_value('author_name') ?>" required>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" name="price" id="price" class="form-control" value="<?= set_value('price') ?>" min="0" step="0.01">
        </div>

        <div class="mb-3">
            <label for="year" class="form-label">Year</label>
            <input type="number" name="year" id="year" class="form-control" value="<?= set_value('year') ?>" min="1" max="<?= date('Y') ?>" step="1">
        </div>

        <div class="mb-3">
            <label for="book_image" class="form-label">Book Image</label>
            <input type="file" name="book_image" id="book_image" class="form-control" accept="image/jpeg, image/png">
        </div>

        <div class="d-grid gap-2">
            <input type="submit" name="submit" class="btn btn-primary btn-lg" value="Create book item">
        </div>
    </form>
</div>
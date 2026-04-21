<div class="d-flex flex-column justify-content-center align-items-center vh-100 w-100">
  
  <div class="w-75 mx-auto">
    <div class="d-flex justify-content-between align-items-end mb-3">
      <h1 class="display-4 fw-bold text-white mb-0"><?= esc($title) ?></h1>
      <button type="button" class="btn btn-primary btn-lg shadow-sm" data-bs-toggle="modal" data-bs-target="#addBookModal">
        <i class="bi bi-plus-lg"></i> Add New Book
    </button>
    </div>

    <div class="table-responsive" style="max-height: 60vh; overflow-y: auto; background-color: #212529; border: 1px solid #444;"> 
      <table class="table table-dark table-hover text-center mb-0">
        <thead class="sticky-top bg-dark" style="z-index: 1;">
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Title</th>
            <th scope="col">Image</th>
            <th scope="col">Author</th>
            <th scope="col">Year</th>
            <th scope="col">Create Time</th>
            <th scope="col">Update Time</th>
            <th scope="col">Slug</th>
            <th scope="col">Price</th>
          </tr>
        </thead>
        <tbody id="book-table-body">
          </tbody>
      </table>
    </div>
    <nav aria-label="Page navigation" class="mt-4">
      <ul class="pagination justify-content-center" id="pagination-container">
      </ul>
  </nav>

  </div>
</div>

<div class="modal fade" id="addBookModal" tabindex="-1" aria-labelledby="addBookModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered"> <div class="modal-content bg-dark text-white border-secondary">
      
      <div class="modal-header border-secondary">
        <h5 class="modal-title fs-3 fw-bold" id="addBookModalLabel">Add New Book</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger" id="error-message"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        
        <form action="/api/books" method="post" enctype="multipart/form-data" id="add-book-form">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-control bg-secondary text-white border-0" value="<?= set_value('title') ?>" required>
            </div>

            <div class="mb-3">
                <label for="author_name" class="form-label">Author Name</label>
                <input type="text" name="author_name" id="author_name" class="form-control bg-secondary text-white border-0" value="<?= set_value('author_name') ?>" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" name="price" id="price" class="form-control bg-secondary text-white border-0" value="<?= set_value('price') ?>" min="0" step="0.01" placeholder="0.00">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="year" class="form-label">Year</label>
                    <input type="number" name="year" id="year" class="form-control bg-secondary text-white border-0" value="<?= set_value('year') ?>" min="1" max="<?= date('Y') ?>" placeholder="<?= date('Y') ?>">
                </div>
            </div>

            <div class="mb-3">
                <label for="book_image" class="form-label">Book Image</label>
                <input type="file" name="book_image" id="book_image" class="form-control bg-secondary text-white border-0" accept="image/jpeg, image/png">
            </div>

            <div class="modal-footer border-0 px-0 pb-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary px-4" id="create-book-btn">
                  <span id="create-book-btn-spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                  Create Book
                </button>
            </div>
        </form>
      </div>

    </div>
  </div>
</div>
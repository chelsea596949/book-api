<div class="d-flex flex-column justify-content-center align-items-center vh-100 w-100">
  
  <div class="w-75 mx-auto">
    <div class="d-flex justify-content-between align-items-end mb-3">
      <h1 class="display-4 fw-bold text-white mb-0"><?= esc($title) ?></h1>
      <button type="button" class="btn btn-primary btn-lg shadow-sm" data-bs-toggle="modal" data-bs-target="#addBookModal">
        <i class="bi bi-plus-lg"></i> Add New Book
    </button>
    </div>

    <!-- 搜尋表單 -->
    <div class="card bg-dark border-secondary mb-4">
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-4">
            <label for="search-title" class="form-label text-white">Search by Title</label>
            <input type="text" class="form-control bg-secondary text-white border-0" id="search-title" placeholder="Fuzzy search...">
          </div>
          <div class="col-md-4">
            <label for="search-author" class="form-label text-white">Search by Author</label>
            <input type="text" class="form-control bg-secondary text-white border-0" id="search-author" placeholder="Exact match...">
          </div>
          <div class="col-md-3">
            <label for="search-year" class="form-label text-white">Filter by Year</label>
            <select class="form-select bg-secondary text-white border-0" id="search-year">
              <option value="">All Years</option>
            </select>
          </div>
          <div class="col-md-1 d-flex align-items-end">
            <button type="button" class="btn btn-outline-info w-100" id="search-btn">
              <i class="bi bi-search"></i> Search
            </button>
          </div>
        </div>
        <div class="row mt-2">
          <div class="col-12">
            <button type="button" class="btn btn-outline-secondary btn-sm" id="reset-search-btn">
              <i class="bi bi-arrow-clockwise"></i> Reset
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="table-responsive" style="max-height: 60vh; overflow-y: auto; background-color: #212529; border: 1px solid #444;"> 
      <table class="table table-dark table-hover text-center mb-0">
        <thead class="sticky-top bg-dark" style="z-index: 1;">
          <tr>
            <th scope="col">ID</th>
            <th scope="col" class="sortable-column" data-column="title" style="cursor: pointer; user-select: none;">
              Title <span class="sort-indicator ms-1">⇅</span>
            </th>
            <th scope="col">Image</th>
            <th scope="col" class="sortable-column" data-column="author_name" style="cursor: pointer; user-select: none;">
              Author <span class="sort-indicator ms-1">⇅</span>
            </th>
            <th scope="col" class="sortable-column" data-column="year" style="cursor: pointer; user-select: none;">
              Year <span class="sort-indicator ms-1">⇅</span>
            </th>
            <th scope="col" class="sortable-column" data-column="created_at" style="cursor: pointer; user-select: none;">
              Create Time <span class="sort-indicator ms-1">⇅</span>
            </th>
            <th scope="col">Update Time</th>
            <th scope="col">Slug</th>
            <th scope="col" class="sortable-column" data-column="price" style="cursor: pointer; user-select: none;">
              Price <span class="sort-indicator ms-1">⇅</span>
            </th>
            <th scope="col">Actions</th>
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
            <div class="alert alert-danger" id="add-book-error-message"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        
        <form action="/api/books" method="post" enctype="multipart/form-data" id="add-book-form">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" class="form-control bg-secondary text-white border-0" value="<?= set_value('title') ?>" required>
            </div>

            <div class="mb-3">
                <label for="author_name" class="form-label">Author Name</label>
                <input type="text" name="author_name" class="form-control bg-secondary text-white border-0" value="<?= set_value('author_name') ?>" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" name="price" class="form-control bg-secondary text-white border-0" value="<?= set_value('price') ?>" min="0" step="0.01" placeholder="0.00">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="year" class="form-label">Year</label>
                    <input type="number" name="year" class="form-control bg-secondary text-white border-0" value="<?= set_value('year') ?>" min="1" max="<?= date('Y') ?>" placeholder="<?= date('Y') ?>">
                </div>
            </div>

            <div class="mb-3">
                <label for="book_image" class="form-label">Book Image</label>
                <input type="file" name="book_image" class="form-control bg-secondary text-white border-0" accept="image/jpeg, image/png">
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

<div class="modal fade" id="editBookModal" tabindex="-1" aria-labelledby="editBookModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered"> <div class="modal-content bg-dark text-white border-secondary">
      
      <div class="modal-header border-secondary">
        <h5 class="modal-title fs-3 fw-bold" id="editBookModalLabel">Edit Book</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger" id="edit-book-error-message"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        
        <form action="/api/books" method="put" enctype="multipart/form-data" id="edit-book-form">
            <?= csrf_field() ?>

            <input type="text" name="id" class="form-control bg-secondary text-white border-0" value="" required hidden="true">

            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" class="form-control bg-secondary text-white border-0" value="" required>
            </div>

            <div class="mb-3">
                <label for="author_name" class="form-label">Author Name</label>
                <input type="text" name="author_name" class="form-control bg-secondary text-white border-0" value="" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" name="price" class="form-control bg-secondary text-white border-0" value="" min="0" step="0.01" placeholder="0.00">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="year" class="form-label">Year</label>
                    <input type="number" name="year" class="form-control bg-secondary text-white border-0" value="" min="1" max="<?= date('Y') ?>" placeholder="<?= date('Y') ?>">
                </div>
            </div>

            <div class="modal-footer border-0 px-0 pb-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary px-4" id="edit-book-btn">
                  <span id="edit-book-btn-spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                  Edit Book
                </button>
            </div>
        </form>
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="editImageModal" tabindex="-1" aria-labelledby="editImageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg"> <div class="modal-content bg-dark text-white border-secondary">
      
      <div class="modal-header border-secondary">
        <h5 class="modal-title fs-3 fw-bold" id="editImageModalLabel">Edit Book Image - <span class="book-title-label"></span></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger" id="edit-image-error-message"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
         
        <form action="/api/books" method="put" enctype="multipart/form-data" id="edit-image-form">
            <?= csrf_field() ?>

            <input type="text" name="id" class="form-control bg-secondary text-white border-0" value="" required hidden="true">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Current Image</label>
                    <img id="current-image-preview" src="" class="w-100 rounded" style="height: 300px; object-fit: contain; background: #333;">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">New Image Preview</label>
                    <div id="new-image-container" style="height: 300px; background: #333; border-radius: 0.375rem; display: flex; align-items: center; justify-content: center;">
                        <img id="new-image-preview" src="" class="w-100 rounded d-none" style="height: 300px; object-fit: contain;">
                        <span class="no-image-message text-muted">Select an image to preview</span>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="book_image" class="form-label">Choose New Image</label>
                <input type="file" name="book_image" class="form-control bg-secondary text-white border-0" accept="image/jpeg, image/png" required>
            </div>

            <div class="modal-footer border-0 px-0 pb-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-warning px-4" id="edit-image-btn">
                  <span id="edit-image-btn-spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                  Update Image
                </button>
            </div>
        </form>
      </div>

    </div>
  </div>
</div>
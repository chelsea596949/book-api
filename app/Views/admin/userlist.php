<div class="d-flex flex-column justify-content-center align-items-center vh-100 w-100">

  <div class="w-75 mx-auto">
    <div class="d-flex justify-content-between align-items-end mb-3">
      <h1 class="display-4 fw-bold text-white mb-0"><?= esc($title) ?></h1>
    </div>

    <div class="card bg-dark border-secondary mb-4">
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-11">
            <label for="search-uid" class="form-label text-white">Search by Account</label>
            <input type="text" class="form-control bg-secondary text-white border-0" id="search-uid" placeholder="Fuzzy search...">
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
            <th scope="col">UID</th>
            <th scope="col">Name</th>
            <th scope="col">Create Time</th>
            <th scope="col">Update Time</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody id="user-table-body">
        </tbody>
      </table>
    </div>
    <nav aria-label="Page navigation" class="mt-4">
      <ul class="pagination justify-content-center" id="pagination-container">
      </ul>
    </nav>

  </div>
</div>

<div class="container-fluid py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-5 fw-bold mb-3"><?= isset($title) ? esc($title) : 'Books Collection' ?></h1>
            
            <!-- View Toggle & Filters -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <button class="btn btn-outline-primary" id="gridViewBtn" title="Grid View">
                        <i class="bi bi-grid-3x3-gap"></i> Grid
                    </button>
                    <button class="btn btn-outline-secondary" id="listViewBtn" title="List View">
                        <i class="bi bi-list-ul"></i> List
                    </button>
                </div>
                <div>
                    <span class="text-muted">Showing <span id="bookCount">0</span> books</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Pagination -->
    <nav aria-label="Page navigation top" class="mb-4" id="paginationTopContainer" style="display: none;">
        <ul class="pagination justify-content-center">
            <li class="page-item" id="prevPageTopItem">
                <button class="page-link" id="prevPageTopBtn">Previous</button>
            </li>
            <li class="page-item active">
                <span class="page-link">
                    Page <span id="currentPageTop">1</span> of <span id="totalPagesTop">1</span>
                </span>
            </li>
            <li class="page-item" id="nextPageTopItem">
                <button class="page-link" id="nextPageTopBtn">Next</button>
            </li>
        </ul>
        
        <!-- Page number buttons -->
        <div class="d-flex justify-content-center flex-wrap gap-2" id="pageNumbersTopContainer">
            <!-- Page numbers will be added here -->
        </div>
    </nav>

    <!-- Loading Indicator -->
    <div id="loadingIndicator" class="text-center py-5" style="display: none;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3 text-muted">Loading books...</p>
    </div>

    <!-- Error Message -->
    <div id="errorMessage" class="alert alert-danger" role="alert" style="display: none;"></div>

    <!-- Grid View -->
    <div id="gridContainer" class="row row-cols-1 row-cols-md-2 row-cols-lg-4 row-cols-xl-5 g-4">
        <!-- Books will be loaded here -->
    </div>

    <!-- List View -->
    <div id="listContainer" class="list-view" style="display: none;">
        <!-- Books will be loaded here -->
    </div>

    <!-- Bottom Pagination -->
    <nav aria-label="Page navigation bottom" class="mt-5 pt-4 border-top" id="paginationBottomContainer" style="display: none;">
        <ul class="pagination justify-content-center">
            <li class="page-item" id="prevPageBottomItem">
                <button class="page-link" id="prevPageBottomBtn">Previous</button>
            </li>
            <li class="page-item active">
                <span class="page-link">
                    Page <span id="currentPageBottom">1</span> of <span id="totalPagesBottom">1</span>
                </span>
            </li>
            <li class="page-item" id="nextPageBottomItem">
                <button class="page-link" id="nextPageBottomBtn">Next</button>
            </li>
        </ul>
        
        <!-- Page number buttons -->
        <div class="d-flex justify-content-center flex-wrap gap-2 mt-3" id="pageNumbersBottomContainer">
            <!-- Page numbers will be added here -->
        </div>
    </nav>

    <!-- No Results -->
    <div id="noResults" class="alert alert-info text-center py-5" style="display: none;">
        <p class="mb-0">No books found. Please try again later.</p>
    </div>
</div>

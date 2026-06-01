<div class="container-fluid py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-5 fw-bold mb-3"><?= isset($title) ? esc($title) : 'Books Collection' ?></h1>
            
            <!-- Search Bar -->
            <div class="row mb-4">
                <div class="col-12 col-lg-10">
                    <div class="row g-2">
                        <!-- Search by Title -->
                        <div class="col-12 col-md-4">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-book"></i> Title
                                </span>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="searchTitleInput" 
                                    placeholder="Search book title..."
                                    autocomplete="off"
                                >
                            </div>
                        </div>
                        
                        <!-- Search by Author -->
                        <div class="col-12 col-md-4">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person"></i> Author
                                </span>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="searchAuthorInput" 
                                    placeholder="Search author name..."
                                    autocomplete="off"
                                >
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="col-12 col-md-4">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary flex-grow-1" id="searchBtn" type="button">
                                    <i class="bi bi-search"></i> Search
                                </button>
                                <button class="btn btn-secondary flex-grow-1" id="clearSearchBtn" type="button" title="Clear all">
                                    <i class="bi bi-x"></i> Clear
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
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
    <nav aria-label="Page navigation" class="mb-4" id="paginationTop" style="display: none;">
        <div class="d-flex justify-content-center align-items-center flex-wrap gap-2 mb-3">
            <button class="btn btn-sm btn-outline-primary" id="prevBtnTop">← Previous</button>
            <span class="text-muted"><span id="pageInfoTop">Page 1 of 1</span></span>
            <button class="btn btn-sm btn-outline-primary" id="nextBtnTop">Next →</button>
        </div>
        <div class="d-flex justify-content-center flex-wrap gap-2" id="pageButtonsTop"></div>
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
    <nav aria-label="Page navigation" class="mt-5 pt-4 border-top" id="paginationBottom" style="display: none;">
        <div class="d-flex justify-content-center align-items-center flex-wrap gap-2 mb-3">
            <button class="btn btn-sm btn-outline-primary" id="prevBtnBottom">← Previous</button>
            <span class="text-muted"><span id="pageInfoBottom">Page 1 of 1</span></span>
            <button class="btn btn-sm btn-outline-primary" id="nextBtnBottom">Next →</button>
        </div>
        <div class="d-flex justify-content-center flex-wrap gap-2" id="pageButtonsBottom"></div>
    </nav>

    <!-- No Results -->
    <div id="noResults" class="alert alert-info text-center py-5" style="display: none;">
        <p class="mb-0">No books found. Please try again later.</p>
    </div>
</div>

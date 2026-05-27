const BookDisplay = {
    currentPage: 1,
    perPage: 10,
    totalBooks: 0,
    totalPages: 1,
    currentView: 'grid', // 'grid' or 'list'
    books: [],

    init: function() {
        this.setupEventListeners();
        this.loadBooks(1);
    },

    setupEventListeners: function() {
        // View toggle buttons
        const gridViewBtn = document.getElementById('gridViewBtn');
        const listViewBtn = document.getElementById('listViewBtn');
        
        if (gridViewBtn) gridViewBtn.addEventListener('click', () => this.switchView('grid'));
        if (listViewBtn) listViewBtn.addEventListener('click', () => this.switchView('list'));

        // Top pagination buttons
        const prevBtnTop = document.getElementById('prevBtnTop');
        const nextBtnTop = document.getElementById('nextBtnTop');
        
        if (prevBtnTop) prevBtnTop.addEventListener('click', () => this.previousPage());
        if (nextBtnTop) nextBtnTop.addEventListener('click', () => this.nextPage());

        // Bottom pagination buttons
        const prevBtnBottom = document.getElementById('prevBtnBottom');
        const nextBtnBottom = document.getElementById('nextBtnBottom');
        
        if (prevBtnBottom) prevBtnBottom.addEventListener('click', () => this.previousPage());
        if (nextBtnBottom) nextBtnBottom.addEventListener('click', () => this.nextPage());
    },

    switchView: function(view) {
        this.currentView = view;

        // Update button states
        document.getElementById('gridViewBtn').classList.toggle('btn-primary', view === 'grid');
        document.getElementById('gridViewBtn').classList.toggle('btn-outline-primary', view !== 'grid');
        document.getElementById('listViewBtn').classList.toggle('btn-primary', view === 'list');
        document.getElementById('listViewBtn').classList.toggle('btn-outline-secondary', view !== 'list');

        // Toggle container visibility
        document.getElementById('gridContainer').style.display = view === 'grid' ? '' : 'none';
        document.getElementById('listContainer').style.display = view === 'list' ? '' : 'none';

        // Re-render books with new view
        this.renderBooks(this.books);
    },

    loadBooks: function(page = 1) {
        this.currentPage = page;
        document.getElementById('loadingIndicator').style.display = 'block';
        document.getElementById('errorMessage').style.display = 'none';
        document.getElementById('gridContainer').innerHTML = '';
        document.getElementById('listContainer').innerHTML = '';
        document.getElementById('paginationTop').style.display = 'none';
        document.getElementById('paginationBottom').style.display = 'none';
        document.getElementById('noResults').style.display = 'none';

        ApiService.getBooks(page, this.perPage)
            .done((response) => this.handleSuccess(response))
            .fail((error) => this.handleError(error));
    },

    handleSuccess: function(response) {
        document.getElementById('loadingIndicator').style.display = 'none';

        if (response.error) {
            this.handleError(response);
            return;
        }

        const data = response.data || [];

        if (!data || data.length === 0) {
            document.getElementById('noResults').style.display = 'block';
            document.getElementById('bookCount').textContent = '0';
            return;
        }

        this.books = data;

        // Update pagination info from response meta
        if (response.meta && response.meta.pagination) {
            const pagination = response.meta.pagination;
            this.totalBooks = pagination.total || 0;
            this.totalPages = pagination.lastPage || 1;
        }

        document.getElementById('bookCount').textContent = this.totalBooks;
        this.renderBooks(data);
        this.updatePagination();
    },

    handleError: function(error) {
        document.getElementById('loadingIndicator').style.display = 'none';
        const errorMsg = error.message || error.responseText || 'Failed to load books. Please try again.';
        document.getElementById('errorMessage').textContent = errorMsg;
        document.getElementById('errorMessage').style.display = 'block';
    },

    renderBooks: function(books) {
        if (this.currentView === 'grid') {
            this.renderGridView(books);
        } else {
            this.renderListView(books);
        }
    },

    renderGridView: function(books) {
        const container = document.getElementById('gridContainer');
        container.innerHTML = '';

        books.forEach(book => {
            const bookCard = document.createElement('div');
            bookCard.className = 'col';
            bookCard.innerHTML = `
                <div class="card h-100 shadow-sm book-card">
                    <div class="book-image-wrapper" style="height: 250px; overflow: hidden; background-color: #f5f5f5;">
                        ${book.image_url 
                            ? `<img src="${this.escapeHtml(book.image_url)}" class="card-img-top" alt="${this.escapeHtml(book.title)}" style="height: 100%; width: 100%; object-fit: cover;">` 
                            : `<div class="d-flex align-items-center justify-content-center h-100"><i class="bi bi-book text-secondary" style="font-size: 3rem;"></i></div>`
                        }
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">${this.escapeHtml(book.title)}</h5>
                        <p class="card-text text-muted flex-grow-1">
                            <small>by ${this.escapeHtml(book.author_name || 'Unknown Author')}</small>
                        </p>
                    </div>
                </div>
            `;
            container.appendChild(bookCard);
        });
    },

    renderListView: function(books) {
        const container = document.getElementById('listContainer');
        container.innerHTML = '';

        const listHTML = books.map(book => `
            <div class="list-item border-bottom py-3 d-flex gap-3">
                <div style="min-width: 80px; height: 120px; background-color: #f5f5f5; flex-shrink: 0; overflow: hidden;">
                    ${book.image_url 
                        ? `<img src="${this.escapeHtml(book.image_url)}" alt="${this.escapeHtml(book.title)}" style="height: 100%; width: 100%; object-fit: cover;">` 
                        : `<div class="d-flex align-items-center justify-content-center h-100"><i class="bi bi-book text-secondary"></i></div>`
                    }
                </div>
                <div class="flex-grow-1">
                    <h5 class="mb-2">${this.escapeHtml(book.title)}</h5>
                    <p class="mb-0 text-muted">
                        <strong>Author:</strong> ${this.escapeHtml(book.author_name || 'Unknown Author')}
                    </p>
                </div>
            </div>
        `).join('');

        container.innerHTML = listHTML;
    },

    updatePagination: function() {
        // Update top pagination
        this.updatePaginationUI('Top');
        // Update bottom pagination
        this.updatePaginationUI('Bottom');
    },

    updatePaginationUI: function(position) {
        const paginationId = `pagination${position}`;
        const paginationContainer = document.getElementById(paginationId);
        const pageInfoId = `pageInfo${position}`;
        const pageInfoSpan = document.getElementById(pageInfoId);
        const pageButtonsId = `pageButtons${position}`;
        const pageButtonsContainer = document.getElementById(pageButtonsId);
        const prevBtnId = `prevBtn${position}`;
        const prevBtn = document.getElementById(prevBtnId);
        const nextBtnId = `nextBtn${position}`;
        const nextBtn = document.getElementById(nextBtnId);

        // Check if elements exist
        if (!paginationContainer || !pageInfoSpan) {
            console.warn(`Pagination elements for ${position} not found`);
            return;
        }

        // Update page info text
        pageInfoSpan.textContent = `Page ${this.currentPage} of ${this.totalPages}`;

        // Update prev button state
        if (prevBtn) prevBtn.disabled = this.currentPage <= 1;

        // Update next button state
        if (nextBtn) nextBtn.disabled = this.currentPage >= this.totalPages;

        // Generate page number buttons (show max 5 pages)
        if (pageButtonsContainer) {
            pageButtonsContainer.innerHTML = '';
            const maxPageButtons = 5;
            let startPage = Math.max(1, this.currentPage - Math.floor(maxPageButtons / 2));
            let endPage = Math.min(this.totalPages, startPage + maxPageButtons - 1);

            if (endPage - startPage < maxPageButtons - 1) {
                startPage = Math.max(1, endPage - maxPageButtons + 1);
            }

            for (let i = startPage; i <= endPage; i++) {
                const btn = document.createElement('button');
                btn.className = `btn btn-sm ${i === this.currentPage ? 'btn-primary' : 'btn-outline-primary'}`;
                btn.textContent = i;
                btn.addEventListener('click', () => this.loadBooks(i));
                pageButtonsContainer.appendChild(btn);
            }
        }

        // Show pagination if there's more than one page
        paginationContainer.style.display = this.totalPages > 1 ? 'block' : 'none';
    },

    previousPage: function() {
        if (this.currentPage > 1) {
            this.loadBooks(this.currentPage - 1);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    },

    nextPage: function() {
        if (this.currentPage < this.totalPages) {
            this.loadBooks(this.currentPage + 1);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    },

    escapeHtml: function(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    BookDisplay.init();
});

let currentPage = 1;
let currentSort = 'id';
let currentDirection = 'asc';

$(document).ready(function() {
    let page = 1;
    let perPage = 10;
    
    // 初始化年份選單
    loadYearOptions();
    
    renderBookList(page, perPage);

    // 搜尋按鈕事件
    $('#search-btn').on('click', function() {
        currentPage = 1; // 重置為第一頁
        const searchTitle = $('#search-title').val();
        const searchAuthor = $('#search-author').val();
        const searchYear = $('#search-year').val();
        renderBookList(1, perPage, searchTitle, searchAuthor, searchYear);
    });

    // 重置按鈕事件
    $('#reset-search-btn').on('click', function() {
        $('#search-title').val('');
        $('#search-author').val('');
        $('#search-year').val('');
        currentPage = 1;
        currentSort = 'id';
        currentDirection = 'asc';
        updateSortIndicators();
        renderBookList(1, perPage);
    });

    // 回車鍵觸發搜尋
    $('#search-title, #search-author').on('keypress', function(e) {
        if(e.which === 13) {
            e.preventDefault();
            $('#search-btn').click();
        }
    });

    // 可排序欄位的點擊事件
    $('thead').on('click', '.sortable-column', function() {
        const column = $(this).data('column');
        
        // 如果點擊同一欄位，切換排序方向；否則設為新欄位並預設 asc
        if(currentSort === column) {
            currentDirection = currentDirection === 'asc' ? 'desc' : 'asc';
        } else {
            currentSort = column;
            currentDirection = 'asc';
        }
        
        currentPage = 1; // 重置為第一頁
        updateSortIndicators();
        
        const searchTitle = $('#search-title').val();
        const searchAuthor = $('#search-author').val();
        const searchYear = $('#search-year').val();
        renderBookList(1, perPage, searchTitle, searchAuthor, searchYear);
    });

    $('#add-book-form').on('submit', function(e) {
        e.preventDefault(); // 阻止頁面跳轉

        const $form = $(this);
        const $errorBox = $('#add-book-error-message');
        const $createBookBtn = $('#create-book-btn');
        const $spinner = $('#create-book-btn-spinner');

        // 清空並隱藏錯誤訊息
        $errorBox.addClass('d-none').empty();
        
        // 按鈕讀取中狀態
        $createBookBtn.prop('disabled', true);
        $spinner.removeClass('d-none');

        // 取得表單資料
        // 使用 [0] 取得原生 DOM 元素
        const formElement = $(this)[0]; 
        const formData = new FormData(formElement);

        // 使用你的 ApiService 進行新增書籍
        ApiService.createBook(formData)
            .done(function(response) {
                // 新增書籍成功處理
                if(response.status === 'success') {
                    // 重新載入年份選單和書籍列表
                    loadYearOptions();
                    renderBookList(page, perPage);
                    // 關閉模態框
                    $('#addBookModal').modal('hide');
                    // 清空表單
                    $form[0].reset();
                }
            })
            .fail(function(xhr) {
                // 新增書籍失敗處理
                const response = xhr.responseJSON;
                let message = 'Failed to create book. Please try again later.';

                if(response && response.messages) {
                    // 如果後端回傳的是 validation 錯誤陣列
                    if(typeof response.messages === 'object') {
                        message = Object.values(response.messages).join('<br>');
                    }else {
                        message = response.messages;
                    }
                }else if(response && response.error) {
                    message = response.error;
                }

                $errorBox.html(message).removeClass('d-none');
            })
            .always(function() {
                // 恢復按鈕狀態
                $createBookBtn.prop('disabled', false);
                $spinner.addClass('d-none');
            });
    });

    $('#edit-book-form').on('submit', function(e) {
        e.preventDefault(); // 阻止頁面跳轉

        const $form = $(this);
        const $errorBox = $('#edit-book-error-message');
        const $editBookBtn = $('#edit-book-btn');
        const $spinner = $('#edit-book-btn-spinner');

        // 清空並隱藏錯誤訊息
        $errorBox.addClass('d-none').empty();
        
        // 按鈕讀取中狀態
        $editBookBtn.prop('disabled', true);
        $spinner.removeClass('d-none');

        // 取得表單資料
        const formData = $form.serialize();
        const bookId = $form.find('[name="id"]').val();

        // 使用你的 ApiService 進行編輯書籍
        ApiService.editBook(formData, bookId)
            .done(function(response) {
                // 編輯書籍成功處理
                if(response.status === 'success') {
                    // 重新載入年份選單和書籍列表
                    loadYearOptions();
                    const searchTitle = $('#search-title').val();
                    const searchAuthor = $('#search-author').val();
                    const searchYear = $('#search-year').val();
                    renderBookList(page, perPage, searchTitle || null, searchAuthor || null, searchYear || null);
                    // 關閉模態框
                    $('#editBookModal').modal('hide');
                    // 清空表單
                    $form[0].reset();
                }
            })
            .fail(function(xhr) {
                // 編輯書籍失敗處理
                const response = xhr.responseJSON;
                let message = 'Failed to edit book. Please try again later.';

                if(response && response.messages) {
                    // 如果後端回傳的是 validation 錯誤陣列
                    if(typeof response.messages === 'object') {
                        message = Object.values(response.messages).join('<br>');
                    }else {
                        message = response.messages;
                    }
                }else if(response && response.error) {
                    message = response.error;
                }

                $errorBox.html(message).removeClass('d-none');
            })
            .always(function() {
                // 恢復按鈕狀態
                $editBookBtn.prop('disabled', false);
                $spinner.addClass('d-none');
            });
    });

    // 綁定分頁點擊事件
    $('#pagination-container').on('click', '.page-link', function(e) {
        e.preventDefault();
        const targetPage = $(this).data('page');
        const searchTitle = $(this).data('title');
        const searchAuthor = $(this).data('author');
        const searchYear = $(this).data('year');
        
        // 如果按鈕沒被禁用且頁碼有效
        if(targetPage && !$(this).parent().hasClass('disabled') && targetPage !== currentPage) {
            currentPage = targetPage;
            renderBookList(currentPage, perPage, searchTitle || null, searchAuthor || null, searchYear || null);
        }
    });

    // 編輯圖片表單提交
    $('#edit-image-form').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $errorBox = $('#edit-image-error-message');
        const $submitBtn = $('#edit-image-btn');
        const $spinner = $('#edit-image-btn-spinner');
        
        $errorBox.addClass('d-none').empty();
        $submitBtn.prop('disabled', true);
        $spinner.removeClass('d-none');
        
        // 取得表單原生DOM
        const formElement = $(this)[0];
        
        // 建立FormData
        const formData = new FormData(formElement);
        
        // 手動塞入_method欄位，值為PUT
        formData.append('_method', 'PUT'); 
        
        const bookId = $form.find('[name="id"]').val();
        
        // 呼叫 ApiService
        ApiService.editBook(formData, bookId)
            .done(function(response) {
                if(response.status === 'success') {
                    loadYearOptions();
                    const searchTitle = $('#search-title').val();
                    const searchAuthor = $('#search-author').val();
                    const searchYear = $('#search-year').val();
                    renderBookList(currentPage, 10, searchTitle || null, searchAuthor || null, searchYear || null);
                    $('#editImageModal').modal('hide');
                    $form[0].reset();
                }
            })
            .fail(function(xhr) {
                const response = xhr.responseJSON;
                let message = 'Failed to update image. Please try again later.';
                
                if(response && response.messages) {
                    if(typeof response.messages === 'object') {
                        message = Object.values(response.messages).join('<br>');
                    } else {
                        message = response.messages;
                    }
                } else if(response && response.error) {
                    message = response.error;
                }
                
                $errorBox.html(message).removeClass('d-none');
            })
            .always(function() {
                $submitBtn.prop('disabled', false);
                $spinner.addClass('d-none');
            });
    });
});

$(document).on('click', '.edit-book-btn', function() {
    // 取得按鈕上的 JSON 資料
    const book = JSON.parse(decodeURIComponent($(this).data('book')));
    
    const $modal = $('#editBookModal');
    
    // 填寫表單欄位
    $modal.find('input[name="id"]').val(book.id); // 隱藏欄位存 ID
    $modal.find('input[name="title"]').val(book.title);
    $modal.find('input[name="author_name"]').val(book.author_name);
    $modal.find('input[name="price"]').val(book.price);
    $modal.find('input[name="year"]').val(book.year);

    // 顯示 Modal
    $modal.modal('show');
});

$(document).on('click', '.edit-image-btn', function() {
    const bookId = $(this).data('id');
    const imageUrl = $(this).data('image');
    const bookTitle = $(this).data('title');
    
    const $modal = $('#editImageModal');
    
    // 設定 book ID 和書籍標題
    $modal.find('input[name="id"]').val(bookId);
    $modal.find('.book-title-label').text(bookTitle);
    
    // 顯示原圖片預覽
    $modal.find('#current-image-preview').attr('src', imageUrl);
    
    // 清空新圖片預覽和檔案選擇
    $modal.find('#new-image-preview').attr('src', '').addClass('d-none');
    $modal.find('input[name="book_image"]').val('');
    $modal.find('.no-image-message').removeClass('d-none');
    
    // 顯示 Modal
    $modal.modal('show');
});

// 圖片檔案選擇後的預覽
$(document).on('change', '#editImageModal input[name="book_image"]', function() {
    const file = this.files[0];
    
    if(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const $modal = $('#editImageModal');
            $modal.find('#new-image-preview').attr('src', e.target.result).removeClass('d-none');
            $modal.find('.no-image-message').addClass('d-none');
        };
        reader.readAsDataURL(file);
    }
});

$(document).on('click', '.delete-book-btn', function() {
    const bookId = $(this).data('id');
    const $button = $(this);
    
    // 確認刪除
    if(confirm('Are you sure you want to delete this book? This action cannot be undone.')) {
        $button.prop('disabled', true);
        const originalHtml = $button.html();
        $button.html('<span class="spinner-border spinner-border-sm" role="status"></span>');
        
        ApiService.deleteBook(bookId)
            .done(function(response) {
                if(response.status === 'success') {
                    // 重新載入年份選單和書籍列表
                    loadYearOptions();
                    const searchTitle = $('#search-title').val();
                    const searchAuthor = $('#search-author').val();
                    const searchYear = $('#search-year').val();
                    renderBookList(currentPage, 10, searchTitle || null, searchAuthor || null, searchYear || null);
                }
            })
            .fail(function(xhr) {
                const response = xhr.responseJSON;
                let message = 'Failed to delete book. Please try again later.';
                
                if(response && response.messages) {
                    message = typeof response.messages === 'object' 
                        ? Object.values(response.messages).join('\n')
                        : response.messages;
                } else if(response && response.error) {
                    message = response.error;
                }
                
                alert(message);
                $button.prop('disabled', false);
                $button.html(originalHtml);
            });
    }
});

function renderBookList(page, perPage, searchTitle = null, searchAuthor = null, searchYear = null) {
    ApiService.getBooks(page, perPage, searchTitle, searchAuthor, searchYear, currentSort, currentDirection).done(function(response) {
        const books = Array.isArray(response) ? response : response.data;
        let html = '';

        books.forEach(book => {
            const detailUrl = `/books/detail/${book.id}`;
            
            // 將 book 物件轉為 JSON 字串，方便存入 data 屬性 (注意引號處理)
            const bookData = encodeURIComponent(JSON.stringify(book));

            html += `
                <tr>
                    <td>${book.id}</td>
                    <td class="text-truncate" style="max-width: 150px;" title="${book.title}">${book.title}</td>
                    <td>
                        <a href="${detailUrl}" class="text-decoration-none shadow-none" target="_blank">
                            <img src="${book.image_url || '/images/default-book.png'}" class="w-100" 
                                 style="height: 120px; object-fit: contain; background: transparent;">
                        </a>
                    </td>
                    <td>${book.author_name}</td>
                    <td>${book.year}</td>
                    <td>${book.created_at}</td>
                    <td>${book.updated_at}</td>
                    <td class="text-truncate" style="max-width: 150px;" title="${book.slug}">${book.slug}</td>
                    <td>$${parseFloat(book.price).toFixed(2)}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-info edit-book-btn" data-book="${bookData}">
                            <i class="bi bi-pencil"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-outline-warning edit-image-btn ms-2" data-id="${book.id}" data-image="${book.image_url}" data-title="${book.title}">
                            <i class="bi bi-image"></i> Edit Image
                        </button>
                        <button class="btn btn-sm btn-outline-danger delete-book-btn ms-2" data-id="${book.id}">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </td>
                </tr>`;
        });

        $('#book-table-body').html(html);

        if(response.meta && response.meta.pagination) {
            currentPage = response.meta.pagination.page;
            renderPagination(response.meta.pagination.page, response.meta.pagination.lastPage, searchTitle, searchAuthor, searchYear);
        }
    });
}

// 加載所有可用的年份到選單
function loadYearOptions() {
    // 獲取所有書籍來提取年份
    ApiService.getBooks(null, null, null, null, null, currentSort, currentDirection).done(function(response) {
        const books = Array.isArray(response) ? response : response.data;
        const years = new Set();
        
        books.forEach(book => {
            if(book.year) {
                years.add(book.year);
            }
        });
        
        // 排序年份（由新到舊）
        const sortedYears = Array.from(years).sort((a, b) => b - a);
        
        // 填充選單
        const $yearSelect = $('#search-year');
        const currentValue = $yearSelect.val();
        
        $yearSelect.html('<option value="">All Years</option>');
        sortedYears.forEach(year => {
            $yearSelect.append(`<option value="${year}">${year}</option>`);
        });
        
        // 恢復之前的選擇
        if(currentValue) {
            $yearSelect.val(currentValue);
        }
    });
}

// 更新排序指示符號
function updateSortIndicators() {
    $('.sortable-column .sort-indicator').text('⇅');
    
    const $activeColumn = $(`.sortable-column[data-column="${currentSort}"]`);
    if($activeColumn.length) {
        const indicator = currentDirection === 'asc' ? '⇧' : '⇩';
        $activeColumn.find('.sort-indicator').text(indicator);
    }
}

// 渲染分頁按鈕的函式
function renderPagination(currentPage, totalPages, searchTitle = null, searchAuthor = null, searchYear = null) {
    const $container = $('#pagination-container');
    $container.empty();

    // 「上一頁」按鈕
    const prevDisabled = currentPage === 1 ? 'disabled' : '';
    $container.append(`
        <li class="page-item ${prevDisabled}">
            <a class="page-link bg-dark text-white border-secondary" href="#" data-page="${currentPage - 1}" data-title="${searchTitle || ''}" data-author="${searchAuthor || ''}" data-year="${searchYear || ''}">Previous</a>
        </li>
    `);

    // 頁碼按鈕 (這裡簡單示範，若頁數過多建議只顯示前後幾頁)
    for(let i = 1; i <= totalPages; i++) {
        const activeClass = i === currentPage ? 'active' : '';
        const activeStyle = i === currentPage ? 'bg-primary border-primary' : 'bg-dark text-white border-secondary';
        
        $container.append(`
            <li class="page-item ${activeClass}">
                <a class="page-link ${activeStyle}" href="#" data-page="${i}" data-title="${searchTitle || ''}" data-author="${searchAuthor || ''}" data-year="${searchYear || ''}">${i}</a>
            </li>
        `);
    }

    // 「下一頁」按鈕
    const nextDisabled = currentPage === totalPages ? 'disabled' : '';
    $container.append(`
        <li class="page-item ${nextDisabled}">
            <a class="page-link bg-dark text-white border-secondary" href="#" data-page="${currentPage + 1}" data-title="${searchTitle || ''}" data-author="${searchAuthor || ''}" data-year="${searchYear || ''}">Next</a>
        </li>
    `);
}
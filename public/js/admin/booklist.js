let currentPage = 1;
$(document).ready(function() {
    let page = 1;
    let perPage = 10;
    renderBookList(page, perPage);

    $('#add-book-form').on('submit', function(e) {
        e.preventDefault(); // 阻止頁面跳轉

        const $form = $(this);
        const $errorBox = $('#error-message');
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
                    // 重新載入書籍列表
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

    // 綁定分頁點擊事件
    $('#pagination-container').on('click', '.page-link', function(e) {
        e.preventDefault();
        const targetPage = $(this).data('page');
        
        // 如果按鈕沒被禁用且頁碼有效
        if(targetPage && !$(this).parent().hasClass('disabled') && targetPage !== currentPage) {
            currentPage = targetPage;
            renderBookList(currentPage, perPage); // 重新呼叫抓取資料函式
        }
    });
});

function renderBookList(page, perPage) {
    ApiService.getBooks(page, perPage).done(function(response) {
        const books = Array.isArray(response) ? response : response.data;
        let html = '';

        books.forEach(book => {
            const detailUrl = `/books/detail/${book.id}`;

            html += `
                <tr>
                    <td>${book.id}</td>
                    <td class="text-truncate" style="max-width: 150px;" title="${book.title}">${book.title}</td>
                    <td>
                        <a href="${detailUrl}" class="text-decoration-none shadow-none" target="_blank">
                            <img src="/images/books/${book.image_url}" class="w-100" 
                                 style="height: 200px; object-fit: contain; background: transparent;">
                        </a>
                    </td>
                    <td>${book.author_name}</td>
                    <td>${book.year}</td>
                    <td>${book.created_at}</td>
                    <td>${book.updated_at}</td>
                    <td class="text-truncate" style="max-width: 150px;" title="${book.slug}">${book.slug}</td>
                    <td>$${book.price.toFixed(2)}</td>
                </tr>`;
        });

        $('#book-table-body').html(html);

        if(response.meta.pagination) {
            currentPage = response.meta.pagination.page;
            renderPagination(response.meta.pagination.page, response.meta.pagination.lastPage);
        }
    });
}

// 渲染分頁按鈕的函式
function renderPagination(currentPage, totalPages) {
    const $container = $('#pagination-container');
    $container.empty();

    // 「上一頁」按鈕
    const prevDisabled = currentPage === 1 ? 'disabled' : '';
    $container.append(`
        <li class="page-item ${prevDisabled}">
            <a class="page-link bg-dark text-white border-secondary" href="#" data-page="${currentPage - 1}">Previous</a>
        </li>
    `);

    // 頁碼按鈕 (這裡簡單示範，若頁數過多建議只顯示前後幾頁)
    for(let i = 1; i <= totalPages; i++) {
        const activeClass = i === currentPage ? 'active' : '';
        const activeStyle = i === currentPage ? 'bg-primary border-primary' : 'bg-dark text-white border-secondary';
        
        $container.append(`
            <li class="page-item ${activeClass}">
                <a class="page-link ${activeStyle}" href="#" data-page="${i}">${i}</a>
            </li>
        `);
    }

    // 「下一頁」按鈕
    const nextDisabled = currentPage === totalPages ? 'disabled' : '';
    $container.append(`
        <li class="page-item ${nextDisabled}">
            <a class="page-link bg-dark text-white border-secondary" href="#" data-page="${currentPage + 1}">Next</a>
        </li>
    `);
}
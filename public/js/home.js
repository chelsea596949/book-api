$(document).ready(function() {
    renderBookCarousel();

    const token = localStorage.getItem('auth_token');
    const $authZone = $('#auth-zone');

    if(token) {
        // 從 localStorage 讀取儲存的用戶名 (推薦登入時順便存入)
        const username = localStorage.getItem('uid') || 'User';
        
        renderWelcome(username);
    }

    // 渲染歡迎介面
    function renderWelcome(name) {
        $authZone.html(`
            <div class="d-flex align-items-center">
                <span class="text-white-50 me-3">
                    Welcome, <strong class="text-white">${name}</strong>
                    <a class="btn btn-sm btn-outline-info px-3" href="/admin">AdminPanel</a>
                </span>
            </div>
        `);
    }

    // 登出事件處理
    $(document).on('click', '#logout-btn', function() {
        if(confirm('Are you sure you want to log out?')) {
            localStorage.removeItem('auth_token');
            localStorage.removeItem('uid');
            window.location.reload(); // 重新整理頁面回到未登入狀態
        }
    });
});

function renderBookCarousel() {
    ApiService.getBooks().done(function(response) {
        const books = Array.isArray(response) ? response : response.data;
        let html = '';

        books.forEach(book => {
            const detailUrl = `/books/detail/${book.id}`;

            html += `
                <div class="slick-item">
                    <a href="${detailUrl}" class="text-decoration-none shadow-none" target="_blank" data-bs-toggle="tooltip" data-bs-title="${book.title}">
                        <img src="/images/books/${book.image_url}" class="w-100" 
                             style="height: 500px; object-fit: contain; background: transparent;">
                    </a>
                </div>`;
        });

        $('#book-slick-slider').html(html);

        initSlick(); // 在內容渲染完成後初始化 Slick 插件

        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    });
}

// 在 AJAX 成功渲染完內容後執行
function initSlick() {
    $('#book-slick-slider').slick({
        centerMode: true,        // 開啟中間模式
        centerPadding: '80px',  // 兩側露出的寬度
        slidesToShow: 3,         // 中間顯示一張
        autoplay: true,
        autoplaySpeed: 5000,
        arrows: false,            // 顯示左右箭頭
        dots: true,              // 顯示下方的點點
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    centerPadding: '40px',
                    slidesToShow: 1
                }
            }
        ]
    });
}
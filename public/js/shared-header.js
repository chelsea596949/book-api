// Shared Header Component Logic
// Handles authentication state display in the sticky auth zone

function initSharedHeader() {
    const token = localStorage.getItem('auth_token');
    const exp = localStorage.getItem('login_exp');
    const $authZone = $('#auth-zone-sticky');

    // 檢查 token 是否存在且未過期
    if(token && exp && isTokenValid(exp)) {
        // 從 localStorage 讀取儲存的用戶名
        const username = localStorage.getItem('uid') || 'User';
        renderWelcome(username, $authZone);
    } else {
        // token 過期或不存在，清除 localStorage
        clearAuthData();
        renderLoginButton($authZone);
    }

    // 登出事件處理
    $(document).on('click', '#logout-btn', function() {
        if(confirm('Are you sure you want to log out?')) {
            clearAuthData();
            window.location.reload();
        }
    });
}

// 檢查 token 是否過期
function isTokenValid(exp) {
    const expirationTime = parseInt(exp) * 1000;
    const currentTime = Date.now();
    return currentTime < expirationTime;
}

// 清除認證資料
function clearAuthData() {
    localStorage.removeItem('auth_token');
    localStorage.removeItem('uid');
    localStorage.removeItem('login_iat');
    localStorage.removeItem('login_exp');
}

// 渲染歡迎介面
function renderWelcome(name, $authZone) {
    $authZone.html(`
        <div class="d-flex align-items-center gap-2">
            <span class="text-white-50">
                Welcome, <strong class="text-white">${name}</strong>
            </span>
            <a class="btn btn-sm btn-outline-info px-2" href="/admin">Admin</a>
            <button id="logout-btn" class="btn btn-sm btn-outline-light px-2">Logout</button>
        </div>
    `);
}

// 渲染登入按鈕 (預設狀態)
function renderLoginButton($authZone) {
    $authZone.html(`
        <a href="/users/login" class="btn btn-outline-light px-4">LOG IN</a>
    `);
}

// 當文檔準備好時初始化頭部
$(document).ready(function() {
    initSharedHeader();
});

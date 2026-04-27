$(document).ready(function() {
    $('#login-form').on('submit', function(e) {
        e.preventDefault(); // 阻止頁面跳轉

        const $form = $(this);
        const $errorBox = $('#error-message');
        const $loginBtn = $('#login-btn');
        const $spinner = $('#btn-spinner');

        // 清空並隱藏錯誤訊息
        $errorBox.addClass('d-none').empty();
        
        // 按鈕讀取中狀態
        $loginBtn.prop('disabled', true);
        $spinner.removeClass('d-none');

        // 取得表單資料
        const formData = $form.serialize();

        // 使用你的 ApiService 進行登入
        ApiService.login(formData)
            .done(function(response) {
                // 登入成功處理
                if(response.status === 'success' || response.data.token) {
                    // 如果有回傳 JWT，存入 localStorage
                    if(response.data.token) {
                        localStorage.setItem('auth_token', response.data.token);
                        localStorage.setItem('uid', response.data.uid);
                        localStorage.setItem('login_iat', response.data.iat);
                        localStorage.setItem('login_exp', response.data.exp);
                        
                        document.cookie = `auth_token=${response.data.token}; path=/; max-age=3600; SameSite=Strict`;
                    }
                    // 跳轉至首頁或書本清單
                    window.location.href = '/'; 
                }
            })
            .fail(function(xhr) {
                // 登入失敗處理
                const response = xhr.responseJSON;
                let message = 'Login fail. Please Try later.';

                if(response && response.messages) {
                    // 如果後端回傳的是 validation 錯誤陣列
                    if(typeof response.messages === 'object') {
                        message = Object.values(response.messages).join('<br>');
                    }else {
                        message = response.messages;
                    }
                }else if (response && response.error) {
                    message = response.error;
                }

                $errorBox.html(message).removeClass('d-none');
            })
            .always(function() {
                // 恢復按鈕狀態
                $loginBtn.prop('disabled', false);
                $spinner.addClass('d-none');
            });
    });
});
const ApiService = {
    baseUrl: '/api',

    request: function(endpoint, method='GET', data=null) {
        // 初始化基礎 AJAX 設定
        const ajaxConfig = {
            url: this.baseUrl + endpoint,
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('jwt_token')
            },
            method: method,
            data: data,
            dataType: 'json'
        };

        // 自動偵測：如果 data 是 FormData 物件（通常用於檔案上傳）
        if(data instanceof FormData) {
            ajaxConfig.processData = false; // 告訴 jQuery 不要處理資料（不轉成字串）
            ajaxConfig.contentType = false; // 告訴 jQuery 不要設定 Content-Type（由瀏覽器自動加上 boundary）
        }

        return $.ajax(ajaxConfig);
    },

    // 專門抓取書本資料
    getBooks: function(page = null, perPage = null) {
        const params = {};
        if (page !== null) params.page = page;
        if (perPage !== null) params.perPage = perPage;
        return this.request('/books', 'GET', params);
    },

    // 抓取單本書
    getBookDetail: function(id) {
        return this.request(`/books/${id}`, 'GET');
    },

    // 新增書（傳入 FormData 或一般物件都行）
    createBook: function(data) {
        return this.request('/books', 'POST', data);
    },

    // 編輯書（傳入 FormData 或一般物件都行）
    editBook: function(data, id) {
        return this.request(`/books/${id}`, 'PUT', data);
    },

    // 登入
    login: function(data) {
        return this.request('/login', 'POST', data);
    },

    // 註冊
    register: function(data) {
        return this.request('/register', 'POST', data);
    }
};

$(document).ready(function() {
    // 全局 AJAX 設置：在每次 AJAX 請求前自動帶入 JWT Token
    $.ajaxSetup({
        beforeSend: function(xhr) {
            const token = localStorage.getItem('auth_token');
            if(token) {
                xhr.setRequestHeader('Authorization', 'Bearer ' + token);
            }
        }
    });
});
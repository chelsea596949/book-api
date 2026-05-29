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
    getBooks: function(page = null, perPage = null, search = null) {
        const params = {};
        if (page !== null) params.page = page;
        if (perPage !== null) params.perPage = perPage;
        if (search !== null && search !== '') params.search = search;
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

    // 編輯書（自動相容一般物件與帶圖片的FormData）
    editBook: function(data, id) {
        // 預設是標準的PUT
        let method = 'PUT';
        
        // 如果傳進來的是 FormData，就把實際Method改為POST，並手動塞入_method='PUT'
        if(data instanceof FormData) {
            method = 'POST';
            
            // 檢查是否已經有塞過_method，沒有的話再補，避免重複塞入
            if(!data.has('_method')) {
                data.append('_method', 'PUT');
            }
        }

        return this.request(`/books/${id}`, method, data);
    },

    // 刪除書
    deleteBook: function(id) {
        return this.request(`/books/${id}`, 'DELETE');
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
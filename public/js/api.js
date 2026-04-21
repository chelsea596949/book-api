const ApiService = {
    baseUrl: '/api',

    request: function(endpoint, method = 'GET', data = null) {
        // 初始化基礎 AJAX 設定
        const ajaxConfig = {
            url: this.baseUrl + endpoint,
            method: method,
            data: data,
            dataType: 'json'
        };

        // 自動偵測：如果 data 是 FormData 物件（通常用於檔案上傳）
        if (data instanceof FormData) {
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

    // 新增書（傳入 FormData 或一般物件都行）
    createBook: function(data) {
        return this.request('/books', 'POST', data);
    },

    // 登入
    login: function(data) {
        return this.request('/login', 'POST', data);
    }
};
const ApiService = {
    // 統一 API 的基礎路徑，方便以後換網域時一鍵修改
    baseUrl: '/api',

    // 通用的請求函式
    request: function(endpoint, method='GET', data=null) {
        return $.ajax({
            url: this.baseUrl+endpoint,
            method: method,
            data: data,
            dataType: 'json',
            // 如果後端需要上傳檔案，這裡可能需要根據 data 類型動態調整
        });
    },

    // 專門抓取書本資料的函式
    getBooks: function() {
        return this.request('/books');
    },
    
    // 專門抓取書本詳細資料的函式
    getBookDetail: function(id) {
        return this.request(`/books/${id}`);
    },
};
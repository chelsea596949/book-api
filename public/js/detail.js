$(document).ready(function() {
    // --- 取得網址最後一個段落 (ID) ---
    const url = window.location.pathname;
    const bookId = url.substring(url.lastIndexOf('/') + 1);
    renderBookDetail(bookId);
});

function renderBookDetail(id) {
    ApiService.getBookDetail(id).done(function(response) {
        const book = response.data;

        $("#book_image").attr("src", `/images/books/${book.image_url}`);
        $("#book_title").text(book.title);
        $("#book_author").text(book.author_name);
        $("#book_year").text(book.year);
        $("#book_price").text(book.price);
    });
}
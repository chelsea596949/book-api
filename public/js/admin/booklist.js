$(document).ready(function() {
    renderBookList();
});

function renderBookList() {
    ApiService.getBooks().done(function(response) {
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
    });
}
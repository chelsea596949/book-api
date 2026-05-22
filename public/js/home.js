$(document).ready(function() {
    renderBookCarousel();




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

        initSlick();

        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    });
}

function initSlick() {
    $('#book-slick-slider').slick({
        centerMode: true,
        centerPadding: '80px',
        slidesToShow: 3,
        autoplay: true,
        autoplaySpeed: 5000,
        arrows: false,
        dots: true,
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

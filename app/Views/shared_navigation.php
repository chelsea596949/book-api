<!-- Shared Navigation Component -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-secondary">
    <div class="container-fluid">
        <!-- Brand/Logo -->
        <a class="navbar-brand fw-bold fs-5" href="<?= base_url('/') ?>">
            <span class="text-primary">That</span> Bookstore.
        </a>

        <!-- Toggler for Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/') ?>" 
                       id="home-link">
                        <i class="bi bi-house-fill"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('books/display') ?>" 
                       id="books-link">
                        <i class="bi bi-book-fill"></i> Books
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    .navbar {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .navbar-brand {
        transition: opacity 0.3s ease;
    }

    .navbar-brand:hover {
        opacity: 0.8;
    }

    .navbar-nav .nav-link {
        padding: 0.5rem 1rem !important;
        transition: color 0.3s ease;
        color: #adb5bd !important;
    }

    .navbar-nav .nav-link:hover,
    .navbar-nav .nav-link.active {
        color: #0d6efd !important;
    }

    .navbar-nav .nav-link i {
        margin-right: 0.5rem;
    }

    @media (max-width: 991px) {
        .navbar-nav .nav-link {
            padding: 0.75rem 0 !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .navbar-nav .nav-link:last-child {
            border-bottom: none;
        }
    }
</style>

<script>
    // Set active nav link based on current page
    document.addEventListener('DOMContentLoaded', function() {
        const currentPath = window.location.pathname;
        const homeLink = document.getElementById('home-link');
        const booksLink = document.getElementById('books-link');

        // Remove active class from all links
        document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
            link.classList.remove('active');
        });

        // Add active class to current page link
        if (currentPath === '/' || currentPath === '') {
            homeLink?.classList.add('active');
        } else if (currentPath.includes('books')) {
            booksLink?.classList.add('active');
        }
    });
</script>
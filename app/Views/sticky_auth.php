<!-- Sticky Auth Component -->
<div id="sticky-auth-container" class="sticky-auth-wrapper">
    <div id="auth-zone-sticky" class="auth-zone-sticky">
        <!-- 登入按钮 (默认显示) -->
        <a href="/users/login" class="btn btn-outline-light px-4">LOG IN</a>
    </div>
</div>

<style>
    .sticky-auth-wrapper {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        display: flex;
        align-items: center;
    }

    .auth-zone-sticky {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .auth-zone-sticky .btn {
        white-space: nowrap;
    }

    @media (max-width: 768px) {
        .sticky-auth-wrapper {
            top: 10px;
            right: 10px;
        }

        .auth-zone-sticky {
            gap: 10px;
        }

        .auth-zone-sticky .btn {
            padding: 0.375rem 0.75rem !important;
            font-size: 0.875rem;
        }
    }
</style>

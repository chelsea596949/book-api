let currentPage = 1;

$(document).ready(function() {
    const perPage = 10;

    renderUserList(1, perPage);

    $('#search-btn').on('click', function() {
        currentPage = 1;
        const searchUid = $('#search-uid').val();
        renderUserList(1, perPage, searchUid);
    });

    $('#reset-search-btn').on('click', function() {
        $('#search-uid').val('');
        currentPage = 1;
        renderUserList(1, perPage);
    });

    $('#search-uid').on('keypress', function(e) {
        if(e.which === 13) {
            e.preventDefault();
            $('#search-btn').click();
        }
    });

    $('#pagination-container').on('click', '.page-link', function(e) {
        e.preventDefault();
        const targetPage = $(this).data('page');

        if(targetPage && !$(this).parent().hasClass('disabled') && targetPage !== currentPage) {
            currentPage = targetPage;
            const searchUid = $(this).data('uid');
            renderUserList(currentPage, perPage, searchUid || null);
        }
    });
});

$(document).on('click', '.delete-user-btn', function() {
    const uid = $(this).data('uid');
    const $button = $(this);

    if(!confirm(`Are you sure you want to delete user "${uid}"? This action cannot be undone.`)) {
        return;
    }

    $button.prop('disabled', true);
    const originalHtml = $button.html();
    $button.html('<span class="spinner-border spinner-border-sm" role="status"></span>');

    ApiService.deleteUser(uid)
        .done(function(response) {
            if(response.status === 'success') {
                const searchUid = $('#search-uid').val();
                renderUserList(currentPage, 10, searchUid || null);
            }
        })
        .fail(function(xhr) {
            const response = xhr.responseJSON;
            let message = 'Failed to delete user. Please try again later.';

            if(response && response.message) {
                message = response.message;
            } else if(response && response.error) {
                message = response.error;
            }

            alert(message);
            $button.prop('disabled', false);
            $button.html(originalHtml);
        });
});

function escapeHtml(text) {
    if(text === null || text === undefined) {
        return '';
    }
    return String(text)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function renderUserList(page, perPage, searchUid = null) {
    ApiService.getUsers(page, perPage, searchUid).done(function(response) {
        const users = Array.isArray(response) ? response : response.data;
        let html = '';

        if(!users || users.length === 0) {
            html = `
                <tr>
                    <td colspan="5" class="text-muted py-4">No members found.</td>
                </tr>`;
        } else {
            users.forEach(user => {
                const uid = escapeHtml(user.uid);
                const name = escapeHtml(user.name);

                html += `
                <tr>
                    <td>${uid}</td>
                    <td>${name}</td>
                    <td>${user.created_at || '-'}</td>
                    <td>${user.updated_at || '-'}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-danger delete-user-btn" data-uid="${uid}">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </td>
                </tr>`;
            });
        }

        $('#user-table-body').html(html);

        if(response.meta && response.meta.pagination) {
            currentPage = response.meta.pagination.page;
            renderPagination(response.meta.pagination.page, response.meta.pagination.lastPage, searchUid);
        }
    }).fail(function(xhr) {
        const response = xhr.responseJSON;
        let message = 'Failed to load members. Please try again later.';

        if(response && response.message) {
            message = response.message;
        }

        $('#user-table-body').html(`
            <tr>
                <td colspan="5" class="text-danger py-4">${escapeHtml(message)}</td>
            </tr>`);
    });
}

function renderPagination(currentPageNum, totalPages, searchUid = null) {
    const $container = $('#pagination-container');
    $container.empty();

    const prevDisabled = currentPageNum === 1 ? 'disabled' : '';
    $container.append(`
        <li class="page-item ${prevDisabled}">
            <a class="page-link bg-dark text-white border-secondary" href="#" data-page="${currentPageNum - 1}" data-uid="${searchUid || ''}">Previous</a>
        </li>
    `);

    for(let i = 1; i <= totalPages; i++) {
        const activeClass = i === currentPageNum ? 'active' : '';
        const activeStyle = i === currentPageNum ? 'bg-primary border-primary' : 'bg-dark text-white border-secondary';

        $container.append(`
            <li class="page-item ${activeClass}">
                <a class="page-link ${activeStyle}" href="#" data-page="${i}" data-uid="${searchUid || ''}">${i}</a>
            </li>
        `);
    }

    const nextDisabled = currentPageNum === totalPages ? 'disabled' : '';
    $container.append(`
        <li class="page-item ${nextDisabled}">
            <a class="page-link bg-dark text-white border-secondary" href="#" data-page="${currentPageNum + 1}" data-uid="${searchUid || ''}">Next</a>
        </li>
    `);
}

<div class="d-flex" id="wrapper">
    <div class="bg-dark border-end vh-100" id="sidebar-wrapper" style="min-width: 250px;">
        <div class="sidebar-heading p-4 text-white fs-4 fw-bold">Admin Menu</div>
        <div class="list-group list-group-flush">
            <a class="list-group-item list-group-item-action border-secondary p-3 
            <?= ($_SERVER['REQUEST_URI'] == '/admin' || $_SERVER['REQUEST_URI'] == '/admin/index') ? 'active bg-white text-black-50' : 'bg-dark text-white-50 ' ?>" 
            href="/admin">
            Dashboard
            </a>

            <a class="list-group-item list-group-item-action border-secondary p-3 
            <?= (str_contains($_SERVER['REQUEST_URI'], '/admin/booklist')) ? 'active bg-white text-black-50' : ' bg-dark text-white-50' ?>" 
            href="/admin/booklist">
            Books Management
            </a>
        </div>
    </div>
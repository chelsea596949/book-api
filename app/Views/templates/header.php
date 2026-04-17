<!doctype html>
<html>
<head>
    <title>That BookStore.</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <?php if (isset($page_css) && is_array($page_css)): ?>
        <?php foreach ($page_css as $css): ?>
            <link rel="stylesheet" href="<?= (strpos($css, 'http') === 0) ? $css : base_url($css) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body data-bs-theme="dark">
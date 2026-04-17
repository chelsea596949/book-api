    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <?php if (isset($page_js) && is_array($page_js)): ?>
        <?php foreach ($page_js as $js): ?>
            <script src="<?= (strpos($js, 'http') === 0) ? $js : base_url($js) ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
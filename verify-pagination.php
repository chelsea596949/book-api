<?php
/**
 * Verification script for Book Display Page with Pagination
 * This script validates that all components are properly configured
 */

$errors = [];
$warnings = [];
$success = [];

echo "<!DOCTYPE html>
<html>
<head>
    <title>Book Display Page Verification</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .error { color: #d9534f; background: #f2dede; padding: 10px; margin: 10px 0; border: 1px solid #d6d8db; }
        .warning { color: #8a6d3b; background: #fcf8e3; padding: 10px; margin: 10px 0; border: 1px solid #faebcc; }
        .success { color: #3c763d; background: #dff0d8; padding: 10px; margin: 10px 0; border: 1px solid #d6e9c6; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ccc; }
        h2 { color: #333; }
        pre { background: #f4f4f4; padding: 10px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Book Display Page - Verification Report</h1>";

// 1. Check if view file exists
echo "<div class='test-section'><h2>1. View Template Check</h2>";
$viewPath = 'app/Views/books/display.php';
if (file_exists($viewPath)) {
    $success[] = "View file exists: $viewPath";
    echo "<div class='success'>✓ View file exists</div>";
    
    // Check for required HTML IDs
    $viewContent = file_get_contents($viewPath);
    $requiredIds = [
        'gridViewBtn', 'listViewBtn',
        'paginationTop', 'paginationBottom',
        'prevBtnTop', 'nextBtnTop', 'prevBtnBottom', 'nextBtnBottom',
        'pageInfoTop', 'pageInfoBottom',
        'pageButtonsTop', 'pageButtonsBottom',
        'gridContainer', 'listContainer',
        'loadingIndicator', 'errorMessage'
    ];
    
    foreach ($requiredIds as $id) {
        if (strpos($viewContent, "id=\"$id\"") !== false) {
            echo "<div class='success'>✓ HTML ID found: $id</div>";
        } else {
            $errors[] = "Missing HTML ID in view: $id";
            echo "<div class='error'>✗ Missing HTML ID: $id</div>";
        }
    }
} else {
    $errors[] = "View file not found: $viewPath";
    echo "<div class='error'>✗ View file not found</div>";
}
echo "</div>";

// 2. Check JavaScript file
echo "<div class='test-section'><h2>2. JavaScript File Check</h2>";
$jsPath = 'public/js/book-display.js';
if (file_exists($jsPath)) {
    $success[] = "JavaScript file exists: $jsPath";
    echo "<div class='success'>✓ JavaScript file exists</div>";
    
    $jsContent = file_get_contents($jsPath);
    
    // Check for required methods
    $requiredMethods = [
        'init', 'setupEventListeners', 'switchView', 'loadBooks',
        'renderGridView', 'renderListView', 'updatePagination', 'updatePaginationUI',
        'previousPage', 'nextPage', 'escapeHtml'
    ];
    
    foreach ($requiredMethods as $method) {
        if (strpos($jsContent, "$method:") !== false || strpos($jsContent, "$method = function") !== false) {
            echo "<div class='success'>✓ Method found: $method</div>";
        } else {
            $warnings[] = "Method may be missing: $method";
            echo "<div class='warning'>⚠ Method may be missing: $method</div>";
        }
    }
    
    // Check for correct element ID references
    $elementIds = [
        'prevBtnTop', 'nextBtnTop', 'prevBtnBottom', 'nextBtnBottom',
        'paginationTop', 'paginationBottom',
        'pageInfoTop', 'pageInfoBottom',
        'pageButtonsTop', 'pageButtonsBottom'
    ];
    
    foreach ($elementIds as $id) {
        if (strpos($jsContent, "getElementById('$id')") !== false) {
            echo "<div class='success'>✓ JavaScript references: $id</div>";
        } else {
            $errors[] = "JavaScript doesn't reference ID: $id";
            echo "<div class='error'>✗ JavaScript missing reference: $id</div>";
        }
    }
} else {
    $errors[] = "JavaScript file not found: $jsPath";
    echo "<div class='error'>✗ JavaScript file not found</div>";
}
echo "</div>";

// 3. Check CSS file
echo "<div class='test-section'><h2>3. CSS File Check</h2>";
$cssPath = 'public/book-display.css';
if (file_exists($cssPath)) {
    $success[] = "CSS file exists: $cssPath";
    echo "<div class='success'>✓ CSS file exists</div>";
    
    $cssContent = file_get_contents($cssPath);
    
    // Check for CSS selectors
    $cssSelectors = [
        '#paginationTop' => 'Top pagination container',
        '#paginationBottom' => 'Bottom pagination container',
        '#pageButtonsTop' => 'Top page buttons',
        '#pageButtonsBottom' => 'Bottom page buttons',
        '.book-card' => 'Book card styles',
        '.list-item' => 'List item styles'
    ];
    
    foreach ($cssSelectors as $selector => $desc) {
        if (strpos($cssContent, $selector) !== false) {
            echo "<div class='success'>✓ CSS selector found: $selector ($desc)</div>";
        } else {
            $warnings[] = "CSS selector missing: $selector";
            echo "<div class='warning'>⚠ CSS selector missing: $selector</div>";
        }
    }
} else {
    $errors[] = "CSS file not found: $cssPath";
    echo "<div class='error'>✗ CSS file not found</div>";
}
echo "</div>";

// 4. Check Controller
echo "<div class='test-section'><h2>4. Controller Check</h2>";
$controllerPath = 'app/Controllers/BookPage.php';
if (file_exists($controllerPath)) {
    $success[] = "Controller file exists: $controllerPath";
    echo "<div class='success'>✓ Controller file exists</div>";
    
    $controllerContent = file_get_contents($controllerPath);
    if (strpos($controllerContent, 'public function display()') !== false) {
        echo "<div class='success'>✓ display() method found</div>";
    } else {
        $errors[] = "display() method not found in controller";
        echo "<div class='error'>✗ display() method not found</div>";
    }
} else {
    $errors[] = "Controller file not found: $controllerPath";
    echo "<div class='error'>✗ Controller not found</div>";
}
echo "</div>";

// 5. Check Routes
echo "<div class='test-section'><h2>5. Routes Check</h2>";
$routesPath = 'app/Config/Routes.php';
if (file_exists($routesPath)) {
    $success[] = "Routes file exists: $routesPath";
    echo "<div class='success'>✓ Routes file exists</div>";
    
    $routesContent = file_get_contents($routesPath);
    if (strpos($routesContent, "books/display") !== false) {
        echo "<div class='success'>✓ Display route configured</div>";
    } else {
        $errors[] = "Display route not configured";
        echo "<div class='error'>✗ Display route not found</div>";
    }
} else {
    $errors[] = "Routes file not found: $routesPath";
    echo "<div class='error'>✗ Routes file not found</div>";
}
echo "</div>";

// Summary
echo "<div class='test-section'><h2>Summary</h2>";
echo "<p><strong>Successful checks:</strong> " . count($success) . "</p>";
echo "<p><strong>Warnings:</strong> " . count($warnings) . "</p>";
echo "<p><strong>Errors:</strong> " . count($errors) . "</p>";

if (count($errors) === 0) {
    echo "<div class='success'><strong>✓ All verifications passed!</strong></div>";
} else {
    echo "<div class='error'><strong>✗ There are errors that need to be fixed:</strong>";
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul></div>";
}

if (count($warnings) > 0) {
    echo "<div class='warning'><strong>Warnings:</strong>";
    echo "<ul>";
    foreach ($warnings as $warning) {
        echo "<li>$warning</li>";
    }
    echo "</ul></div>";
}

echo "</div>";

// Display functionality guide
echo "<div class='test-section'><h2>Functionality Guide</h2>";
echo "<p><strong>Access the page at:</strong> <code>/books/display</code></p>";
echo "<p><strong>Features:</strong></p>";
echo "<ul>";
echo "<li>Grid view (default) with responsive columns</li>";
echo "<li>List view toggle</li>";
echo "<li>Pagination controls at top and bottom</li>";
echo "<li>20 books per page</li>";
echo "<li>Page navigation buttons and number links</li>";
echo "<li>Previous/Next buttons with disabled state</li>";
echo "</ul>";
echo "</div>";

echo "</body>
</html>";
?>

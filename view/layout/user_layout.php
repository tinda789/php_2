<?php include __DIR__ . '/header.php'; ?>
<div class="container main-content">
    <?php
    if (isset($view_file)) {
        if (isset($product)) extract(['product' => $product], EXTR_SKIP);
        if (isset($reviews)) extract(['reviews' => $reviews], EXTR_SKIP);
        include $view_file;
    }
    ?>
</div>
<?php include __DIR__ . '/footer.php'; ?> 
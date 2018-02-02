<section class="ct" id="<?php echo $pagesslug; ?>">
    <div class="container">
        <?php echo str_replace(array("{cache}","{ver}"), array($cache,$ver), $content);?>
    </div>
</section>
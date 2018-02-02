<section class="main" id="<?php echo $pagesslug; ?>">
    <div class="container-main">
        <div class="container">
            <div class="concorra">
                <?php echo str_replace(array("{cache}","{ver}"), array($cache,$ver), $content);?>
            </div>
        </div>
    </div>
</section>

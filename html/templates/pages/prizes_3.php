<div class="container allaround">
    <div id="prizes_container">
        <div class="container">
        <?php if (isset($title_tag)) {echo '<h1>' . $title_tag . '</h1>';} ?>
        <?php echo str_replace(array("{cache}","{ver}"), array($cache,$ver), $content);?>
        </div>
    </div>
</div>

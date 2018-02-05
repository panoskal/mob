<div class="container allaround">
    <div id="terms_container">
        <div class="container">
        <?php if (isset($title_tag)) {echo '<h1>' . $title_tag . '</h1>';} ?>
        <div class="mainclass"><div class="scrollable"><?php echo str_replace(array("{cache}","{ver}"), array($cache,$ver), $content);?></div></div>
        </div>
    </div>
</div>

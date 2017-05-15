<div id="sb-site" class="m-container">
    <div class="entry-content">
        <div class="mob-listEvent ">
            <article class="box-detail" itemscope="" itemtype="http://schema.org/Article">
                <div class="dataBody" itemprop="articleBody">
                    <?php if (!empty($articles)) {
                        foreach($articles as $article) {
                    ?>
                        <div class="box-block">
                            <h3 class="rs block-titles">
                                <?php echo $article['Article']['title']; ?>
                            </h3>
                            <div class="block-nd">
                                <?php echo $article['Article']['parsed_body']; ?>
                            </div>
                        </div>
                    <?php }} ?>
                </div>
            </article>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $(".box-block .block-titles").on('click', function() {
            $(this).closest('.box-block ').toggleClass('active')
        });
    });
</script>
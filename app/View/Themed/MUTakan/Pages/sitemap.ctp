<?php $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://'; ?>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>';?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc><?php echo $protocol.$_SERVER['SERVER_NAME']; ?>/landing</loc>
        <lastmod><?php echo date("Y-m-d"); ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>1</priority>
    </url>
    <url>
        <loc><?php echo $protocol.$_SERVER['SERVER_NAME']; ?>/home</loc>
        <lastmod><?php echo date("Y-m-d"); ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>1</priority>
    </url>
    <url>
        <loc><?php echo $protocol.$_SERVER['SERVER_NAME']; ?>/tin-tuc-su-kien</loc>
        <lastmod><?php echo date("Y-m-d"); ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
<?php
    $arr_category = array();
    if(!empty($all_categories)){
        foreach($all_categories as $category){
            switch ($category['Category']['slug']) {
                case 'faq':
                    $arr_category[$category['Category']['slug']] = "cau-hoi-thuong-gap";
                    break;
                case 'events':
                    $arr_category[$category['Category']['slug']] = "su-kien";
                    break;
                case 'news':
                    $arr_category[$category['Category']['slug']] = "tin-tuc";
                    break;
                case 'features':
                    $arr_category[$category['Category']['slug']] = "dac-sac";
                    break;
                case 'guides':
                    $arr_category[$category['Category']['slug']] = "huong-dan";
                    break;
                case 'hero':
                    $arr_category[$category['Category']['slug']] = "danh-sach-tuong";
                    break;
                
                default:
                    $arr_category[$category['Category']['slug']] = $category['Category']['slug'];
                    break;
            }
?>
    <url>
        <loc><?php echo $protocol.$_SERVER['SERVER_NAME'].'/'.$arr_category[$category['Category']['slug']]; ?></loc>
        <lastmod><?php echo date("Y-m-d"); ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
<?php  
        }
    }
    if(!empty($all_article)){
        foreach($all_article as $article){
?>
    <url>
        <loc><?php echo $protocol.$_SERVER['SERVER_NAME'].'/'.$arr_category[$article['Category']['slug']].'/'.$article['Article']['slug']; ?></loc>
        <lastmod><?php echo $this->Time->format("Y-m-d", $article['Article']['modified']); ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
<?php  
        }
    }
?>
</urlset>

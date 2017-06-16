<?php
echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";

?>
<urlset
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

    <url>
        <loc><?php echo site_url()?></loc>
        <lastmod><?php echo $date?></lastmod>
        <changefreq>daily</changefreq>
        <priority>1.00</priority>
    </url>

    <?php foreach($tablename as $row){?>
        <url>
            <loc><?php echo site_url($row)?></loc>
            <lastmod><?php echo $date?></lastmod>
            <changefreq>daily</changefreq>
            <priority>0.80</priority>
        </url>
        <?php foreach($this->data['category'.$row] as $cat){?>
            <url>
                <loc><?php echo site_url($cat->_url)?></loc>
                <lastmod><?php echo $date?></lastmod>
                <changefreq>daily</changefreq>
                <priority><?php echo $cat->sm?></priority>
            </url>
        <?php }
    }
    foreach($tablename as $row){
        foreach(${$row} as $table)
        {
            ?>
            <url>
                <loc><?php echo $table->_url_view?></loc>
                <lastmod><?php echo $date?></lastmod>
                <changefreq>daily</changefreq>
                <priority><?php echo $table->sm?></priority>
            </url>
        <?php }}?>
</urlset>
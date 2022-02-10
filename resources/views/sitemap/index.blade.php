<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ route('sitemap_static') }}</loc>
    </url>

    <url>
        <loc>{{ route('sitemap_items') }}</loc>
    </url>

    <url>
        <loc>{{ route('sitemap_categories') }}</loc>
    </url>

    <url>
        <loc>{{ route('sitemap_blog') }}</loc>
    </url>

</urlset>


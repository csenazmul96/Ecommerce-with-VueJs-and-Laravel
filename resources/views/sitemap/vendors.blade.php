<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($vendors as $vendor)
        <url>
            <loc>{{ route('vendor_or_parent_category', ['text' => changeSpecialChar($vendor->company_name)]) }}</loc>
            <lastmod>{{ $vendor->updated_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.6</priority>
        </url>
    @endforeach
</urlset>
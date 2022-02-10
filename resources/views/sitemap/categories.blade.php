<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($categories as $category)
        <url>
            <loc>{{ \request()->getHttpHost() }}/{{$category->slug}}</loc>
            <lastmod>{{ $category->updated_at->tz('UTC')->toAtomString() }}</lastmod>
        </url>

        @if (sizeof($category->subCategories) > 0)
            @foreach ($category->subCategories as $sub)
                <url>
                    <loc>{{ \request()->getHttpHost() }}/{{$category->slug}}/{{$sub->slug}}</loc>
                    <lastmod>{{ $sub->updated_at->tz('UTC')->toAtomString() }}</lastmod>
                </url>
                @if(sizeof($sub->thirdcategory)>0)
                    @foreach ($sub->thirdcategory as $third)
                        <url>
                            <loc>{{ \request()->getHttpHost() }}/{{$category->slug}}/{{$sub->slug}}/{{$third->slug}}</loc>
                            <lastmod>{{ $third->updated_at->tz('UTC')->toAtomString() }}</lastmod>
                        </url>
                    @endforeach
                @endif
            @endforeach
        @endif
    @endforeach
</urlset>

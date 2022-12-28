<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @if (isset($categories) && !($categories->isEmpty()))
        @foreach ($categories as $category)
            <url>
                <name>{{$category->name}}</name>
                <loc>{{ url('/') }}/collections/{{ $category->slug }}</loc>
                <lastmod>{{ $category->created_at }}</lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.8</priority>
            </url>
        @endforeach
    @endif
    
    @if (isset($products) && !empty($products))
        @foreach ($products as $product)
            <url>
                <name>{{$product->name}}</name>
                <loc>{{ url('/') }}/products/{{ $product->slug }}</loc>
                <lastmod>{{ $product->created_at }}</lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.8</priority>
                
            </url>
        @endforeach                     
    @endif
</urlset>
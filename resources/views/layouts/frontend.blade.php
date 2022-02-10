<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="icon" href="{{asset('/images/favicon.ico')}}" type="image/x-icon">
    <meta name="p:domain_verify" content="a1475510e92df7084c014573319feff8"/>
    <meta name="robots" content="index, follow" />
    <meta name="robots" content="noodp,noydir" />
    <link rel="preload" as="style" onload="this.rel='stylesheet'" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    @foreach($metas as $meta)
        @if(isset($meta['name']) && $meta['name'] == 'title' )
            <title>{{$meta['content']}}</title>
        @else
            <meta @foreach($meta as $key => $value){{ $key }}="{{ $value }}"@endforeach>
        @endif
    @endforeach
    <link rel="preload" as="style" onload="this.rel='stylesheet'" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css">
    <link rel="preload" as="style" onload="this.rel='stylesheet'" href="https://cdn.lineicons.com/2.0/LineIcons.css">
    <link rel="preload" as="style" onload="this.rel='stylesheet'" href="{{asset('/themes/front')}}/fonts/stylesheet.css">
    <link rel="preload" as="style" onload="this.rel='stylesheet'" href="{{ asset('/themes/front/css/froala_editor.pkgd.css') }}">
    <link rel="preload" as="style" onload="this.rel='stylesheet'" href="{{ asset('css/vue-slick-carousel.css') }}">
    <link rel="preload" as="style" onload="this.rel='stylesheet'" href="{{ asset('css/vue-slick-carousel-theme.css') }}">
    <link rel="preload" as="style" onload="this.rel='stylesheet'" href="{{ asset('css/owl.carousel.css') }}">
    <link rel="preload" as="style" onload="this.rel='stylesheet'" href="{{ asset('css/video-js.css') }}">
    <link rel="preload" as="style" onload="this.rel='stylesheet'" href="{{ asset('css/owl.theme.default.css') }}">
    <link rel="preload" as="style" onload="this.rel='stylesheet'" href="{{ asset('css/sweetalert2.min.css') }}">
    <link rel="preload" as="style" onload="this.rel='stylesheet'" href="{{ asset('css/vue-loading.css') }}">
    <link rel="preload" as="style" onload="this.rel='stylesheet'" href="{{ mix('/css/front.css') }}">
    <link rel="preload" as="style" onload="this.rel='stylesheet'" href="{{ asset('/themes/front/css/custom.css') }}">
    <meta name="facebook-domain-verification" content="v2thf7wf3b2xs3yv3c9sqhfm8buu2g" />

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-NTCMXJL9R4"></script>
    <script async>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-NTCMXJL9R4');
    </script>
    <!-- Google Tag Manager -->
    <script async>
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-M55Z5J2');
    </script>
    <!-- End Google Tag Manager -->

    @if($script)
        <script type="application/ld+json" async>
            {
              "@context": "https://schema.org/",
              "@type": "Product",
              "name": "{{$script->name}} Gel Polish",
              "image": ["{{ count($script->images) > 0 ? Storage::url($script->images[0]->compressed_image_path) : null}}"],
              "description": "{{strip_tags($script->details)}}",

              "offers": {
                "@type": "Offer",
                "url": "{{$script->url}}",
                "priceCurrency": "USD",
                "price": "{{$script->price}}",
                "itemCondition": "https://schema.org/NewCondition",
                "availability": "https://schema.org/InStock"
              }
            }
        </script>
    @endif
    <script type="json" async>
        {
          "@context": "http://schema.org",
          "@type": "WebSite",
          "url": "https://shophologram.com/",
          "potentialAction": {
            "@type": "SearchAction",
            "target": "https://shophologram.com/search?s={search_term_string}",
            "query-input": "required name=search_term_string"
          }
        },
        {
          "@context": "http://schema.org",
          "@type": "Organization",
          "address": {
            "@type": "PostalAddress",
            "addressLocality": "Los Angeles,",
            "postalCode": "CA 90007",
            "streetAddress": "3761 S Hill St #1",
            "addressRegion": "California"
          }
        }

    </script>

    <!-- Facebook Pixel Code -->
    <script async>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '207004984713247');
        fbq('track', 'PageView');
        fbq('track', 'ViewContent');
    </script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=207004984713247&ev=PageView&noscript=1"/></noscript>
    <!-- End Facebook Pixel Code -->

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-200775444-1">
    </script>
    <script async>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-200775444-1');
    </script>

    <!-- Global site tag (gtag.js) - Google Ads: 315645690 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-315645690"></script>
    <script async>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'AW-315645690');
    </script>


</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M55Z5J2"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->


<div id="lynktoVueApp">
    <frontendlayout></frontendlayout>
</div>
<script>
    window.fbResponse = {};
    // function getFbLoginStatus() {
    // (function(d, s, id) {
    //     var js, fjs = d.getElementsByTagName(s)[0];
    //     if (d.getElementById(id)) return;
    //     js = d.createElement(s); js.id = id;
    //     js.src = "//connect.facebook.net/en_US/sdk.js";
    //     fjs.parentNode.insertBefore(js, fjs);
    // }(document, 'script', 'facebook-jssdk'));
    // window.fbAsyncInit = function() {
    //     FB.init({
    //         appId      : {{ config('services.facebook.client_id') }},
    //         cookie     : true,
    //         xfbml      : true,
    //         version    : 'v2.8'
    //     });
    //     FB.getLoginStatus(function(response) {
    //         window.fbResponse = response;
    // /
    // };
    // }
</script>
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js" defer></script>
<script src="https://apis.google.com/js/api:client.js" defer></script>
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>--}}
{{--<script src="{{asset('/themes/front')}}/js/vendor/bootstrap.js"></script>--}}
<script src="{{asset('/themes/front')}}/js/main.js" defer></script>
<!--<script src="https://www.paypal.com/sdk/js?client-id=AZY1OYQIi8vS8BVPyFOw3wK1JfGldpuIOWcWo20V8uJyMeHQE1lLaWAXCELsWRMb34Wc--eXBZ7Lf38C&disable-funding=credit,card"></script> -->
<!-- <script src="https://apis.google.com/js/api:client.js"></script> -->
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/3.1.1/js/froala_editor.pkgd.min.js" defer></script>--}}
<script src="{{ asset('themes/front/js/jquery.zoom.js') }}" defer></script>
{{--<script src="{{ asset('/js/sweetalert2.min.js') }}" defer></script>--}}
<script src="{{ mix('/js/app.js') }}" async></script>
</body>
</html>

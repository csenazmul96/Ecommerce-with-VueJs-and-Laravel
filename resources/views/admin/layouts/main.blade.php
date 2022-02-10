<?php use App\Enumeration\PageEnumeration; ?>

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <title>{{ config('app.name', 'Lynkto - Admin') }}</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    
    @include('admin.partials.header-assets')    
    @yield('additionalCSS')
    
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&display=swap" rel="stylesheet">

    <script>
        function imgErrHndlHologram(that, source) {
            that.onerror=null; 
            var sourceArray = source.split("/");
            var safariSource = '';
            for (let index = 0; index < sourceArray.length; index++) {
                safariSource += sourceArray[index];
                if (index < sourceArray.length - 1) {
                    safariSource += '/'
                }
                if (index == sourceArray.length - 2) {
                    safariSource += 'sa/'
                }
                
            }
            that.setAttribute('src', safariSource)
        }
    </script>
</head>

<body>
    <div class="side_nav">
        @include('admin.partials.sidebar')
    </div>
    <div id="wrapper">
        @include('admin.partials.header')
        <div id="main_content">
            @if(isset($page_title))
                <div class="main_title">
                    <h1 class="mr_8">{{ $page_title}}</h1>
                </div>
            @endif
            @yield('content')
        </div>
        <div class="footer_area">
            <div class="ly-wrap">
                <div class="ly-row">
                    <div class="ly-12">
                        <div class="footer_copyright text_center">
                            <p>© Copyright <?php echo date('Y');?> by Lynkto.net. All rights reserved. <a href="#">Terms of Use</a> <a href="#">Brand Guideline</a> </p>
                            @if(isset($logo_path))
                                @if ($logo_path != '')
                                    <a href="{{ route('admin_dashboard') }}">
                                        <img src="{{ $logo_path }}" alt="logo" style="width: 200px;">
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal" data-modal="ViewExample">
        <div class="modal_overlay" data-modal-close="ViewExample"></div>
        <div class="modal_inner">
            <div class="modal_wrapper modal_500p">
                <div class="modal_header display_table white_bg no_border pa15 pb_0">
                    <span class="modal_header_title font_16p">This is where your Order Notice will appear on 143Story:</span>
                    <div class="float_right">
                        <span class="close_modal" data-modal-close="ViewExample"></span>
                    </div>
                </div>
                <div class="modal_content pl_15 pr_15">
                    <div class="form_inline">
                        <div class="time_pick"><input id="schedule_time" class="form_global" type="text">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.partials.footer-assets')
    <script type="text/javascript" src="{{ asset('themes/back/js/custom.js') }}"></script>
    @yield('additionalJS')
</body>

</html>
<!DOCTYPE html>
<html>
<head>
    <!-- META -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- FAVICON -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- SEO -->
    @if (isset($SEO['html']))
        @foreach ($SEO['html'] as $meta)
            @if ($meta)
                {!! $meta !!}
            @endif
        @endforeach
    @endif
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- CONFIG -->
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    @yield('critical')

    <script>
        var App = App || {
            urls: {
                basePath: "{{ trim(asset('assets'), '/') }}/"
            }
        }
    </script>

    {!! no_captcha('v2')->script() !!}

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-K64HVB');</script>
    <!-- End Google Tag Manager -->
</head>

<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K64HVB" height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    <!-- End Google Tag Manager (noscript) -->

    <div class="hl">
        @include('front.includes.global.header')

        <div class="gr">
            @include('front.includes.header')

            @yield('content')

            @include('front.includes.footer')

            @include('front.includes.popups')
        </div>

        @include('front.includes.global.footer')
    </div>

    <link rel="stylesheet" href="{{ asset('assets/css/main.min.css') }}?v={{ md5(config('sentry.release')) }}">
    <script defer type="text/javascript" src="{{ asset('assets/js/main.min.js') }}?v={{ md5(config('sentry.release')) }}"></script>
    </body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="출석체크를 해보세요!">
    <meta property="og:type" content="website">
    <meta property="og:title" content="체크메이트">
    <meta property="og:description" content="출석체크를 해보세요">
    <meta property="og:image" content="https://ckme.live/img/main.png">
    <meta property="og:url" content="https://ckme.live">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    @if (Auth::check())
    <meta name="user-name" content="{{ Auth::user()->name }}">
    <meta name="user-sid" content="{{ Auth::user()->sid }}">
    <meta name="user-class" content="{{ Auth::user()->class }}">
    <meta name="user-phone" content="{{ Auth::user()->phone_number }}">
    <meta name="user-position" content="{{ Auth::user()->position }}">
    <meta name="user-photo" content="{{ Auth::user()->profile_photo_path }}">
    <meta name="user-id" content="{{ Auth::user()->id }}">
    <meta name="user-current_team_id" content="{{ Auth::user()->current_team_id }}">
    @endif
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="manifest" href="/manifest.json">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" type='image/x-icon'>
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <!-- Scripts -->
    @routes
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}" defer></script>
    <script type="module">
        import 'https://cdn.jsdelivr.net/npm/@pwabuilder/pwaupdate';
        const el = document.createElement('pwa-update');
        document.body.appendChild(el);
     </script>
</head>

<body class="font-sans antialiased">
    @inertia

    {{-- @env ('local')
            <script src="http://localhost:3000/browser-sync/browser-sync-client.js"></script>
        @endenv --}}
</body>

</html>

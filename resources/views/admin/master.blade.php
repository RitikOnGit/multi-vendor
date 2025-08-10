<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'My Laravel App')</title>
    <!-- Add CSS/JS here -->
      <link rel="stylesheet" href="{{ asset('admin/css/backend-plugin.min.css')}}">
      <link rel="stylesheet" href="{{ asset('admin/css/backende209.css?v=1.0.0')}}">
      <link rel="stylesheet" href="{{ asset('admin/vendor/remixicon/fonts/remixicon.css')}}">

      <!-- <link rel="stylesheet" href="{{ asset('admin/vendor/tui-calendar/tui-calendar/dist/tui-calendar.css')}}">
      <link rel="stylesheet" href="{{ asset('admin/vendor/tui-calendar/tui-date-picker/dist/tui-date-picker.css')}}">
      <link rel="stylesheet" href="{{ asset('admin/vendor/tui-calendar/tui-time-picker/dist/tui-time-picker.css')}}"> -->
</head>
<body>

    {{-- Include Header --}}
    @include('admin.header')

    <div class="">
        @yield('content')
    </div>

    {{-- Include Footer --}}
    @include('admin.footer')

</body>
</html>

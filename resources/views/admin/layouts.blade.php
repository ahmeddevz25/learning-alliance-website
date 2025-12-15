<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free">
@include('admin.layout.heads')

<body>
    @include('admin.layout.header')
    @include('admin.layout.sidebar')
    @yield('content')
    @include('admin.layout.footer')
    @include('admin.layout.script')
</body>

</html>

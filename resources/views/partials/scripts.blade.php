<!-- Font Awesome -->
<script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>

@if (Auth::check())
    @if (Auth::user()->hasPermissionTo('access admin cp', 'web'))
        <!-- Print permssions for User, will only be used to decide if a user can see and access pages in admin, API still controls actions -->
        <script> var permissions = @json(Auth::user()->getAllPermissions()->pluck('name'));</script>

        <!-- Bootstrap and necessary plugins -->
        <script src="{{ asset('js/app.js') }}"></script>
    @else
        <!-- Bootstrap and necessary plugins -->
        <!-- Lite is without the admin content -->
        <script src="{{ asset('js/app-lite.js') }}"></script>
    @endif
@else
    <!-- Bootstrap and necessary plugins -->
     <!-- Lite is without the admin content -->
    <script src="{{ asset('js/app-lite.js') }}"></script>
@endif

<!-- Left Sidebar -->
<aside id="leftsidebar" class="top-sidebar">
    
    <!-- Menu -->
    <div class="menu">

        <a href="javascript:void(0);" class="bars"></a>
        {!! $mainMenu->render() !!}

    </div>
    <!-- #Menu -->
    <!-- Footer -->
    <div class="legal">
        <i title="@lang('core::core.minify_sidebar')" id="minify-sidebar" class="material-icons">keyboard_arrow_left</i>
        <div class="version">
            <b>@lang('vaance.version'): {{ config('vaance.version') }}</b>
        </div>
    </div>
    <!-- #Footer -->
</aside>
<!-- #END# Left Sidebar -->
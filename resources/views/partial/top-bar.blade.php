<?php
?>

<!-- Top Bar -->
<nav class="navbar">

    <div class="container-fluid">
        <div class="navbar-header">


            <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse"
               data-target="#navbar-collapse" aria-expanded="false"></a>
            <a href="javascript:void(0);" class="bars"></a>
            <a class="navbar-brand" href="{{ url('/') }}">

                @if(\Krucas\Settings\Facades\Settings::get(\Modules\Platform\Core\Helper\SettingsHelper::S_DISPLAY_SHOW_LOGO_IN_APPLICATION))
                    {!!   \Modules\Platform\Core\Helper\SettingsHelper::displayLogo() !!}
                @else
                    {{ \Krucas\Settings\Facades\Settings::get(\Modules\Platform\Core\Helper\SettingsHelper::S_DISPLAY_APPLICATION_NAME, config('app.name'))}}
                @endif

            </a>

        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse">


            <ul class="nav navbar-nav navbar-right">
            @if(config('vaance.global_search'))
                <!-- Call Search -->
                    <li><a href="javascript:void(0);" class="js-search" data-close="true"><i class="material-icons">search</i></a>
                    </li>
                @endif


                <li class="dropdown">
                    <a id="top-bar-notifications" href="javascript:void(0);" class="dropdown-toggle"
                       data-toggle="dropdown" role="button" aria-expanded="true">
                        <i class="material-icons">notifications</i>
                        <span id="top_bar_notifications_count"
                              class="label-count bg-red"> {{ Auth::user()->unreadNotifications()->count() }}</span>
                    </a>
                    <ul id="notifications_dropdown" class="dropdown-menu">
                        <li class="header bg-red">@lang('core::core.notifications')</li>
                        <li class="body">
                            <ul id="top-bar-notifications-menu" class="menu">

                                @include('notifications::top-bar')

                            </ul>
                        </li>
                        <li class="footer">
                            <a href="{{ route('notifications.index') }}"
                               class=" waves-effect waves-block">@lang('notifications::notifications.view_all_notifications')</a>
                        </li>
                    </ul>
                </li>


                <!-- #END# Tasks -->
                <li class="pull-right"><a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i
                                class="material-icons">more_vert</i></a></li>
            </ul>
        </div>
    </div>
</nav>
<!-- #Top Bar -->

@extends('layouts.app')

@section('content')

    <div class="block-header">
        <h2>@lang('settings::settings.module')</h2>
    </div>

    <div class="row">

        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 no-vert-padding" >

            @include('settings::partial.menu')

        </div>

        <div  class="col-lg-9 col-md-9 col-sm-9 col-xs-12 no-vert-padding">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">

                        <div class="body">

                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                @widget('Modules\Platform\Settings\Widgets\UserCountWidget',['count_active' => true,'color'=>'bg-light-green'])
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                @widget('Modules\Platform\Settings\Widgets\UserCountWidget',['count_active' => false,'widget_title'=>'inactive','color'=>'bg-deep-orange'])
                            </div>

                        </div>
                    </div>
                </div>
            </div>
    </div>

@endsection

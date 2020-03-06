@extends('layouts.app')

@section('content')

<div class="row">
        @widget('Modules\Platform\Core\Widgets\AutoGroupDictWidget',
        [
        'coll_class' => 'col-lg-3 col-md-3 col-sm-4 col-xs-4',
        'dict' =>'Modules\PolizzaCar\Entities\PolizzaCarStatus',
        'moduleEntity' => 'Modules\PolizzaCar\Entities\PolizzaCar',
        'moduleTable' =>'polizza_car',
        'icon_type' => 'material',
        'groupBy' => 'status_id',
        'dataTableToFilter' => 'PolizzaCarDatatable'
        ]
        )
    </div>

        <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                        <div class="card">
                                <div class="header">
                                        <h2>
                                            <div class="header-buttons">
                                                @can('procurement.create')
                                                    <a class="btn btn-primary btn-create btn-crud" href="{{ route('polizzacar.polizzacar.create') }}">@lang('PolizzaCar::PolizzaCar.create')</a>
                                                @endcan
                                                <a data-toggle="modal" data-target="#genericModal" class="btn btn-primary bg-pink btn-create btn-crud" href="#">@lang('PolizzaCar::PolizzaCar.fast_quote')</a>
                                            </div>
                                            <div class="header-text">
                                                    @lang('dashboard::dashboard.widgets.polizzacar')
                                                    <small>@lang('dashboard::dashboard.widgets.polizzacar')</small>
                                            </div> 
                                        </h2>
                                    </div>
                            <div class="body">
                                <div class="table-responsive col-lg-12 col-md-12 col-sm-12">
                                    {{ $polizzaCarDatatable->table(['width' => '100%']) }}
                                </div>
                            </div>
                        </div>
                    </div>

            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                <div class="card">
                    <div class="header">
                        <h2>
                            <div class="header-text">
                                Assistenza commerciale
                            </div> 
                        </h2>
                    </div>
                <div class="body">
			        <ul class="list-group list-menu">
                    <li><p><i class="material-icons">phone</i><span class="icon-name">02 87.280.758</span></p></li>
                    <li><p><i class="material-icons">timelapse</i> <span class="icon-name"><span>dal Lunedì al Venerdì</span></p></li>
                    <li><p><i class="material-icons">schedule</i> <span class="icon-name">9.00-13.00 / 14.00-18.00</span> </p></li>
                    <li><p><i class="material-icons">mail_outline</i><a class="icon-name" href="mailto:a.vacca@vaance.com">a.vacca@vaance.com</a></p></li>
                    </ul>
                </div>
                </div>
                <div class="card">
                        <div class="header">
                            <h2>
                                <div class="header-text">
                                    Assistenza tecnica
                                </div> 
                            </h2>
                        </div>
                    <div class="body">
                        <ul class="list-group list-menu">
				    <li><p><i class="material-icons">phonelink_setup</i><span class="icon-name">02 87.280.718</span></p></li>
					<li><p><i class="material-icons">timelapse</i> <span class="icon-name">dal Lunedì al Venerdì</span></p></li>
                    <li><p><i class="material-icons">schedule</i> <span class="icon-name">9.00-13.00 / 14.00-18.00</span> </p></li>
                    <li><p><i class="material-icons">mail_outline</i><a class="icon-name" href="mailto:assistenza@vaance.net">Assistenza@vaance.net</a></p></li>
			        </ul>
        </div>
    </div>
    </div>
  </div>

    <div class="block-header">
        <h2>@lang('dashboard::dashboard.widgets.procurements')</h2>
    </div>

    <div class="row">
        

        @widget('Modules\Dashboard\Widgets\CountWidget',['title' =>
        trans('dashboard::dashboard.widgets.count_procurement_with_insurance_number'),'bg_color'=>'bg-green','icon'=>'check','href'=>url('/dashboard/with_in'), 'coll_class' => 'col-lg-3 col-md-3 col-sm-6 col-xs-6', 'counter' =>
        $countDaCompletare['with_insurance_number']])
       
       @widget('Modules\Dashboard\Widgets\CountWidget',['title' =>
        trans('dashboard::dashboard.widgets.count_procurement_without_insurance_number'),'bg_color'=>'bg-red','icon'=>'cancel','href'=>url('/dashboard/without_in'), 'coll_class' => 'col-lg-3 col-md-3 col-sm-6 col-xs-6', 'counter' =>
        $countDaCompletare['without_insurance_number']])
       
    </div>

    <div class="row dashboard-row">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    <div class="header">
                            <h2>
                                <div class="header-buttons">
                                    @can('procurement.create')
                                        <a class="btn btn-primary btn-create btn-crud" href="{{ route('polizzacar.polizzacar.create') }}">@lang('PolizzaCar::PolizzaCar.create')</a>
                                    @endcan
                                    <a class="btn btn-primary btn-create btn-modal" href="{{ route('polizzacar.polizzacar.create') }}">@lang('PolizzaCar::PolizzaCar.fast_quote')</a>
                                </div>
                                <div class="header-text">
                                        @lang('dashboard::dashboard.widgets.procurements')
                                        <small>@lang('dashboard::dashboard.widgets.procurements')</small>
                                </div> 
                            </h2>
                        </div>
                <div class="body">
                    <div class="table-responsive col-lg-12 col-md-12 col-sm-12">
                        
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')

    <script src="{!! Module::asset('dashboard:js/VAANCE_Dashboard.js') !!}"></script>

@endpush

@push('scripts')
{!! $polizzaCarDatatable->scripts() !!}
@endpush

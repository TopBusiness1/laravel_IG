<?php

namespace Modules\Dashboard\Http\Controllers;

use Modules\Platform\Core\Http\Controllers\AppBaseController;
use Modules\Dashboard\Datatables\PolizzaCarDatatable;
use Modules\PolizzaCar\Entities\PolizzaCar;
use Modules\PolizzaCar\Service\PolizzaCarService;
use Illuminate\Http\Request;

/**
 * Class DashboardController
 * @package Modules\Dashboard\Http\Controllers
 */
class DashboardController extends AppBaseController
{

    /**
     * Dashboard
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    // public function index(Request $request)
    public function index($status_id=0)
    {
        // $draw = $request->get('draw', null);
        
        $view = view('dashboard::index');

        $polizzeService = \App::make(PolizzaCarService::class);

        $countDaCompletare = $polizzeService->countByStatus(PolizzaCar::STATUS_SAVED);

        $view->with('countDaCompletare', $countDaCompletare);
        
        $polizzaCarDatatable = new PolizzaCarDatatable();
        $polizzaCarDatatable->setTableId('PolizzaCarDatatable');
        // $polizzaCarDatatable->setAjaxSource(route('dashboard.polizzacar'));
        $polizzaCarDatatable->setAjaxSource(route('dashboard.polizzacar', ['status_id'=>$status_id]));

        $view->with('polizzaCarDatatable', $polizzaCarDatatable->html());

        return $view;
    }

    // public function getPolizzacar(PolizzaCarDatatable $polizzaCarDatatable) {
    public function getPolizzacar($status_id) {
        $polizzaCarDatatable = new PolizzaCarDatatable($status_id);
        return $polizzaCarDatatable->render('core::crud.module.modal-datatable');
    }

}

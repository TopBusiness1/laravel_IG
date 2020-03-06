<?php

namespace Modules\Platform\User\Datatables;

use Modules\Platform\Core\Datatable\PlatformDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class RolesDatatable
 * @package Modules\Platform\User\Datatables
 */
class RolesDatatable extends PlatformDataTable
{
    const SHOW_URL_ROUTE = 'settings.roles.show';

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);


        $this->applyLinks($dataTable, self::SHOW_URL_ROUTE);

        $dataTable->filterColumn('created_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('created_at', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('updated_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('updated_at', array($dates[0], $dates[1]));
            }
        });

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Role $model)
    {
        return $model->newQuery()->select();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())

            ->setTableAttribute('class', 'table table-hover')
            ->parameters([
                'dom' => 'lBfrtip',
                'responsive' => false,
                'stateSave' => true,
                'columnFilters' => [
                    [
                        'column_number' => 0,
                        'filter_type' => 'text'
                    ],
                    [
                        'column_number' => 1,
                        'filter_type' => 'text'
                    ],
                    [
                        'column_number' => 2,
                        'filter_type' => 'text',
                    ],
                    [
                        'column_number' => 3,
                        'filter_type' => 'vaance_date_range_picker',

                    ],
                    [
                        'column_number' => 4,
                        'filter_type' => 'vaance_date_range_picker',
                    ]
                ],
                'buttons' => DataTableHelper::buttons(),
                'regexp' => true

            ]);
    }

    /**
     * @return array
     */
    protected function getColumns()
    {
        return
            [
                'display_name' => ['data' => 'display_name', 'title' => trans('user::roles.table.display_name'), 'data_type' => 'text'],
                'name' => ['data' => 'name', 'title' => trans('user::roles.table.name'), 'data_type' => 'text'],
                'guard_name' => ['data' => 'guard_name', 'title' => trans('user::roles.table.guard_name'), 'data_type' => 'text'],
                'created_at' => ['data' => 'created_at', 'title' => trans('user::roles.table.created_at'), 'data_type' => 'datetime'],
                'updated_at' => ['data' => 'updated_at', 'title' => trans('user::roles.table.updated_at'), 'data_type' => 'datetime']
            ];
    }
}

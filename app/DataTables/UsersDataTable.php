<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('mobile_number', function (User $user) {
                return $user->user_detail->mobile_number ?? '-';
            })
            ->editColumn('plan_expire_date', function (User $user) {
                return date('d-m-Y', strtotime($user->plan_expire_date)) ?? '-';
            })
            ->addColumn('action', function (User $user) {
                return view('users.action', compact('user'));
            })
            ->rawColumns(['mobile_number', 'plan_expire_date', 'action']);
    }

    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()->where('created_by', '=', Auth::user()->creatorId())->where('type', '!=', 'advocate')->where('type', '!=', 'superAdminEmployee');
    }

    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('users-table')
            ->columns($this->getColumns())
            ->orderBy(1)
            ->language([
                "paginate" => [
                    "previous" => 'Prev'
                ],
                'lengthMenu' => __('Show ') . __("_MENU_") . __(' entries'),
            ]);

        $dataTable->parameters([
            "dom" =>  "
                <'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>
                <'dataTable-container'<'col-sm-12'tr>>
                <'row'<'col-sm-5'i><'col-sm-7'p>>
            ",
            'buttons' => [],
            "drawCallback" => 'function( settings ) {
                var tooltipTriggerList = [].slice.call(
                    document.querySelectorAll("[data-bs-toggle=tooltip]")
                );
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
                var popoverTriggerList = [].slice.call(
                    document.querySelectorAll("[data-bs-toggle=popover]")
                );
                var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                    return new bootstrap.Popover(popoverTriggerEl);
                });
                var toastElList = [].slice.call(document.querySelectorAll(".toast"));
                var toastList = toastElList.map(function (toastEl) {
                    return new bootstrap.Toast(toastEl);
                });
            }'
        ]);

        return $dataTable;
    }

    protected function getColumns(): array
    {
        return [
            Column::make('No')->title(__('#'))->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
            Column::make('name')->title(__('Name')),
            Column::make('email')->title(__('Email')),
            Column::make('mobile_number')->title(__('Phone Number')),
            Column::make('plan_expire_date')->title(__('Due Date')),
            Column::computed('action')->title(__('Action'))
                ->exportable(false)
                ->printable(false)
                ->width(120),
        ];
    }

    protected function filename(): string
    {
        return 'Users_' . date('YmdHis');
    }
}

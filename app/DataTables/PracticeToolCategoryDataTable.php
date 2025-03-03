<?php

namespace App\DataTables;

use App\Models\PracticeToolCategory;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PracticeToolCategoryDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('name', function (PracticeToolCategory $practiceToolCategory) {
                return '<a href="javascript:void(0)" class="btn btn-sm text-line-clamp"
                                data-url="' . route('practice-tool-categories.edit', $practiceToolCategory->id) . '"
                                data-size="md" data-ajax-popup="true"
                                data-title="' . __('Edit Practice Tool Category') . '">
                                ' . htmlspecialchars($practiceToolCategory->name) . '
                            </a>';
            })
            ->addColumn('action', function (PracticeToolCategory $practiceToolCategory) {
                return view('libraries.practice-tool-categories.action', compact('practiceToolCategory'));
            })
            ->rawColumns(['name', 'action']);
    }

    public function query(PracticeToolCategory $model): QueryBuilder
    {
        return $model->newQuery()->where('created_by', Auth::user()->creatorId());
    }

    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('practicetoolcategory-table')
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
            Column::computed('action')->title(__('Action'))
                ->exportable(false)
                ->printable(false)
                ->width(120),
        ];
    }

    protected function filename(): string
    {
        return 'PracticeToolCategory_' . date('YmdHis');
    }
}

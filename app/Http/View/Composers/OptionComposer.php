<?php

namespace App\Http\View\Composers;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OptionComposer
{
    /**
     * Create a new RadioComposer.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $field = $view->field;
        if ($field->dynamic && $field->field_type!='complex') {
            $table = $field->master_table_info['table_name'];
            $valueField = $field->master_table_info['value_field'];
            $keyField = $field->master_table_info['key_field'];
            $options = collect(DB::select("select * from {$table}"))->pluck($valueField, $keyField)->toArray();
        } else {
            $options = $field->option_info;
        }
        $view->with('options', $options);
    }
}

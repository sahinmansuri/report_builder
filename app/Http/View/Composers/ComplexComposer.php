<?php

namespace App\Http\View\Composers;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ComplexComposer
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
        $children = $view->field->children;
        $view->with('children', $children);
    }
}

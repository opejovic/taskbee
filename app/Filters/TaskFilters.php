<?php

namespace taskbee\Filters;

use Illuminate\Support\Facades\Auth;

class TaskFilters extends Filters
{
    /**
     * Filters.
     *
     * @var array
     */
    protected $filters = ['my', 'creator', 'responsibility'];

    /**
     * Filter the tasks by the authenticated user.
     *
     * @return void
     */
    public function my()
    {
        return $this->builder->where('user_responsible', Auth::user()->id);
    }

    /**
     * Filter the tasks by the creator.
     *
     * @param integer $id
     *
     * @return void
     */
    public function creator($id)
    {
        if ($id) { return $this->builder->where('created_by', $id); }
    }

    /**
     * Filter the tasks by the responsible user.
     *
     * @param integer $id
     *
     * @return void
     */
    public function responsibility($id)
    {
        if ($id) { return $this->builder->where('user_responsible', $id); }
    }
}

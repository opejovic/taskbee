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
     */
    public function my() : void
    {
        return $this->builder->where('user_responsible', Auth::user()->id);
    }

    /**
     * Filter the tasks by the creator.
     *
     * @param integer $id
     */
    public function creator($id) : void
    {
        if ($id) { return $this->builder->where('created_by', $id); }
    }

    /**
     * Filter the tasks by the responsible user.
     *
     * @param integer $id
     */
    public function responsibility($id) : void
    {
        if ($id) { return $this->builder->where('user_responsible', $id); }
    }
}

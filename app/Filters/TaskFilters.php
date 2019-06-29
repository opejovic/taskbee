<?php

namespace App\Filters;

use Illuminate\Support\Facades\Auth;

class TaskFilters extends Filters
{
    protected $filters = ['my', 'creator', 'responsibility'];

    /**
     * summary
     *
     * @return void
     * @author 
     */
    public function my()
    {
        return $this->builder->where('user_responsible', Auth::user()->id);
    }

    /**
     * summary
     *
     * @return void
     * @author 
     */
    public function creator($id)
    {
        if ($id) {
            return $this->builder->where('created_by', $id);
        }
    }
    
    /**
     * summary
     *
     * @return void
     * @author 
     */
    public function responsibility($id)
    {
        if ($id) {
            return $this->builder->where('user_responsible', $id);
        }
    }
}

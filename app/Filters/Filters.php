<?php

namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filters
{
    /**
     * summary
     *
     * @var string
     */
    protected $request, $builder;
    protected $filters = [];

    /**
     * Class construct.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * summary
     *
     * @param $builder
     *
     * @return void
     * @author
     */
    public function apply($builder)
    {
        $this->builder = $builder;

        $this->getFilters()->each(function ($value, $filter) {
            if (method_exists($this, $filter)) {
                return $this->$filter($value);
            }
        });

        return $builder;
    }

    /**
     * Return the filters from the request.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getFilters()
    {
        return collect($this->request->only($this->filters));
    }
}

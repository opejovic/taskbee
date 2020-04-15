<?php

namespace taskbee\Filters;

use Illuminate\Http\Request;

abstract class Filters
{
    /**
     * Filters request, and query builder.
     *
     * @var string
     */
    protected $request, $builder;

    /**
     * Defined filters.
     *
     * @var array
     */
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
     * Apply filter to the query builder.
     *
     * @param $builder
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
     */
    public function getFilters() : \Illuminate\Support\Collection
    {
        return collect($this->request->only($this->filters));
    }
}

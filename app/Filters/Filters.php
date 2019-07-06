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
     * summary
     *
     * @return void
     * @author 
     */
	public function __construct(Request $request)
	{
		$this->request = $request;
	}

    /**
     * summary
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
     * summary
     *
     * @return void
     * @author 
     */
    public function getFilters()
    {
        return collect($this->request->only($this->filters));
    }
}
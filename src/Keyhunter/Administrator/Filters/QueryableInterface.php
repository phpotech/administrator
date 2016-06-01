<?php

namespace Keyhunter\Administrator\Filters;

use Illuminate\Database\Eloquent\Builder;

interface QueryableInterface
{
    /**
     * Check if Filter element has a query
     *
     * @return bool
     */
    public function hasQuery();

    /**
     * Execute filter element's query
     * @return Builder|mixed
     * @internal param Builder $query
     */
    public function execQuery();
}
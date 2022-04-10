<?php

namespace uiadmin\core\model;

use Illuminate\Pagination\LengthAwarePaginator as BaseLengthAwarePaginator;
use Illuminate\Support\Str;

class LengthAwarePaginator extends BaseLengthAwarePaginator
{
    /**
     * CustomLengthAwarePaginator constructor.
     *
     * @param BaseLengthAwarePaginator $lengthAwarePaginator
     */
    public function __construct(BaseLengthAwarePaginator $lengthAwarePaginator)
    {
        $this->total = $lengthAwarePaginator->total;
        $this->perPage = $lengthAwarePaginator->perPage;
        $this->lastPage = $lengthAwarePaginator->lastPage;
        $this->path = $lengthAwarePaginator->path;
        $this->currentPage = $lengthAwarePaginator->currentPage;
        $this->items = $lengthAwarePaginator->items;
        $this->path = $lengthAwarePaginator->path;
        $this->query = $lengthAwarePaginator->query;
        $this->fragment = $lengthAwarePaginator->fragment;
        $this->pageName = $lengthAwarePaginator->pageName;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        $convertedArray = [];

        foreach (parent::toArray() as $key => $value) {
            $convertedArray[ Str::camel($key) ] = $value;
        }

        return $convertedArray;
    }

}
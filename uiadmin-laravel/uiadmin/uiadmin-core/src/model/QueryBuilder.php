<?php

namespace uiadmin\core\model;

use Illuminate\Database\Query\Builder as BaseQueryBuilder;
use Illuminate\Support\Str;

class QueryBuilder extends BaseQueryBuilder
{

    /**
     * Add a basic where clause to the query.
     *
     * @param  string|array|\Closure $column
     * @param  string $operator
     * @param  mixed $value
     * @param  string $boolean
     *
     * @return BaseQueryBuilder
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        if (is_string($column)) {
            $column = Str::snake($column);

            return parent::where($column, $operator, $value, $boolean);
        } else if (is_array($column)) {
            $columns = [];

            foreach ($column as $name => $value) {
                $columns[ Str::snake($name) ] = $value;
            }

            return parent::where($columns, $operator, $value, $boolean);
        }  else {
            return parent::where($column, $operator, $value, $boolean);
        }
    }

    /**
     * Add a "where in" clause to the query.
     *
     * @param  string  $column
     * @param  mixed   $values
     * @param  string  $boolean
     * @param  bool    $not
     * @return $this
     */
    public function whereIn($column, $values, $boolean = 'and', $not = false)
    {
        $column = Str::snake($column);

        return parent::whereIn($column, $values, $boolean, $not);
    }

    /**
     * Add a where between statement to the query.
     *
     * @param  string  $column
     * @param  array   $values
     * @param  string  $boolean
     * @param  bool  $not
     * @return $this
     */
    public function whereBetween($column, array $values, $boolean = 'and', $not = false)
    {
        $column = Str::snake($column);

        return parent::whereBetween($column, $values, $boolean, $not);
    }

}

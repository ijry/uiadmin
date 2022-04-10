<?php

namespace uiadmin\core\model;

use Illuminate\Pagination\LengthAwarePaginator as BaseLengthAwarePaginator;
use uiadmin\core\model\QueryBuilder;
use uiadmin\core\model\LengthAwarePaginator;
use Illuminate\Support\Str;
use Illuminate\Support\Array;

/**
 * Class CamelCaseTrait
 * Based on eloquence package
 *
 * @package RestartPackage\Traits
 */
trait CamelCaseTrait
{

    /**
     * Alter eloquent model behaviour so that model attributes can be accessed via camelCase, but more importantly,
     * attributes also get returned as camelCase fields.
     *
     * @var bool
     */
    public $enforceCamelCase = true;

    /**
     * Get a new query builder instance for the connection.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function newBaseQueryBuilder()
    {
        $conn = $this->getConnection();

        $grammar = $conn->getQueryGrammar();

        return new QueryBuilder($conn, $grammar, $conn->getPostProcessor());
    }

    /**
     * Overloads the eloquent setAttribute method to ensure that fields accessed
     * in any case are converted to snake_case, which is the defacto standard
     * for field names in databases.
     *
     * @param string $key
     * @param mixed $value
     */
    public function setAttribute($key, $value)
    {
        parent::setAttribute($this->getSnakeKey($key), $value);
    }

    /**
     * Retrieve a given attribute but allow it to be accessed via alternative case methods (such as camelCase).
     *
     * @param  string $key
     *
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (method_exists($this, $key)) {
            return $this->getRelationValue($key);
        }

        return parent::getAttribute($this->getSnakeKey($key));
    }

    /**
     * Helper for snake casing an attributes inside mutators.
     *
     * @param string $key
     * @param mixed $value
     */
    public function setMutator($key, $value)
    {
        $this->attributes[ $this->getSnakeKey($key) ] = $value;
    }

    /**
     * Return the attributes for the model, converting field casing if necessary.
     *
     * @return array
     */
    public function attributesToArray()
    {
        return $this->toCamelCase(parent::attributesToArray());
    }

    /**
     * Converts the attributes to a camel-case version, if applicable.
     *
     * @return array
     */
    public function getAttributes($keys = [])
    {
        return $this->attributesToArray();
    }

    /**
     * Get the model's relationships, converting field casing if necessary.
     *
     * @return array
     */
    public function relationsToArray()
    {
        return $this->toCamelCase(parent::relationsToArray());
    }

    /**
     * Overloads eloquent's getHidden method to ensure that hidden fields declared
     * in camelCase are actually hidden and not exposed when models are turned
     * into arrays.
     *
     * @return array
     */
    public function getHidden()
    {
        //check which one is attribute and which is relation
        $hiddenAttributes = $hiddenRelations = [];
        foreach ($this->hidden as $hiddenField) {
            if (array_key_exists($hiddenField, $this->relations)) {
                $hiddenRelations[] = $hiddenField;
            } else {
                $hiddenAttributes[] = $hiddenField;
            }
        }

        //snake_case only for the attributes
        return array_merge(array_map('snake_case', $hiddenAttributes), $hiddenRelations);
    }

    /**
     * Overloads the eloquent getDates method to ensure that date field declarations
     * can be made in camelCase but mapped to/from DB in snake_case.
     *
     * @return array
     */
    public function getDates()
    {
        $dates = parent::getDates();

        return array_map('snake_case', $dates);
    }

    /**
     * Converts a given array of attribute keys to the casing required by CamelCaseModel.
     *
     * @param mixed $attributes
     *
     * @return array
     */
    public function toCamelCase($attributes)
    {
        $convertedAttributes = [];

        foreach ($attributes as $key => $value) {
            $key = $this->getTrueKey($key);
            $convertedAttributes[ $key ] = $value;
        }

        return $convertedAttributes;
    }

    /**
     * Get the model's original attribute values.
     *
     * @param  string $key
     * @param  mixed $default
     *
     * @return array
     */
    public function getOriginal($key = null, $default = null)
    {
        return array_get($this->toCamelCase($this->original), $key, $default);
    }

    /**
     * Get the casts array.
     *
     * @return array
     */
    public function getCasts()
    {
        $casts = parent::getCasts();

        return $this->toSnakeCase($casts);
    }

    /**
     * Converts a given array of attribute keys to the casing required by CamelCaseModel.
     *
     * @param $attributes
     *
     * @return array
     */
    public function toSnakeCase($attributes)
    {
        $convertedAttributes = [];

        foreach ($attributes as $key => $value) {
            $convertedAttributes[ $this->getSnakeKey($key) ] = $value;
        }

        return $convertedAttributes;
    }

    /**
     * Retrieves the true key name for a key.
     *
     * @param $key
     *
     * @return string
     */
    public function getTrueKey($key)
    {
        // If the key is a pivot key, leave it alone - this is required internal behaviour
        // of Eloquent for dealing with many:many relationships.
        if ($this->isCamelCase() && strpos($key, 'pivot_') === false) {
            $key = Str::camel($key);
        }

        return $key;
    }

    /**
     * Determines whether the model (or its parent) requires camelcasing. This is required
     * for pivot models whereby they actually depend on their parents for this feature.
     *
     * @return bool
     */
    public function isCamelCase()
    {
        return $this->enforceCamelCase or (isset($this->parent) && method_exists($this->parent,
                    'isCamelCase') && $this->parent->isCamelCase());
    }

    /**
     * If the field names need to be converted so that they can be accessed by camelCase, then we can do that here.
     *
     * @param $key
     *
     * @return string
     */
    protected function getSnakeKey($key)
    {
        return Str::snake($key);
    }

    /**
     * Because we are changing the case of keys and want to use camelCase throughout the application, whenever
     * we do isset checks we need to ensure that we check using snake_case.
     *
     * @param $key
     *
     * @return mixed
     */
    public function __isset($key)
    {
        return parent::__isset($key) || parent::__isset($this->getSnakeKey($key));
    }

    /**
     * Because we are changing the case of keys and want to use camelCase throughout the application, whenever
     * we do unset variables we need to ensure that we unset using snake_case.
     *
     * @param $key
     *
     * @return void
     */
    public function __unset($key)
    {
        return parent::__unset($this->getSnakeKey($key));
    }

    /**
     * Handle dynamic method calls into the model.
     *
     * @param  string $method
     * @param  array $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $parentCall = parent::__call($method, $parameters);

        if ($parentCall instanceof BaseLengthAwarePaginator) {
            return new LengthAwarePaginator($parentCall);
        }

        return $parentCall;
    }

}

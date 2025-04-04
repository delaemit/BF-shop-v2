<?php

declare(strict_types=1);

namespace Webkul\Core\Eloquent;

use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Repository\Traits\CacheableRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * @method \Kalnoy\Nestedset\QueryBuilder query()
 * @method \Kalnoy\Nestedset\QueryBuilder where($column, $operator = null, $value = null, $boolean = 'and')
 */
abstract class Repository extends BaseRepository implements CacheableInterface
{
    use CacheableRepository;

    /**
     * Cache only enabled.
     *
     * @var ?array
     */
    protected ?array $cacheOnly = null;

    /**
     * Cache except enabled.
     *
     * @var ?array
     */
    protected ?array $cacheExcept = null;

    /**
     * Clean enabled.
     *
     * @var bool
     */
    protected bool $cleanEnabled = false;

    protected \Illuminate\Contracts\Database\Eloquent\Builder $query;

    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        $this->query = $model->newQuery();

        return $this->model = $model;
    }

    /**
     * Allowed clean.
     *
     * @return bool
     */
    public function allowedClean(): bool
    {
        return $this->cleanEnabled ?? config('repository.cache.clean.enabled', true);
    }

    /**
     * Allowed cache.
     *
     * @param mixed $method
     *
     * @return bool
     */
    protected function allowedCache($method): bool
    {
        $className = static::class;

        $cacheEnabled = config("repository.cache.repositories.$className.enabled", config('repository.cache.enabled', true));
        if (!$cacheEnabled) {
            return false;
        }

        $cacheOnly = $this->cacheOnly ?? config("repository.cache.repositories.$className.allowed.only", config('repository.cache.allowed.only', null));

        $cacheExcept = $this->cacheExcept ?? config("repository.cache.repositories.$className.allowed.except", config('repository.cache.allowed.only', null));

        if (is_array($cacheOnly)) {
            return in_array($method, $cacheOnly, true);
        }
        if (is_array($cacheExcept)) {
            return !in_array($method, $cacheExcept, true);
        }
        if (is_null($cacheOnly) && is_null($cacheExcept)) {
            return true;
        }

        return false;
    }

    /**
     * Reset model.
     *
     * @throws RepositoryException
     */
    public function resetModel()
    {
        $this->makeModel();

        return $this;
    }

    /**
     * Find data by field and value.
     *
     * @param string $field
     * @param string $value
     * @param array $columns
     *
     * @return mixed
     */
    public function findOneByField($field, $value = null, $columns = ['*'])
    {
        return $this->findByField($field, $value, $columns)->first();
    }

    /**
     * Find data by field and value.
     *
     * @param array $columns
     * @param array $where
     *
     * @return mixed
     */
    public function findOneWhere(array $where, $columns = ['*'])
    {
        return $this->findWhere($where, $columns)->first();
    }

    /**
     * Find data by id.
     *
     * @param int $id
     * @param array $columns
     *
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->find($id, $columns);
        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Find data by id.
     *
     * @param int $id
     * @param array $columns
     *
     * @return mixed
     */
    public function findOrFail($id, $columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->findOrFail($id, $columns);
        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Count results of repository.
     *
     * @param string $columns
     * @param array $where
     *
     * @return int
     */
    public function count(array $where = [], $columns = '*')
    {
        $this->applyCriteria();
        $this->applyScope();

        if ($where) {
            $this->applyConditions($where);
        }

        $result = $this->model->count($columns);
        $this->resetModel();
        $this->resetScope();

        return $result;
    }

    /**
     * Sum.
     *
     * @param string $columns
     *
     * @return mixed
     */
    public function sum($columns)
    {
        $this->applyCriteria();
        $this->applyScope();

        $sum = $this->model->sum($columns);
        $this->resetModel();

        return $sum;
    }

    /**
     * Avg.
     *
     * @param string $columns
     *
     * @return mixed
     */
    public function avg($columns)
    {
        $this->applyCriteria();
        $this->applyScope();

        $avg = $this->model->avg($columns);
        $this->resetModel();

        return $avg;
    }

    /**
     * Get model.
     *
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }
}

<?php

declare(strict_types=1);

namespace Webkul\DataGrid\ColumnTypes;

use Webkul\DataGrid\Column;
use Webkul\DataGrid\Enums\FilterTypeEnum;
use Webkul\DataGrid\Exceptions\InvalidColumnExpressionException;

class Aggregate extends Column
{
    /**
     * Process filter.
     *
     * @param mixed $queryBuilder
     * @param mixed $requestedValues
     */
    public function processFilter($queryBuilder, $requestedValues)
    {
        if ($this->filterableType === FilterTypeEnum::DROPDOWN->value) {
            return $queryBuilder->having(function ($scopeQueryBuilder) use ($requestedValues): void {
                if (is_string($requestedValues)) {
                    $scopeQueryBuilder->orHaving($this->columnName, $requestedValues);
                } elseif (is_array($requestedValues)) {
                    foreach ($requestedValues as $value) {
                        $scopeQueryBuilder->orHaving($this->columnName, $value);
                    }
                } else {
                    throw new InvalidColumnExpressionException('Only string and array are allowed for text column type.');
                }
            });
        }

        return $queryBuilder->having(function ($scopeQueryBuilder) use ($requestedValues): void {
            if (is_string($requestedValues)) {
                $scopeQueryBuilder->orHaving($this->columnName, 'LIKE', '%' . $requestedValues . '%');
            } elseif (is_array($requestedValues)) {
                foreach ($requestedValues as $value) {
                    $scopeQueryBuilder->orHaving($this->columnName, 'LIKE', '%' . $value . '%');
                }
            } else {
                throw new InvalidColumnExpressionException('Only string and array are allowed for text column type.');
            }
        });
    }
}

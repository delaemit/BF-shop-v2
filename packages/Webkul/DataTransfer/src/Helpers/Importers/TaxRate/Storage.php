<?php

declare(strict_types=1);

namespace Webkul\DataTransfer\Helpers\Importers\TaxRate;

use Webkul\Tax\Repositories\TaxRateRepository;

class Storage
{
    /**
     * Items contains identifier as key and product information as value
     */
    protected array $items = [];

    /**
     * Columns which will be selected from database
     */
    protected array $selectColumns = [
        'id',
        'identifier',
    ];

    /**
     * Create a new helper instance.
     *
     * @param TaxRateRepository $taxRateRepository
     *
     * @return void
     */
    public function __construct(protected TaxRateRepository $taxRateRepository)
    {
    }

    /**
     * Initialize storage
     */
    public function init(): void
    {
        $this->items = [];

        $this->load();
    }

    /**
     * Load the identifiers
     *
     * @param array $identifiers
     */
    public function load(array $identifiers = []): void
    {
        if (empty($identifiers)) {
            $taxRates = $this->taxRateRepository->all($this->selectColumns);
        } else {
            $taxRates = $this->taxRateRepository->findWhereIn('identifier', $identifiers, $this->selectColumns);
        }

        foreach ($taxRates as $taxRate) {
            $this->set($taxRate->identifier, $taxRate->id);
        }
    }

    /**
     * Get identifier information
     *
     * @param string $identifier
     * @param int $id
     */
    public function set(string $identifier, int $id): self
    {
        $this->items[$identifier] = $id;

        return $this;
    }

    /**
     * Check if identifier exists
     *
     * @param string $identifier
     */
    public function has(string $identifier): bool
    {
        return isset($this->items[$identifier]);
    }

    /**
     * Get identifier information
     *
     * @param string $identifier
     */
    public function get(string $identifier): ?int
    {
        if (!$this->has($identifier)) {
            return null;
        }

        return $this->items[$identifier];
    }

    /**
     * Is storage is empty
     */
    public function isEmpty(): int
    {
        return empty($this->items);
    }
}

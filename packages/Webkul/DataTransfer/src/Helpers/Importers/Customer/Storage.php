<?php

declare(strict_types=1);

namespace Webkul\DataTransfer\Helpers\Importers\Customer;

use Webkul\Customer\Repositories\CustomerRepository;

class Storage
{
    /**
     * Items contains email as key and product information as value
     */
    protected array $items = [];

    /**
     * Columns which will be selected from database
     */
    protected array $selectColumns = [
        'id',
        'email',
    ];

    /**
     * Create a new helper instance.
     *
     * @param CustomerRepository $customerRepository
     *
     * @return void
     */
    public function __construct(protected CustomerRepository $customerRepository)
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
     * Load the Emails
     *
     * @param array $emails
     */
    public function load(array $emails = []): void
    {
        if (empty($emails)) {
            $customers = $this->customerRepository->all($this->selectColumns);
        } else {
            $customers = $this->customerRepository->findWhereIn('email', $emails, $this->selectColumns);
        }

        foreach ($customers as $customer) {
            $this->set($customer->email, $customer->id);
        }
    }

    /**
     * Get email information
     *
     * @param string $email
     * @param int $id
     */
    public function set(string $email, int $id): self
    {
        $this->items[$email] = $id;

        return $this;
    }

    /**
     * Check if email exists
     *
     * @param string $email
     */
    public function has(string $email): bool
    {
        return isset($this->items[$email]);
    }

    /**
     * Get email information
     *
     * @param string $email
     */
    public function get(string $email): ?int
    {
        if (!$this->has($email)) {
            return null;
        }

        return $this->items[$email];
    }

    /**
     * Is storage is empty
     */
    public function isEmpty(): int
    {
        return empty($this->items);
    }
}

<?php

declare(strict_types=1);

namespace Webkul\DataGrid;

/**
 * Initial implementation of the mass action class. Stay tuned for more features coming soon.
 */
class MassAction
{
    /**
     * Create a column instance.
     *
     * @param string $icon
     * @param string $title
     * @param string $method
     * @param mixed $url
     * @param array $options
     */
    public function __construct(
        public string $icon,
        public string $title,
        public string $method,
        public mixed $url,
        public array $options = [],
    ) {
    }

    /**
     * Convert to an array.
     */
    public function toArray()
    {
        return [
            'icon' => $this->icon,
            'title' => $this->title,
            'method' => $this->method,
            'url' => $this->url,
            'options' => $this->options,
        ];
    }
}

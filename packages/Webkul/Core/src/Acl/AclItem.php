<?php

declare(strict_types=1);

namespace Webkul\Core\Acl;

use Illuminate\Support\Collection;

class AclItem
{
    /**
     * Create a new AclItem instance.
     *
     * @param string $key
     * @param string $name
     * @param string $route
     * @param int $sort
     * @param Collection $children
     */
    public function __construct(
        public string $key,
        public string $name,
        public string $route,
        public int $sort,
        public Collection $children,
    ) {
    }
}

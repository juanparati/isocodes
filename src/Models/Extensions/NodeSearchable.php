<?php

namespace Juanparati\ISOCodes\Models\Extensions;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

trait NodeSearchable
{

    /**
     * Helper method that provide a way of search inside nodes.
     *
     * @param string $node
     * @param string|array $search
     * @param bool $exact
     * @return Collection|null
     */
    protected function whereNodeHas(string $node, string|array $search, bool $exact = false) : ?Collection
    {
        $search = Arr::wrap($search);

        $qry = $this->all();

        if ($exact) {
            return $qry->filter(
                fn($iso) => empty(array_diff($search, $iso[$node]))
            );
        }

        return $qry->filter(
            fn($iso) => !empty(array_intersect($search, $iso[$node]))
        );
    }

}
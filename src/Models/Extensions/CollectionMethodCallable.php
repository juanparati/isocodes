<?php

namespace Juanparati\ISOCodes\Models\Extensions;

use Illuminate\Support\Collection;

trait CollectionMethodCallable
{

    /**
     * Call a method from Collection.
     *
     * @param $method
     * @param $parameters
     * @return mixed
     * @throws \Juanparati\ISOCodes\Exceptions\ISONodeAttributeMissing
     */
    public function __call($method, $parameters) : mixed
    {
        if (method_exists(Collection::class, $method)) {
            $all = $this->all();
            return call_user_func_array([$all, $method], $parameters);
        }

        return parent::__call($method, $parameters);
    }
}
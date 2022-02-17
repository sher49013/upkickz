<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\JsonResource;

trait RequestHelper
{
    /**
     * Remove null values from Eloquent api resource
     * @param array $data
     * @return array
     */
    public function removeNullValues($data)
    {
        foreach ($data as $key => $value) {
            if (is_array($data[$key])){
                $data[$key] = $this->removeNullValues($data[$key]);
            }

            if (empty($value)){
                unset($data[$key]);
            }
        }

        return $data;
    }
}

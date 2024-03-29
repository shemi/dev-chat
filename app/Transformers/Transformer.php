<?php

namespace App\Transformers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

abstract class Transformer
{

    public function transformCollection($items)
    {
        $return = [];

        foreach ($items as $item){
            array_push($return, $this->transformModel($item));
        }

        return $return;
    }

    public function transformGroupedCollection($group)
    {
        $return = [];

        foreach ($group as $key => $items){
            $return[$key] = $this->transformCollection($items);
        }

        return $return;
    }

    public function formatDate($date)
    {
        if(! $date) {
            return null;
        }

        try {
            return Carbon::parse($date)->toIso8601String();
        } catch (\Exception $exception) {
            return $date;
        }
    }

    public static function transform($item)
    {
        if(is_array($item) || $item instanceof Collection)
        {
            return (new static())->transformCollection($item);
        }

        return $item ? (new static())->transformModel($item) : null;
    }

    public abstract function transformModel($item);

}
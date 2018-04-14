<?php

namespace App\Traits;

use Hashids;

trait HasPublicId
{

    /**
     * Scope a query to only include users of a given type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $publicId
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublicId($query, $publicId)
    {
        return $query->where($this->getTable().'.id', static::decodePublicId($publicId));
    }

    /**
     * @param $publicId
     * @return self|null
     */
    public static function findByPublicId($publicId)
    {
        return static::publicId($publicId)->first();
    }

    public static function getTableName()
    {
        return (new static)->getTable();
    }

    /**
     * @param string $publicId
     * @param bool $single
     * @return mixed
     */
    public static function decodePublicId($publicId, $single = true)
    {
        $id = Hashids::connection(static::getTableName())
            ->decode($publicId);

        return $single ? array_last((array) $id) : $id;
    }

    /**
     * @param int|string|array $id
     * @return mixed
     */
    public static function encodePublicId($id)
    {
        return Hashids::connection(static::getTableName())
            ->encode($id);
    }

    public function getPublicIdAttribute()
    {
        return static::encodePublicId($this->id);
    }

}
<?php namespace Novanti\Laravext\Traits;


trait LaravextModel
{

    public static function baseQuery()
    {
        $query = self::select(array('*'));
        return $query;
    }

    public function filterByKey()
    {
        return $this->baseQuery()->where($this->getKeyName(), $this->getKey());
    }

}

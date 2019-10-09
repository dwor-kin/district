<?php
namespace App\Helper;

class FilterMapper
{
    public static $filterDefinition = [
        'districtName'  => null,
        'population'    => null,
        'cityName'      => null,
        'area'          => null
    ];

    public static function convertFromUrlToArray($filter): array
    {
        $filters = self::$filterDefinition;
        foreach ($filter as $filterUrl) {
            $exploded = explode('@', $filterUrl);
            $filters[$exploded[0]] = $exploded[1];
        }

        return $filters;
    }

    public static function getFilterDefinition(): array
    {
        return self::$filterDefinition;
    }
}


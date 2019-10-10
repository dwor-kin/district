<?php
namespace App\Helper;

class FilterMapper
{
    public static $filterDefinition = [
        'districtName'  => null,
        'population'    => ['min' => null, 'max' => null],
        'cityName'      => null,
        'area'          => ['min' => null, 'max' => null],
    ];

    public static function convertFromUrlToArray($filter): array
    {
        $filters = self::$filterDefinition;
        foreach ($filter as $filterUrl) {
            $exploded = explode('@', $filterUrl);

            switch ($exploded[0]) {
                case 'areaMin' :
                    $filters['area']['min'] = $exploded[1]; break;
                case 'areaMax' :
                    $filters['area']['max'] = $exploded[1]; break;
                case 'populationMin' :
                    $filters['population']['min'] = $exploded[1]; break;
                case 'populationMax' :
                    $filters['population']['max'] = $exploded[1]; break;
                default:
                    $filters[$exploded[0]] = $exploded[1];
            }
        }

        return $filters;
    }

    public static function getFilterDefinition(): array
    {
        return self::$filterDefinition;
    }
}


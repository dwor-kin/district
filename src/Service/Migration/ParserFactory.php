<?php
namespace App\Service\Migration;

class ParserFactory
{
    /**
     * @param $city
     * @return DistrictParserInterface
     * @throws \Exception
     */
    public static function getParser($city): DistrictParserInterface
    {
        switch ($city) {
            case 'Gdańsk' :
                return new Gdansk();
            case 'Kraków' :
                return new Krakow();
            default:
                throw new \Exception('No specified city parser');
        }
    }
}
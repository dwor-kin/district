<?php
namespace App\Service\Migration;

use App\Model\CityDistrict;
use App\Model\SimpleDistrict;
use Symfony\Component\DomCrawler\Crawler;

class Krakow extends DistrictParserAbstract implements DistrictParserInterface
{
    protected $mainSiteUrl = "https://appimeri.um.krakow.pl/app-pub-dzl/pages/DzlViewAll.jsf?a=1&lay=&fo=";
    protected $districtUrl = "https://appimeri.um.krakow.pl/app-pub-dzl/pages/DzlViewGlw.jsf?id={{ index }}&lay=&fo=";

    /** @var string  */
    private $cityName = "Kraków";

    /** @var array */
    protected $arrContextOptions = array(
        "ssl" => array(
            "verify_peer"       => false,
            "verify_peer_name"  => false
        )
    );

    /** @var bool */
    protected $useIncludePath = false;

    /**
     * @param Crawler $crawler
     * @return SimpleDistrict[]
     */
    protected function getDistrictIdCollection(Crawler $crawler): array
    {
        $crawler = $crawler->filter('form > select')->children();

        $simpleDistrictCollection = $crawler->each(
            function (Crawler $node, $i) {
                return SimpleDistrict::build(
                    $node->text(),
                    $node->attr('value')
                );
            }
        );

        return $simpleDistrictCollection;
    }

    /**
     * @param array $simpleDistrictCollection
     * @return CityDistrict[]
     */
    protected function parseDistrict(array $simpleDistrictCollection): array
    {
        $allDistricts = [];

        /** @var SimpleDistrict $simpleDistrict */
        foreach ($simpleDistrictCollection as $simpleDistrict) {
            $districtHtml = file_get_contents(
                $this->prepareDistrictUrl($simpleDistrict->getDistrictId(), $this->districtUrl),
                $this->useIncludePath,
                stream_context_create($this->arrContextOptions)
            );

            $crawler = new Crawler($districtHtml);
            $district = $crawler->filter('table > tr > td > table > tr');

            $area = str_replace(",", ".", $district->getNode(0)->nodeValue);

            $allDistricts[] = CityDistrict::build(
                $this->cityName,
                $simpleDistrict->getDistrictName(),
                (float)filter_var($area, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
                (int)filter_var($district->getNode(1)->nodeValue, FILTER_SANITIZE_NUMBER_INT)
            );
        }

        return $allDistricts;
    }
}


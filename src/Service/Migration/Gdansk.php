<?php
namespace App\Service\Migration;

use App\Model\CityDistrict;
use App\Model\SimpleDistrict;
use Symfony\Component\DomCrawler\Crawler;

class Gdansk extends DistrictParserAbstract implements DistrictParserInterface
{
    /** @var string */
    protected $mainSiteUrl = "http://www.gdansk.pl/matarnia";

    /** @var string */
    protected $districtUrl = "https://www.gdansk.pl/subpages/dzielnice/[dzielnice]/html/dzielnice_mapa_alert.php?id={{ index }}";

    /** @var string */
    private $cityName = "Gdańsk";

    /** @var array */
    protected $arrContextOptions = array();

    /** @var bool */
    protected $useIncludePath = false;

    /**
     * @param Crawler $crawler
     * @return SimpleDistrict[]
     */
    protected function getDistrictIdCollection(Crawler $crawler): array
    {
        $district = $crawler->filter('div[class="btn-group lista-dzielnic"] > ul > li');
        $nodeValues = $district->each(
            function (Crawler $node, $i) {
                $id = $node->children()->attr('href');
                $title = $node->children()->first()->text();

                if ($title <> 'Wszystkie') {
                    return SimpleDistrict::build(
                        $title, $id
                    );
                } else {
                    return null;
                }
            }
        );

        return $nodeValues;
    }

    protected function parseDistrict(array $simpleDistrictCollection): array
    {
        $allDistricts = [];

        /** @var SimpleDistrict $simpleDistrict */
        foreach ($simpleDistrictCollection as $simpleDistrict) {
            if ($simpleDistrict instanceof SimpleDistrict) {
                $pattern = "/[0-9]+:'(" . $simpleDistrict->getDistrictId() . "){1}'/";
                preg_match_all($pattern, $this->mainHtml, $matches);

                if (!empty($matches[0][0])) {
                    $url = $this->prepareDistrictUrl((int)$matches[0][0], $this->districtUrl);
                    $quarterHtml = file_get_contents($url, $this->useIncludePath, stream_context_create($this->arrContextOptions));

                    $districtCrawler = new Crawler($quarterHtml);
                    $district = $districtCrawler->filter('div > div');

                    $allDistricts[] = CityDistrict::build(
                        $this->cityName,
                        $district->getNode(0)->textContent,
                        substr_replace(
                            (float)filter_var(
                                $district->getNode(1)->textContent, FILTER_SANITIZE_NUMBER_FLOAT
                            ), "", -1
                        ),
                        (int)filter_var($district->getNode(2)->textContent, FILTER_SANITIZE_NUMBER_INT)
                    );
                }
            }
        }

        return $allDistricts;
    }
}


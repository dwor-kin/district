<?php
namespace App\Service\Migration;

use Symfony\Component\DomCrawler\Crawler;

abstract class DistrictParserAbstract implements DistrictParserInterface
{
    protected $mainSiteUrl = '';
    protected $useIncludePath = false;
    protected $arrContextOptions = [];

    protected $mainHtml;

    protected function getMainCrawler(): string
    {
        return $this->getCrawlerForProvidedUrl(
            $this->mainSiteUrl,
            $this->useIncludePath,
            stream_context_create($this->arrContextOptions)
        );
    }

    protected function getCrawlerForProvidedUrl($url, $useIncludePath, $arrContextOptions): string
    {
        return file_get_contents($url, $useIncludePath, $arrContextOptions);
    }

    protected function prepareDistrictUrl($index, $districtUrl)
    {
        return str_replace("{{ index }}", $index, $districtUrl);
    }

    protected function getDistrictIdCollection(Crawler $crawler): array
    {
        return array();
    }

    protected function parseDistrict(array $simpleDistrictCollection): array
    {
        return array();
    }

    public function buildDistrictData(): array
    {
        $this->mainHtml = $this->getMainCrawler();
        $simpleDistrictCollection = $this->getDistrictIdCollection(new Crawler($this->mainHtml));
        return $this->parseDistrict(($simpleDistrictCollection));
    }
}
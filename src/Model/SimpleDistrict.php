<?php
namespace App\Model;

class SimpleDistrict
{
    /** @var string */
    private $districtName;

    /** @var string */
    private $districtId;

    public function __construct(string $districtName, string $districtId)
    {
        $this->districtName = $districtName;
        $this->districtId = $districtId;
    }

    static public function build(string $districtName, string $districtId): SimpleDistrict
    {
        return new self($districtName, $districtId);
    }

    /**
     * @return string
     */
    public function getDistrictName(): string
    {
        return $this->districtName;
    }

    /**
     * @param string $districtName
     */
    public function setDistrictName(string $districtName): void
    {
        $this->districtName = $districtName;
    }

    /**
     * @return string
     */
    public function getDistrictId(): string
    {
        return $this->districtId;
    }

    /**
     * @param string $districtId
     */
    public function setDistrictId(string $districtId): void
    {
        $this->districtId = $districtId;
    }
}
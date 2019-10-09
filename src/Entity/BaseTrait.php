<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

trait BaseTrait
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return $this
     */
    public function setId(?int $id)
    {
        $this->id = $id;
        return $this;
    }
}
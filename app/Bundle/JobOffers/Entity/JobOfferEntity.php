<?php declare(strict_types=1);

namespace App\Bundle\JobOffers\Entity;

class JobOfferEntity
{
    /** @var int */
    private $id;
    /** @var string */
    private $title = '';
    /** @var array */
    private $cities = [];
    /** @var string */
    private $content = '';

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getCities(): array
    {
        return $this->cities;
    }

    /**
     * @param string $cities
     */
    public function setCities(string $cities): void
    {
        $this->cities[] = $cities;
    }

    /**
     * @param array $cities
     */
    public function addCities(array $cities): void
    {
        $this->cities = array_merge($this->cities, $cities);
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}

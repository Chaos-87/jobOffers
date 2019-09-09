<?php

namespace App\Bundle\JobOffers\Service;

interface JobOffersServiceInterface
{
    /**
     * @return array
     */
    public function getAll(): array;

    /**
     * @param string $city
     * @return array
     */
    public function getByCity(string $city): array;

    /**
     * @param string $title
     * @return array
     */
    public function getByTitle(string $title): array;

    /**
     * @return int
     */
    public function getCountAllOffers(): int;

    /**
     * @return string
     */
    public function getStatus(): string;
}

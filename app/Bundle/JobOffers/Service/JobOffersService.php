<?php declare(strict_types=1);

namespace App\Bundle\JobOffers\Service;

use App\Bundle\JobOffers\Handle\JobOffersExceptionHandle;
use App\Bundle\JobOffers\Repository\JobOffersRepository;
use Exception;

class JobOffersService implements JobOffersServiceInterface
{

    /** @var JobOffersRepository */
    private $jobOffersRepository;
    /** @var JobOffersExceptionHandle */
    private $exceptionHandle;

    public function __construct(
        JobOffersRepository $jobOffersRepository,
        JobOffersExceptionHandle $exceptionHandle
    )
    {
        $this->jobOffersRepository = $jobOffersRepository;
        $this->exceptionHandle = $exceptionHandle;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        try{
            $results = $this->jobOffersRepository->getAll();
        }catch (Exception $exception) {
            $this->exceptionHandle->handle($exception);
            $results = [];
        }
        return $results;
    }

    /**
     * @param string $city
     * @return array
     */
    public function getByCity(string $city): array
    {
        try{
            $results = $this->jobOffersRepository->getByCity($city);
        }catch (Exception $exception) {
            $this->exceptionHandle->handle($exception);
            $results = [];
        }
        return $results;
    }

    /**
     * @param string $title
     * @return array
     */
    public function getByTitle(string $title): array
    {
        try{
            $results = $this->jobOffersRepository->getByTitle($title);
        }catch (Exception $exception) {
            $this->exceptionHandle->handle($exception);
            $results = [];
        }
        return $results;
    }

    /**
     * @return int
     */
    public function getCountAllOffers(): int
    {
        try{
            return $this->jobOffersRepository->getCountAllOffers();
        }catch (Exception $exception){
            $this->exceptionHandle->handle($exception);
            return 0;
        }
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        $results = 'OK';
        try{
            $this->jobOffersRepository->getStatus();
        }catch (Exception $exception) {
            $this->exceptionHandle->handle($exception);
            $results = 'ERROR';
        }
        return $results;
    }
}

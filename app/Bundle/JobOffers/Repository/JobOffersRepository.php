<?php declare(strict_types=1);

namespace App\Bundle\JobOffers\Repository;

use App\Bundle\JobOffers\Constant\ApiFieldConstant;
use App\Bundle\JobOffers\Constant\FilterJobOfferConstant;
use App\Bundle\JobOffers\Entity\JobOfferEntity;
use App\Bundle\JobOffers\Exception\APIInvalidContentStatusException;
use App\Bundle\JobOffers\Exception\APIInvalidDataOfferException;
use App\Bundle\JobOffers\Exception\GuzzleException;
use App\Bundle\JobOffers\Exception\GuzzleInvalidBodyTypeException;
use App\Bundle\JobOffers\Exception\GuzzleInvalidConnectionToAPIException;
use App\Bundle\JobOffers\Exception\APIInvalidContentException;
use App\Bundle\JobOffers\Exception\GuzzleInvalidStatusCodeException;
use App\Bundle\JobOffers\Exception\JobOfferNotFoundIdException;
use App\Bundle\JobOffers\Hydrator\JobOffersHydrator;
use App\Bundle\JobOffers\Query\JobOffersQuery;

class JobOffersRepository
{
    /** @var JobOffersQuery */
    private $jobOffersQuery;
    /** @var JobOffersHydrator */
    private $hydrator;

    public function __construct(
        JobOffersQuery $jobOffersQuery,
        JobOffersHydrator $hydrator
    )
    {
        $this->jobOffersQuery = $jobOffersQuery;
        $this->hydrator = $hydrator;
    }

    /**
     * @return array
     * @throws APIInvalidContentException
     * @throws APIInvalidContentStatusException
     * @throws APIInvalidDataOfferException
     * @throws GuzzleException
     * @throws GuzzleInvalidBodyTypeException
     * @throws GuzzleInvalidConnectionToAPIException
     * @throws GuzzleInvalidStatusCodeException
     * @throws JobOfferNotFoundIdException
     */
    public function getAll(): array
    {
        return $this->getBy();
    }

    /**
     * @param string $city
     * @return array
     * @throws APIInvalidContentException
     * @throws APIInvalidContentStatusException
     * @throws APIInvalidDataOfferException
     * @throws GuzzleException
     * @throws GuzzleInvalidBodyTypeException
     * @throws GuzzleInvalidConnectionToAPIException
     * @throws GuzzleInvalidStatusCodeException
     * @throws JobOfferNotFoundIdException
     */
    public function getByCity(string $city): array
    {
        $conditions[FilterJobOfferConstant::CITY] = $city;
        return $this->getBy($conditions);
    }

    /**
     * @param string $title
     * @return array
     * @throws APIInvalidContentException
     * @throws APIInvalidContentStatusException
     * @throws APIInvalidDataOfferException
     * @throws GuzzleException
     * @throws GuzzleInvalidBodyTypeException
     * @throws GuzzleInvalidConnectionToAPIException
     * @throws GuzzleInvalidStatusCodeException
     * @throws JobOfferNotFoundIdException
     */
    public function getByTitle(string $title): array
    {
        $conditions[FilterJobOfferConstant::TITLE]=$title;
        return $this->getBy($conditions);
    }

    /**
     * @return int
     * @throws APIInvalidContentException
     * @throws APIInvalidContentStatusException
     * @throws GuzzleException
     * @throws GuzzleInvalidBodyTypeException
     * @throws GuzzleInvalidConnectionToAPIException
     * @throws GuzzleInvalidStatusCodeException
     */
    public function getCountAllOffers()
    {
        $body = $this->getResponseBody();
        return $this->countOffers($body);
    }

    /**
     * @return void
     * @throws APIInvalidContentException
     * @throws APIInvalidContentStatusException
     * @throws GuzzleException
     * @throws GuzzleInvalidBodyTypeException
     * @throws GuzzleInvalidConnectionToAPIException
     * @throws GuzzleInvalidStatusCodeException
     */
    public function getStatus(): void
    {
        $this->getResponseBody();
    }

    /**
     * @param string $content
     * @return array
     * @throws APIInvalidContentException
     */
    private function decodeResponseApi(string $content): array
    {
        $body = json_decode($content, true);
        if (json_last_error() != JSON_ERROR_NONE) {
            throw new APIInvalidContentException();
        }
        return $body;
    }

    /**
     * @param array $body
     * @return array
     * @throws APIInvalidContentStatusException
     */
    private function checkStatusResponseApi(array $body): array
    {
        if (!isset($body[ApiFieldConstant::SUCCESS]) && !$body[ApiFieldConstant::SUCCESS]) {
            throw new APIInvalidContentStatusException();
        }
        return $body;
    }

    /**
     * @return array
     * @throws APIInvalidContentException
     * @throws APIInvalidContentStatusException
     * @throws GuzzleException
     * @throws GuzzleInvalidBodyTypeException
     * @throws GuzzleInvalidConnectionToAPIException
     * @throws GuzzleInvalidStatusCodeException
     */
    private function getResponseBody(): array
    {
        $content = $this->jobOffersQuery->getAll();

        $body = $this->decodeResponseApi($content);

        $body = $this->checkStatusResponseApi($body);
        return $body;
    }

    /**
     * @param array $body
     * @return int
     */
    private function countOffers(array $body): int
    {
        if(empty($body[ApiFieldConstant::DATA]) || !is_array($body[ApiFieldConstant::DATA])){
            return 0;
        }
        return count($body[ApiFieldConstant::DATA]);
    }

    /**
     * @param array $body
     * @param array $conditions
     * @return array
     * @throws APIInvalidDataOfferException
     * @throws JobOfferNotFoundIdException
     */
    private function mapperOffers(array $body, array $conditions = []): array
    {
        $result = [];
        foreach ($body[ApiFieldConstant::DATA] as $offer) {
            if (!is_array($offer)) {
                throw new APIInvalidDataOfferException();
            }
            /** @var  JobOfferEntity $jobOffer */
            $jobOffer = $this->hydrator->hydrate($offer);
            if($this->checkOfferMeetConditions($jobOffer, $conditions)){
                $result[] = $jobOffer;
            }
        }
        return $result;
    }

    /**
     * @param JobOfferEntity $jobOffer
     * @param array $conditions
     * @return bool
     */
    private function checkOfferMeetConditions(JobOfferEntity $jobOffer, array $conditions = []): bool
    {
        if(empty($conditions)){
            return true;
        }
        if(!empty($conditions[FilterJobOfferConstant::CITY])) {
            if(!(strpos(
                strtolower(implode(',',$jobOffer->getCities() )),
                strtolower($conditions[FilterJobOfferConstant::CITY])
                ) !== false )){
                return false;
            }
        }

        if(!empty($conditions[FilterJobOfferConstant::TITLE])) {
            if(!(strpos(
                    strtolower($jobOffer->getTitle()),
                    strtolower($conditions[FilterJobOfferConstant::TITLE])
                ) !== false )){
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $conditions
     * @return array
     * @throws APIInvalidContentException
     * @throws APIInvalidContentStatusException
     * @throws APIInvalidDataOfferException
     * @throws GuzzleException
     * @throws GuzzleInvalidBodyTypeException
     * @throws GuzzleInvalidConnectionToAPIException
     * @throws GuzzleInvalidStatusCodeException
     * @throws JobOfferNotFoundIdException
     */
    private function getBy(array $conditions = []): array
    {
        $result = [];

        $body = $this->getResponseBody();

        if ($this->countOffers($body) < 1) {
            return $result;
        }
        return $this->mapperOffers($body, $conditions);
    }
}

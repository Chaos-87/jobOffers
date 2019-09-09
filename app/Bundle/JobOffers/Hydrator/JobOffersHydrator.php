<?php declare(strict_types=1);

namespace App\Bundle\JobOffers\Hydrator;

use App\Bundle\JobOffers\Entity\JobOfferEntity;
use App\Bundle\JobOffers\Exception\JobOfferNotFoundIdException;

class JobOffersHydrator
{
    public function hydrate(array $data): JobOfferEntity
    {
        if(!isset($data['id'])){
            throw new JobOfferNotFoundIdException();
        }
        $jobOffer = new JobOfferEntity((int)$data['id']);

        if(isset($data['cities'])){
            if(is_string($data['cities'])){
                $jobOffer->setCities($data['cities']);
            }elseif(is_array($data['cities'])){
                $jobOffer->addCities($data['cities']);
            }
        }

        if(isset($data['content'])){
            if(!empty($data['content']['title'])){
                $jobOffer->setTitle($data['content']['title']);
            }
            if(!empty($data['content']['content'])){
                $jobOffer->setContent($data['content']['content']);
            }
        }

        return $jobOffer;
    }
}

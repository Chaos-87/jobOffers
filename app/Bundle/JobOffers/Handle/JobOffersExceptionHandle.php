<?php declare(strict_types=1);

namespace App\Bundle\JobOffers\Handle;

use App\Bundle\JobOffers\Exception\APIInvalidContentException;
use App\Bundle\JobOffers\Exception\APIInvalidContentStatusException;
use App\Bundle\JobOffers\Exception\APIInvalidDataOfferException;
use App\Bundle\JobOffers\Exception\GuzzleException;
use App\Bundle\JobOffers\Exception\GuzzleInvalidBodyTypeException;
use App\Bundle\JobOffers\Exception\GuzzleInvalidConnectionToAPIException;
use App\Bundle\JobOffers\Exception\GuzzleInvalidStatusCodeException;
use App\Bundle\JobOffers\Exception\JobOfferNotFoundIdException;
use Exception;
use Illuminate\Support\Facades\Log;

class JobOffersExceptionHandle
{
    public function handle(Exception $exception)
    {

        switch (get_class($exception)) {
            case APIInvalidContentException::class:
                Log::error('APIInvalidContentException');
                break;
            case APIInvalidContentStatusException::class:
                Log::error('APIInvalidContentStatusException');
                break;
            case APIInvalidDataOfferException::class:
                Log::error('APIInvalidDataOfferException');
                break;
            case GuzzleException::class:
                Log::error('GuzzleException');
                break;
            case GuzzleInvalidBodyTypeException::class:
                Log::error('GuzzleInvalidBodyTypeException');
                break;
            case GuzzleInvalidConnectionToAPIException::class:
                Log::error('GuzzleInvalidConnectionToAPIException');
                break;
            case GuzzleInvalidStatusCodeException::class:
                Log::error('GuzzleInvalidStatusCodeException');
                break;
            case JobOfferNotFoundIdException::class:
                Log::error('JobOfferNotFoundIdException');
                break;
            default:
                Log::error('Unknown Exception');
        }
    }
}

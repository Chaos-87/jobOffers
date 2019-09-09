<?php

namespace App\Http\Controllers;

use App\Bundle\JobOffers\Service\JobOffersService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /** @var JobOffersService */
    private $jobOffersService;

    public function __construct(JobOffersService $jobOffersService)
    {
        $this->jobOffersService = $jobOffersService;
    }

    public function index()
    {
        dd($this->jobOffersService->getByTitle('spe'));
    }
}

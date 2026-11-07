<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\City;
use App\Entity\Forecast;
use App\Repository\CityRepository;
use App\Repository\ForecastRepository;

class WeatherUtil
{
    public function __construct(private ForecastRepository $forecastRepository, private CityRepository $cityRepository){}
    /**
     * @return Forecast[]
     */
    public function getWeatherForLocation(City $city): array
    {
        return $this->forecastRepository->findByLocation($city);
    }

    /**
     * @return Forecast[]
     */
    public function getWeatherForCountryAndCity(string $countryCode, string $city): array
    {
        $location = $this->cityRepository->findByName($city, $countryCode);
        if (!$location) {
            return [];
        }
        return $this->getWeatherForLocation($location);
    }
}

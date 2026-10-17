<?php

namespace App\Controller;

use App\Entity\City;
use App\Repository\CityRepository;
use App\Repository\ForecastRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherController extends AbstractController
{
    #[Route('/weather/{name}/{country}', name: 'app_weather', requirements: ['country' => '[A-Z]{2}'])]
    public function city(string $name, string $country, CityRepository $cityRepository, ForecastRepository $forecastRepository): Response
    {
        $city = $cityRepository->findByName($name,$country);
        $forecast = $forecastRepository->findByLocation($city);
        return $this->render('weather/city.html.twig', [
            'city' => $city,
            'forecast' => $forecast,
        ]);
    }

}

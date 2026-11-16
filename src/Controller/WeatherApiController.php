<?php

namespace App\Controller;

use App\Entity\Forecast;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api', methods: ['GET'])]
    public function index(
        #[MapQueryParameter] string $city,
        #[MapQueryParameter] string $country,
        WeatherUtil $util,
        #[MapQueryParameter] string $format = 'json',
        #[MapQueryParameter('twig')] bool $twig = false
    ): Response
    {
        $measurements = $util->getWeatherForCountryAndCity($country, $city);
        if($format === 'csv'){
            if ($twig) {
                return $this->render('weather_api/index.csv.twig', [
                    'city' => $city,
                    'country' => $country,
                    'measurements' => $measurements,
                ]);
            }
            $csv = "city,country,date,celsius,fahrenheit\n";
            foreach ($measurements as $f) {
                $csv .= sprintf(
                    "%s,%s,%s,%s,%s\n",
                    $city,
                    $country,
                    $f->getDate()->format('Y-m-d'),
                    $f->getTemperature(),
                    $f->getFahrenheit());
            }
            return new Response($csv,  200, ['Content-Type' => 'text/csv']);
        }
        if ($twig) {
            return $this->render('weather_api/index.json.twig', [
                'city' => $city,
                'country' => $country,
                'measurements' => $measurements,
            ]);
        }
        return $this->json([
            'city' => $city,
            'country' => $country,
            'measurements' => array_map(fn(Forecast $f) => [
                'date' => $f->getDate()->format('Y-m-d'),
                'celsius' => $f->getTemperature(),
                'fahrenheit' => $f->getFahrenheit(),
            ], $measurements),
        ]);
    }
}

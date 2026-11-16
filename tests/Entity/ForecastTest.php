<?php

namespace App\Tests\Entity;

use App\Entity\Forecast;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ForecastTest extends TestCase
{
    #[DataProvider('dataGetFahrenheit')]
    public function testGetFahrenheit(string $celsius, string $expectedFahrenheit): void
    {
        $forecast = new Forecast();
        $forecast->setTemperature($celsius);
        $this->assertEquals($expectedFahrenheit, $forecast->getFahrenheit());
    }

    public static function dataGetFahrenheit(): array{
        return [
          ['0', '32'],
          ['-100', '-148'],
          ['100', '212'],
          ['67', '152.6'],
          ['88', '190.4'],
          ['111', '231.8'],
          ['233', '451.4'],
          ['0.5', '32.9'],
          ['36.6', '97.88'],
          ['-40.5', '-40.9']
        ];
    }
}

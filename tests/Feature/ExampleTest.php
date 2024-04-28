<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */


    public function testConvertToFahrenheit() {
        $celsius = 0; // Punto de congelación del agua

        $response = $this->json('POST', '/convertToFahrenheit', [
            'celsius' => $celsius
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'fahrenheit' => 32 // Punto de congelación del agua en Fahrenheit
            ]);
    }
}

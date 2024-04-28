<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function convertirMoneda(Request $request)
    {
        $cantidad = $request->input('cantidad');
        $monedaOrigen = $request->input('monedaOrigen');
        $monedaDestino = $request->input('monedaDestino');

        // Lógica de conversión (simplificada para este ejemplo)
        $tasasDeCambio = [
            'USD' => ['EUR' => 0.85, 'MXN' => 20],
            // otras tasas...
        ];

        $convertido = $cantidad * $tasasDeCambio[$monedaOrigen][$monedaDestino];
        return response()->json(['convertido' => $convertido]);
    }

   public function filtrarPorEdad(Request $request) {
        $data = $request->input('data');
        $edadMinima = $request->input('edadMinima');

        $filtered = array_filter($data, function($person) use ($edadMinima) {
            return $person['age'] >= $edadMinima;
        });

        return response()->json(array_values($filtered));
    }

    public function convertToFahrenheit(Request $request) {
        $celsius = $request->input('celsius');

        $fahrenheit = ($celsius * 9/5) + 32;

        return response()->json(['fahrenheit' => $fahrenheit]);
    }
}

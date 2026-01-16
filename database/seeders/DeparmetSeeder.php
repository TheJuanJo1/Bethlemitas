<?php

namespace Database\Seeders;

use App\Models\Deparment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeparmetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Amazonas
        $amazonas = new Deparment;
        $amazonas->deparment = 'Amazonas';
        $amazonas->save();

        // Antioquia
        $antioquia = new Deparment;
        $antioquia->deparment = 'Antioquia';
        $antioquia->save();

        // Arauca
        $arauca = new Deparment;
        $arauca->deparment = 'Arauca';
        $arauca->save();

        // Atlantico
        $atlantico = new Deparment;
        $atlantico->deparment = 'Atlantico';
        $atlantico->save();

        // Bolivar
        $bolivar = new Deparment;
        $bolivar->deparment = 'Bolivar';
        $bolivar->save();

        // Boyaca
        $boyaca = new Deparment;
        $boyaca->deparment = 'Boyacá';
        $boyaca->save();

        // Caldas
        $caldas = new Deparment;
        $caldas->deparment = 'Caldas';
        $caldas->save();

        // Caquetá
        $caqueta = new Deparment;
        $caqueta->deparment = 'Caquetá';
        $caqueta->save();

        // Casanare
        $casanare = new Deparment;
        $casanare->deparment = 'Casanare';
        $casanare->save();

        // Cauca
        $cauca = new Deparment;
        $cauca->deparment = 'Cauca';
        $cauca->save();

        // Cesar
        $cesar = new Deparment;
        $cesar->deparment = 'Cesar';
        $cesar->save();

        // Chocó
        $choco = new Deparment;
        $choco->deparment = 'Chocó';
        $choco->save();

        // Córdoba
        $cordoba = new Deparment;
        $cordoba->deparment = 'Córdoba';
        $cordoba->save();

        // Cundinamarca
        $cundinamarca = new Deparment;
        $cundinamarca->deparment = 'Cundinamarca';
        $cundinamarca->save();

        // Guainía
        $guainia = new Deparment;
        $guainia->deparment = 'Guainía';
        $guainia->save();

        // Guaviare
        $guaviare = new Deparment;
        $guaviare->deparment = 'Guaviare';
        $guaviare->save();

        // Huila
        $huila = new Deparment;
        $huila->deparment = 'Huila';
        $huila->save();

        // La Guajira
        $laGuajira = new Deparment;
        $laGuajira->deparment = 'La Guajira';
        $laGuajira->save();

        // Magdalena
        $magdalena = new Deparment;
        $magdalena->deparment = 'Magdalena';
        $magdalena->save();

        // Meta
        $meta = new Deparment;
        $meta->deparment = 'Meta';
        $meta->save();

        // Nariño
        $narino = new Deparment;
        $narino->deparment = 'Nariño';
        $narino->save();

        // Norte de Santander
        $norteDeSantander = new Deparment;
        $norteDeSantander->deparment = 'Norte de Santander';
        $norteDeSantander->save();

        // Putumayo
        $putumayo = new Deparment;
        $putumayo->deparment = 'Putumayo';
        $putumayo->save();

        // Quindío
        $quindio = new Deparment;
        $quindio->deparment = 'Quindío';
        $quindio->save();

        // Risaralda
        $risaralda = new Deparment;
        $risaralda->deparment = 'Risaralda';
        $risaralda->save();

        // San Andrés y Providencia
        $sanAndres = new Deparment;
        $sanAndres->deparment = 'San Andrés y Providencia';
        $sanAndres->save();

        // Santander
        $santander = new Deparment;
        $santander->deparment = 'Santander';
        $santander->save();

        // Sucre
        $sucre = new Deparment;
        $sucre->deparment = 'Sucre';
        $sucre->save();

        // Tolima
        $tolima = new Deparment;
        $tolima->deparment = 'Tolima';
        $tolima->save();

        // Valle del Cauca
        $valleDelCauca = new Deparment;
        $valleDelCauca->deparment = 'Valle del Cauca';
        $valleDelCauca->save();

        // Vaupés
        $vaupes = new Deparment;
        $vaupes->deparment = 'Vaupés';
        $vaupes->save();

        // Vichada
        $vichada = new Deparment;
        $vichada->deparment = 'Vichada';
        $vichada->save();


    }
}

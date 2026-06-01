<?php

namespace App\Console\Commands;

use App\Support\PdfImageHelper;
use Illuminate\Console\Command;

class ConvertPngToJpegForPdf extends Command
{
    protected $signature = 'piar:convert-png-jpeg';

    protected $description = 'Genera archivos .jpg junto a PNG usados en PDFs (firmas, logo)';

    public function handle(): int
    {
        $paths = array_merge(
            glob(public_path('Imagenes_Firma/*.png')) ?: [],
            glob(public_path('img/credencialesGo.png')) ?: []
        );

        foreach ($paths as $png) {
            $jpg = preg_replace('/\.png$/i', '.jpg', $png);
            $result = PdfImageHelper::ensureJpegSidecar($png);
            if ($result) {
                $this->info("OK: {$jpg}");
            } else {
                $this->warn("Sin conversión (¿falta GD?): {$png}");
            }
        }

        return self::SUCCESS;
    }
}

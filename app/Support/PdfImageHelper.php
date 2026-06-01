<?php

namespace App\Support;

class PdfImageHelper
{
    /** @var array<string, string> */
    private static array $tempCache = [];

    /**
     * Ruta absoluta usable por DomPDF (JPEG preferido). PNG se convierte si GD está disponible.
     */
    public static function pathForDompdf(?string $absolutePath): ?string
    {
        if (! $absolutePath || ! is_file($absolutePath)) {
            return null;
        }

        $ext = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION));

        if (in_array($ext, ['jpg', 'jpeg'], true)) {
            return $absolutePath;
        }

        if ($ext === 'png') {
            if (! function_exists('imagecreatefrompng')) {
                return null;
            }

            return self::pngToJpegTemp($absolutePath);
        }

        return null;
    }

    public static function institutionalLogoPath(): ?string
    {
        $jpg = public_path('img/credencialesGo.jpg');
        if (is_file($jpg)) {
            return $jpg;
        }

        return self::pathForDompdf(public_path('img/credencialesGo.png'));
    }

    private static function pngToJpegTemp(string $pngPath): ?string
    {
        if (isset(self::$tempCache[$pngPath]) && is_file(self::$tempCache[$pngPath])) {
            return self::$tempCache[$pngPath];
        }

        $img = @imagecreatefrompng($pngPath);
        if (! $img) {
            return null;
        }

        $width = imagesx($img);
        $height = imagesy($img);
        $canvas = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($canvas, 255, 255, 255);
        imagefill($canvas, 0, 0, $white);
        imagecopy($canvas, $img, 0, 0, 0, 0, $width, $height);
        imagedestroy($img);

        $tmp = sys_get_temp_dir().DIRECTORY_SEPARATOR.'piar_pdf_'.md5($pngPath).'.jpg';
        if (! imagejpeg($canvas, $tmp, 90)) {
            imagedestroy($canvas);

            return null;
        }
        imagedestroy($canvas);

        self::$tempCache[$pngPath] = $tmp;

        return $tmp;
    }
}

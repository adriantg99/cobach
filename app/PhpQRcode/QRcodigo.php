<?php
// ANA MOLINA 08/04/2024
namespace App\PhpQRcode;

include "phpqrcode.php";
class QRcodigo
{

      public static function prueba()
    {
        return "qrcode";
    }

    public  static function get_png($text)
    {

         $qr = QRcode::png($text);
        return $qr;

    }

}

<?php
// ANA MOLINA 08/04/2024

declare(strict_types=1);
namespace App\PhpCfdi;


use Credentials\Credential;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Efirma
{

      public static function prueba()
    {
        return "efirma";
    }

    public  static function certificado($texto,$efirma_file_certificate,$efirma_file_key,$efirma_password): Certificate
    {

        $dir = str_replace('\\', '/',getcwd());

        //$cerFile = $dir.'/fiel/certificate.cer';
        //$pemKeyFile =$dir.'/fiel/private-key.key';
        // $cerFile = $dir.'/fiel/aaa010101aaa_FIEL.cer';
        // $pemKeyFile =$dir.'/fiel/Claveprivada_FIEL_AAA010101AAA_20170515_120909.key';
        // $passPhrase = '12345678a'; // contraseña para abrir la llave privada
        $cerFile = $dir.'/fiel/'.$efirma_file_certificate;
        $pemKeyFile =$dir.'/fiel/'.$efirma_file_key;
        $passPhrase = $efirma_password; // contraseña para abrir la llave privada
        // echo getcwd() ;
        // echo $cerFile;
        // echo $pemKeyFile;
        // echo $passPhrase;

        // echo("OPEN INI");
        // echo($cerFile);

        // echo($pemKeyFile);
        // echo($passPhrase);
        $fiel = Credentials\Credential::openFiles($cerFile, $pemKeyFile, $passPhrase);
        //echo("OPEN FIN");
        // alias de privateKey/sign/verify
        $signature = $fiel->sign($texto);
       // echo base64_encode($signature), PHP_EOL;

        // alias de certificado/publicKey/verify
        $verify = $fiel->verify($texto, $signature);
       //var_dump($verify); // bool(true)

        // objeto certificado
        $certificado = $fiel->certificate();
        // echo "rfc:*****";
        // echo $certificado->rfc(), PHP_EOL; // el RFC del certificado
        // echo "legalname:*****";
        // echo $certificado->legalName(), PHP_EOL; // el nombre del propietario del certificado
        // echo "branchname:*****";
        // echo $certificado->branchName(), PHP_EOL; // el nombre de la sucursal (en CSD, en FIEL está vacía)
        // echo "serialnumber:*****";
        // echo $certificado->serialNumber()->bytes(), PHP_EOL; // número de serie del certificado
        // echo "pem:*****";
        // echo $certificado->pem(), PHP_EOL;
        // echo "pemAsOneLine:*****";
        // echo $certificado->pemAsOneLine(), PHP_EOL;
        // echo "version:*****";
        // echo $certificado->version(), PHP_EOL;
        // echo "validFrom:*****";
        // echo $certificado->validFrom(), PHP_EOL;
        // echo "signatureTypeLN:*****";
        // echo $certificado->signatureTypeLN(), PHP_EOL;
        // echo "signatureTypeNID:*****";
        // echo $certificado->signatureTypeNID(), PHP_EOL;
        // echo "publicKey:*****";
        //var_dump($certificado->publicKey());

        return $certificado;

    }
    public  static function get_certificado($texto,$efirma_file_certificate,$efirma_file_key,$efirma_password)
    {


        //************* $dir = str_replace('\\', '/',getcwd());
        //$dir = str_replace('\\', '/', storage_path('app\efirma/'));
        $dir1=Storage::path('efirma/');
        $dir = str_replace('\\', '/', $dir1);
        //$cerFile = $dir.'/fiel/certificate.cer';
        //$pemKeyFile =$dir.'/fiel/private-key.key';
        // $cerFile = $dir.'/fiel/aaa010101aaa_FIEL.cer';
        // $pemKeyFile =$dir.'/fiel/Claveprivada_FIEL_AAA010101AAA_20170515_120909.key';
        // $passPhrase = '12345678a'; // contraseña para abrir la llave privada

        //************* $cerFile = $dir.'/fiel/'.$efirma_file_certificate;
        //************* $pemKeyFile =$dir.'/fiel/'.$efirma_file_key;

        $cerFile = $dir.$efirma_file_certificate;

        $pemKeyFile =$dir.$efirma_file_key;

        $passPhrase = $efirma_password; // contraseña para abrir la llave privada
        // echo getcwd() ;
        // echo $cerFile;
        // echo $pemKeyFile;
        // echo $passPhrase;

        // echo("OPEN INI");
        // echo($cerFile);

        // echo($pemKeyFile);
        // echo($passPhrase);
        $fiel = Credentials\Credential::openFiles($cerFile, $pemKeyFile, $passPhrase);
        //echo("OPEN FIN");
        // alias de privateKey/sign/verify
        $signature = $fiel->sign($texto);
       // echo base64_encode($signature), PHP_EOL;

        // alias de certificado/publicKey/verify
        $verify = $fiel->verify($texto, $signature);
       //var_dump($verify); // bool(true)

        // objeto certificado
        $certificado = $fiel->certificate();
        // echo "rfc:*****";
        // echo $certificado->rfc(), PHP_EOL; // el RFC del certificado
        // echo "<br>";
        // echo "legalname:*****";
        // echo $certificado->legalName(), PHP_EOL; // el nombre del propietario del certificado
        // echo "<br>";
        // echo "branchname:*****";
        // echo $certificado->branchName(), PHP_EOL; // el nombre de la sucursal (en CSD, en FIEL está vacía)
        // echo "<br>";
        // echo "serialnumber:*****";
        // echo $certificado->serialNumber()->bytes(), PHP_EOL; // número de serie del certificado
        // echo "<br>";
        // echo "pem:*****";
        // echo $certificado->pem(), PHP_EOL;
        // echo "<br>";
        // echo "pemAsOneLine:*****";
        // echo $certificado->pemAsOneLine(), PHP_EOL;
        // echo "<br>";
        // echo "version:*****";
        // echo $certificado->version(), PHP_EOL;
        // echo "<br>";
        // echo "validFrom:*****";
        // echo $certificado->validFrom(), PHP_EOL;
        // echo "<br>";
        // echo "signatureTypeLN:*****";
        // echo $certificado->signatureTypeLN(), PHP_EOL;
        // echo "<br>";
        // echo "signatureTypeNID:*****";
        // echo $certificado->signatureTypeNID(), PHP_EOL;
        // echo "<br>";
        // echo "publicKey:*****";
        // var_dump($certificado->publicKey());
        $proc=$certificado->publicKey()->publicKeyContents();
        $proc=str_replace('-----BEGIN PUBLIC KEY-----','',$proc);
        $proc=str_replace('-----END PUBLIC KEY-----','',$proc);
        $array[0]=$proc;
        $array[1]=$certificado->serialNumber()->bytes();
        $array[2]=$certificado->rfc();
        $array[3]=$certificado->legalName();

        //echo "validFromDateTime:*****";
        $dateTime = new \DateTime();
        $dateTimeImmutable=$certificado->validFromDateTime();
        $dateTime->setTimestamp($dateTimeImmutable->getTimestamp());
        $array[4]= date_format($dateTime, 'd-m-Y');
        //echo "validToDateTime:*****";
        $dateTime1 = new \DateTime();
        $dateTimeImmutable1=$certificado->validToDateTime();
        $dateTime1->setTimestamp($dateTimeImmutable1->getTimestamp());
        $array[5]= date_format($dateTime1, 'd-m-Y');





        return $array;

    }
}

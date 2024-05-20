<?php

namespace App\Utils;

class XmlSigner
{
    public static function signXml($xmlContent, $privateKeyPath, $certificatePath, $certificatePassword)
    {
        // Cargar la clave privada
        $privateKey = openssl_get_privatekey(file_get_contents($privateKeyPath), $certificatePassword);

        // Cargar el certificado
        $certificate = file_get_contents($certificatePath);

        // Crear una estructura de datos XML
        $xml = new \DOMDocument();
        $xml->loadXML($xmlContent);

        // Firmar el XML
        openssl_sign($xml->C14N(), $signature, $privateKey, OPENSSL_ALGO_SHA256);

        // Agregar la firma al XML
        $signatureElement = $xml->createElement('Signature');
        $signatureElement->nodeValue = base64_encode($signature);
        $xml->documentElement->appendChild($signatureElement);

        // Guardar el XML firmado
        $signedXmlContent = $xml->saveXML();

        // Liberar los recursos
        openssl_free_key($privateKey);

        return $signedXmlContent;
    }
}

<?php

namespace App\Http\Controllers;
use App\Utils\XmlSigner;
use Illuminate\Http\Request;

class firmarElectronicaController extends Controller
{

    public function index()
    {
        return view('facturas.index');
    }
    public function generarFactura(Request $request)
    {

        $request->validate([
            'file' => 'required|file',
        ]);
    
        // Verificar si se ha enviado un archivo XML
        if ($request->hasFile('file')) {
            // Obtener el archivo XML enviado
            $xmlFile = $request->file('file');
    
            // Verificar si el archivo es válido
            if ($xmlFile->isValid()) {
                // Obtener el contenido del archivo XML
                $xmlContent = file_get_contents($xmlFile->path());
    
                // Rutas de la clave privada y el certificado
                $privateKeyPath = public_path('private_key.pem');
                $certificatePath = public_path('certificate.pem');
                $certificatePassword = '123456';
    
                // Firmar el XML
                try {
                    $signedXmlContent = XmlSigner::signXml($xmlContent, $privateKeyPath, $certificatePath, $certificatePassword);
    
                    // Lógica para subir la factura firmada electrónicamente
                    // Por ejemplo, guardarla en un almacenamiento en la nube o enviarla por correo electrónico

                    dd($signedXmlContent);
    
                    // En este ejemplo, simplemente retornamos un mensaje de éxito
                    return redirect()->back()->with('success', 'Archivo XML firmado correctamente.');
                } catch (\Exception $e) {
                    // Manejar excepciones
                    return redirect()->back()->with('error', 'Error al firmar el archivo XML: ' . $e->getMessage());
                }
            } else {
                // El archivo no es válido
                return redirect()->back()->with('error', 'El archivo XML no es válido.');
            }
        } else {
            // No se ha enviado un archivo XML
            return redirect()->back()->with('error', 'Debe enviar un archivo XML.');
        }
    }
}

Ejemplo
=======

Ejemplo de listar BHEs
----------------------

El siguiente es un ejemplo básico de cómo obtener datos de un contribuyente utilizando el cliente de API.

Para utilizar el cliente de API de ContaFi, deberás tener definido el token de API y el RUT del contribuyente como variables de entorno.

.. seealso::
    Para más información sobre este paso, referirse al la guía en Configuración.

.. code-block:: php
    <?php

    // Definición de directorio autoload. Necesario si se usa la versión de GitHub.
    require_once __DIR__ . '/vendor/autoload.php';

    // Importaciones del cliente de API de ContaFi
    use contafi\api_client\client\Contribuyentes;

    // Instancia de cliente.
    $client = new Contribuyentes();
    // RUT del contribuyente a consultar.
    $rut = "12345678-9";

    // Respuesta de solicitud HTTP (POST) de emisión de boleta.
    $response = $client->datosContribuyente($rut);

    // Despliegue del resultado.
    echo "\n",$response->getStatusCode();
    echo "\nCONTRIBUYENTE DATOS: \n";
    echo "\n",$response->getBody()->getContents(),"\n";

Ejemplo de emitir una BTE
-------------------------

A continuación se mostrará un segundo ejemplo en donde se podrá emitir una BTE:

.. code-block:: php
    <?php

    declare(strict_types=1);

    // Definición de directorio autoload. Necesario si se usa la versión de GitHub.
    require_once __DIR__ . '/vendor/autoload.php';

    // Importaciones del cliente de API de ContaFi
    use contafi\api_client\client\Bte;

    // Instancia de cliente.
    $client = new Bte();
    // RUT del contribuyente emisor de la BTE.
    $rutEmisor = '12345678-9';

    // Fecha de emisión de la BTE.
    $fecha = date('Y-m-d');

    // Datos de la BTE a emitir.
    $datosBte = [
        'Encabezado' => [
            'IdDoc' => [
                'FchEmis' => $fecha,
            ],
            'Emisor' => [
                'RUTEmisor' => $rutEmisor,
            ],
            'Receptor' => [
                'RUTRecep' => '66666666-6',
                'RznSocRecep' => 'Receptor generico',
                'DirRecep' => 'Santa Cruz',
                'CmnaRecep' => 'Santa Cruz',
            ],
        ],
        'Detalle' => [
            [
                'NmbItem' => 'Prueba integracion ContaFi 1',
                'MontoItem' => 50,
            ],
            [
                'NmbItem' => 'Prueba integracion ContaFi 2',
                'MontoItem' => 100,
            ],
        ],
    ];

    // Obtención de respuesta a la emisión de la BTE.
    $response = $client->emitir($datosBte);

    // Se despliega en consola los resultados.
    echo "\n",$response->getStatusCode();
    echo "\nEMITIR BTE: \n";
    echo "\n",$response->getBody()->getContents(),"\n";

.. seealso::
    Para saber más sobre los parámetros posibles y el cómo consumir las API, referirse a la `documentación de ContaFi. <https://developers.contafi.cl/>`_

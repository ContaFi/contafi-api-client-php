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

    # Definición de directorio autoload. Necesario si se usa la versión de GitHub.
    require_once __DIR__ . '/vendor/autoload.php';

    # Importaciones del cliente de API de ContaFi
    use contafi\api_client\client\Contribuyentes;

    # Instancia de cliente.
    $client = new Contribuyentes();
    # RUT del contribuyente a consultar.
    $rut = "12345678-9";

    # Respuesta de solicitud HTTP (POST) de emisión de boleta.
    $response = $client->datosContribuyente($rut);

    # Despliegue del resultado.
    echo "\n",$response->getStatusCode();
    echo "\nCONTRIBUYENTE DATOS: \n";
    echo "\n",$response->getBody()->getContents(),"\n";



.. seealso::
    Para saber más sobre los parámetros posibles y el cómo consumir las API, referirse a la `documentación de ContaFi. <https://developers.contafi.cl/>`_

<?php

declare(strict_types=1);

/**
 * ContaFi
 * Copyright (C) SASCO SpA (https://sasco.cl)
 *
 * Este programa es software libre: usted puede redistribuirlo y/o modificarlo
 * bajo los términos de la GNU Lesser General Public License (LGPL) publicada
 * por la Fundación para el Software Libre, ya sea la versión 3 de la Licencia,
 * o (a su elección) cualquier versión posterior de la misma.
 *
 * Este programa se distribuye con la esperanza de que sea útil, pero SIN
 * GARANTÍA ALGUNA; ni siquiera la garantía implícita MERCANTIL o de APTITUD
 * PARA UN PROPÓSITO DETERMINADO. Consulte los detalles de la GNU Lesser General
 * Public License (LGPL) para obtener una información más detallada.
 *
 * Debería haber recibido una copia de la GNU Lesser General Public License
 * (LGPL) junto a este programa. En caso contrario, consulte
 * <http://www.gnu.org/licenses/lgpl.html>.
 */

use contafi\api_client\ApiException;
use contafi\api_client\client\Bhe;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Bhe::class)]
/**
 * Clase de pruebas para obtener detalles de una Boleta de Honorarios Electrónica recibida.
 */
class ObtenerDatosBheRecibidaTest extends TestCase
{
    /**
     * Variable que permite desplegar en consola los resultados.
     *
     * @var bool
     */
    protected static $verbose;

    /**
     * Instancia de servicios API Client a través de Bhe.
     *
     * @var Bhe
     */
    protected static $client;

    public static function setUpBeforeClass(): void
    {
        self::$verbose = env('TEST_VERBOSE', false);
        self::$client = new Bhe();
    }

    /**
     * Método de test que prueba el recurso de obtener datos de una BHE recibida.
     *
     * @throws \contafi\api_client\ApiException si la búsqueda falla, si la BHE
     * recibida no existe, o si ocurre un error de conexión.
     * @return void
     */
    public function testObtenerDatosBheRecibida()
    {
        $filtros = [
            'periodo' => date('Ym'),
        ];
        try {
            $listadoBhes = self::$client->listadoBhes($filtros);
            $emisor = json_decode(
                $listadoBhes->getBody()->getContents(),
                true
            )['results'][0]['emisor'];
            $numero = json_decode(
                $listadoBhes->getBody()->getContents(),
                true
            )['results'][0]['numero'];

            $response = self::$client->datosBhe($emisor, $numero);

            $this->assertSame(200, $response->getStatusCode());

            if (self::$verbose) {
                echo "\n",'testObtenerDatosBheRecibida() Bhe: ',$response->getBody()->getContents(),"\n";
            }
        } catch (ApiException $e) {
            throw new ApiException(sprintf(
                '[ApiException %d] %s',
                $e->getCode(),
                $e->getMessage()
            ));
        }
    }
}

<?php

declare(strict_types=1);

/**
 * ContaFi: Cliente de API en PHP.
 * Copyright (C) ContaFi <https://www.contafi.cl>
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
 * Clase de pruebas para observar una Boleta de Honorarios Electrónica recibida.
 */
class ObservarBheRecibidaTest extends TestCase
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

    /**
     * Variable de pruebas de número de BHE.
     *
     * @var int|null
     */
    protected static $testNumero;

    /**
     * RUT del emisor de la BHE observada. Sin puntos y con DV.
     *
     * Ejemplo: 12345678-9
     *
     * @var string
     */
    protected static string $testEmisor;

    public static function setUpBeforeClass(): void
    {
        self::$verbose = env(varname: 'TEST_VERBOSE', default: false);
        self::$client = new Bhe();
        self::$testNumero = env('TEST_NRO_BHE', null);
        self::$testEmisor = env('TEST_EMISOR', '');
    }

    /**
     * Método de test que prueba el recurso de observar una BHE recibida.
     *
     * @throws \contafi\api_client\ApiException si el contribuyente no existe, si la
     * búsqueda falla, o si ocurre un error de conexión.
     * @return void
     */
    public function testObservarBheRecibida(): void
    {
        $data = [
            'causa' => 1,
        ];
        $filtros = [
            'periodo' => env(varname: 'TEST_PERIODO', default: date('Ym')),
        ];
        try {
            if (!isset(self::$testNumero) and self::$testEmisor != '') {

                $listadoBhes = self::$client->listado($filtros);
                $bodyDecoded = json_decode(
                    json: $listadoBhes->getBody()->getContents(),
                    associative: true
                )['results'][0];

                $emisorRut = $bodyDecoded['emisor']['contribuyente']['rut'];
                $emisorDv = $bodyDecoded['emisor']['contribuyente']['dv'];
                self::$testNumero = $bodyDecoded['numero'];

                self::$testEmisor = sprintf('%s-%s', $emisorRut, $emisorDv);
            }
            $response = self::$client->observar(
                self::$testEmisor,
                self::$testNumero,
                $data
            );

            $this->assertSame(200, $response->getStatusCode());

            if (self::$verbose) {
                echo "\n",
                'testObservarBheRecibida() BHE: ',
                $response->getBody()->getContents(),
                "\n";
            }
        } catch (ApiException $e) {
            throw new ApiException(message: sprintf(
                '[ApiException %d] %s',
                $e->getCode(),
                $e->getMessage()
            ));
        }
    }
}

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
use contafi\api_client\client\Bte;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use contafi\tests\Helpers\FunctionHelpers;

#[CoversClass(Bte::class)]
/**
 * Clase de pruebas para anular una Boleta de Terceros Electrónica emitida.
 */
class AnularBteTest extends TestCase
{
    use FunctionHelpers;
    /**
     * Variable que permite desplegar en consola los resultados.
     *
     * @var bool
     */
    protected static $verbose;

    /**
     * Instancia de servicios API Client a través de Bte.
     *
     * @var Bte
     */
    protected static $client;

    /**
     * Número de la BTE por anular.
     *
     * @var int|null
     */
    protected static $testNumero;

    public static function setUpBeforeClass(): void
    {
        self::requireEnv('CONTAFI_API_TOKEN');
        self::$verbose = env(varname: 'TEST_VERBOSE', default: false);
        self::$client = new Bte();
        self::$testNumero = env('TEST_NRO_BTE', null);
    }

    /**
     * Método de test que prueba el recurso de anular una BTE emitida.
     *
     * @throws \contafi\api_client\ApiException si la BTE no existe, si la
     * búsqueda falla, o si ocurre un error de conexión.
     * @return void
     */
    public function testAnularBte(): void
    {
        $data = [
            'causa' => 3,
        ];
        $filtros = [
            'periodo' => env(varname: 'TEST_PERIODO', default: date('Ym')),
        ];
        try {
            if (!isset(self::$testNumero)) {

                $listadoBtes = self::$client->listado(filtros: $filtros);
                $bodyDecoded = json_decode(
                    json: $listadoBtes->getBody()->getContents(),
                    associative: true
                )['results'][0];

                self::$testNumero = $bodyDecoded['numero'];
            }

            $response = self::$client->anular((int)self::$testNumero, $data);

            $this->assertSame(200, $response->getStatusCode());

            if (self::$verbose) {
                echo "\n",
                'testAnularBte() BTE: ',
                $response->getBody()->getContents(),
                "\n";
            }
        } catch (ApiException $e) {
            $this->handleApiException($e);
        }
    }
}

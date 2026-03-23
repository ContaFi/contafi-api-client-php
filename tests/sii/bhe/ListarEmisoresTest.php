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
use contafi\tests\Helpers\FunctionHelpers;

#[CoversClass(Bhe::class)]
/**
 * Clase de pruebas para listar emisores de BHEs recibidas.
 */
class ListarEmisoresTest extends TestCase
{
    use FunctionHelpers;
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
        self::requireEnv('CONTAFI_API_TOKEN');
        self::$verbose = env(varname: 'TEST_VERBOSE', default: false);
        self::$client = new Bhe();
    }

    /**
     * Método de test que prueba el recurso de listar emisores de BHEs recibidas.
     *
     * @throws \contafi\api_client\ApiException si la búsqueda falla, o
     * si ocurre un error de conexión.
     * @return void
     */
    public function testListarEmisores(): void
    {
        $nuevos = env(varname: 'TEST_PERIODO', default: date('Ym'));
        try {
            $response = self::$client->listarEmisores($nuevos);

            $this->assertSame(200, $response->getStatusCode());

            if (self::$verbose) {
                echo "\n",
                'testListarEmisores() Emisores: ',
                $response->getBody()->getContents(),
                "\n";
            }
        } catch (ApiException $e) {
            $this->handleApiException($e);
        }
    }
}

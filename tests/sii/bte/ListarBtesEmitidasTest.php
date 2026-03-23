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
 * Clase de pruebas para listar Boletas de Terceros Electrónicas emitidas.
 */
class ListarBtesEmitidasTest extends TestCase
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

    public static function setUpBeforeClass(): void
    {
        self::requireEnv('CONTAFI_API_TOKEN');
        self::$verbose = env(varname: 'TEST_VERBOSE', default: false);
        self::$client = new Bte();
    }

    /**
     * Método de test que prueba el recurso de listar BTEs emitidas.
     *
     * @throws \contafi\api_client\ApiException si la búsqueda falla, o si
     * ocurre un error de conexión.
     * @return void
     */
    public function testListarBtesEmitidas(): void
    {
        $filtros = [
            'periodo' => env('TEST_PERIODO') ?: date('Ym'),
        ];
        try {
            $response = self::$client->listado(filtros: $filtros);

            $this->assertSame(200, $response->getStatusCode());

            if (self::$verbose) {
                echo "\n",
                'testListarBtesEmitidas() BTEs: ',
                $response->getBody()->getContents(),
                "\n";
            }
        } catch (ApiException $e) {
            $this->handleApiException($e);
        }
    }
}

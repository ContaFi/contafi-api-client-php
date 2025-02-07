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
use contafi\api_client\client\Facturacion;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Facturacion::class)]
/**
 * Clase de pruebas para listar documentos de ventas efectuadas.
 */
class ListarDocumentosVentasTest extends TestCase
{
    /**
     * Variable que permite desplegar en consola los resultados.
     *
     * @var bool
     */
    protected static $verbose;

    /**
     * Instancia de servicios API Client a través de Facturacion.
     *
     * @var Facturacion
     */
    protected static $client;

    public static function setUpBeforeClass(): void
    {
        self::$verbose = env('TEST_VERBOSE', false);
        self::$client = new Facturacion();
    }

    /**
     * Método de test que prueba el recurso de listar DTEs de ventas.
     *
     * @throws \contafi\api_client\ApiException si el contribuyente no existe, si la
     * búsqueda falla, o si ocurre un error de conexión.
     * @return void
     */
    public function testListarDocumentosVentas()
    {
        $filtros = [
            'periodo' => date('Ym'),
        ];
        try {
            $response = self::$client->listadoVentas($filtros);

            $this->assertSame(200, $response->getStatusCode());

            if (self::$verbose) {
                echo "\n",'testListarDocumentosVentas() Documentos: ',$response->getBody()->getContents(),"\n";
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

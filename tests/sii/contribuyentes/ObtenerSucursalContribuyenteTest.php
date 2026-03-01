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
use contafi\api_client\client\Contribuyentes;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

// TODO: Corregir documentación.
#[CoversClass(Contribuyentes::class)]
/**
 * Clase de pruebas para obtener una sucursal específica del contribuyente.
 */
class ObtenerSucursalContribuyenteTest extends TestCase
{
    /**
     * Variable que permite desplegar en consola los resultados.
     *
     * @var bool
     */
    protected static $verbose;

    /**
     * Instancia de servicios API Client a través de Contribuyentes.
     *
     * @var Contribuyentes
     */
    protected static $client;

    /**
     * RUT del emisor a buscar.
     *
     * @var string
     */
    protected static $emisorRut;

    /**
     * ID de la sucursal del contribuyente.
     *
     * @var int|null
     */
    protected static $sucursal;

    public static function setUpBeforeClass(): void
    {
        self::$verbose = env(varname: 'TEST_VERBOSE', default: false);
        self::$client = new Contribuyentes();
        self::$emisorRut = env('CONTAFI_CONTRIBUYENTE_RUT', '76192083-9');
        self::$sucursal = (int)env('TEST_COD_SUCURSAL', null);
    }

    /**
     * Método de test que prueba el recurso de obtener el detalle de una sucursal
     * de un contribuyente.
     *
     * @throws \contafi\api_client\ApiException si el contribuyente no existe, si
     * la sucursal no existe, si la búsqueda falla, o si ocurre un error de conexión.
     * @return void
     */
    public function testObtenerSucursalContribuyente(): void
    {
        try {
            if (!isset(self::$sucursal)) {
                $datos = self::$client->datos(self::$emisorRut);
                $datosDec = json_decode(
                    json: $datos->getBody()->getContents(),
                    associative: true
                );
                self::$sucursal = $datosDec['sucursales'][0]['codigo'];
            }

            $response = self::$client->sucursal(self::$sucursal);

            $this->assertSame(200, $response->getStatusCode());

            if (self::$verbose) {
                echo "\n",
                'testObtenerSucursalContribuyente() Sucursal: ',
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

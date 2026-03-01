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

#[CoversClass(Contribuyentes::class)]
/**
 * Clase de pruebas para quitar permisos a un rol del contribuyente.
 */
class QuitarPermisoRolTest extends TestCase
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

    public static function setUpBeforeClass(): void
    {
        self::$verbose = env(varname: 'TEST_VERBOSE', default: false);
        self::$client = new Contribuyentes();
    }

    /**
     * Método de test que prueba el recurso de quitar uno o más permisos a un
     * rol específico en el contribuyente.
     *
     * @throws \contafi\api_client\ApiException si el permiso o rol no existen, si la
     * búsqueda falla, o si ocurre un error de conexión.
     * @return void
     */
    public function testQuitarPermisoRol(): void
    {
        try {
            $response = self::$client->obtenerRoles();
            $rolId = (int)env(
                varname: 'TEST_ROL_ID',
                default: json_decode(
                    json: $response->getBody()->getContents(),
                    associative: true
                )[0]['id']
            );
            $permiso = 'bhe_ver';

            $response = self::$client->quitarPermisoRol(
                $rolId,
                $permiso
            );

            $this->assertSame(200, $response->getStatusCode());

            if (self::$verbose) {
                echo "\n",
                'testQuitarPermisoRol() Rol: ',
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

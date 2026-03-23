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
use contafi\tests\Helpers\FunctionHelpers;

// TODO: Corregir documentación.
#[CoversClass(Contribuyentes::class)]
/**
 * Clase de pruebas para agregar permisos a un rol específico.
 */
class AgregarPermisoRolTest extends TestCase
{
    use FunctionHelpers;
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
        self::requireEnv('CONTAFI_API_TOKEN');
        self::$verbose = env(varname: 'TEST_VERBOSE', default: false);
        self::$client = new Contribuyentes();
    }

    /**
     * Método de test que prueba el recurso de agregar permisos a un rol específico
     * para usuarios autorizados.
     *
     * @throws \contafi\api_client\ApiException si el rol o el permiso no existen,
     * si la búsqueda falla, o si ocurre un error de conexión.
     * @return void
     */
    public function testAgregarPermisoRol(): void
    {
        try {
            $response = self::$client->obtenerRoles();

            $idRoles = json_decode(
                json: $response->getBody()->getContents(),
                associative: true
            );

            if($idRoles === []){
                $this->markTestSkipped("No existen roles para el contribuyente");
            }

            $rolId = (int)env(
                varname: 'TEST_ROL_ID',
                default: $idRoles[0]['id']
            );

            $data = [
                "rol_id" => $rolId,
                "permisos" => ['bhe_ver'],
            ];
            $response = self::$client->agregarPermisoRol($data);

            $this->assertSame(200, $response->getStatusCode());

            if (self::$verbose) {
                echo "\n",
                'testAgregarPermisoRol() Rol: ',
                $response->getBody()->getContents(),
                "\n";
            }
        } catch (ApiException $e) {
            $this->handleApiException($e);
        }
    }
}

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
use contafi\api_client\client\Bte;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Bte::class)]
/**
 * Clase de pruebas para emitir una Boleta de Terceros Electrónica emitida.
 */
class EmitirBteTest extends TestCase
{
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
     * RUT del emisor de la BTE.
     *
     * @var string
     */
    protected static $emisorRut;

    /**
     * Cuerpo de la petición con los datos de la BTE a emitir.
     *
     * @var array
     */
    protected static $datosBte = [
        'Encabezado' => [
            'IdDoc' => [
                'FchEmis' => null,
            ],
            'Emisor' => [
                'RUTEmisor' => null,
            ],
            'Receptor' => [
                'RUTRecep' => '66666666-6',
                'RznSocRecep' => 'Receptor generico',
                'DirRecep' => 'Santa Cruz',
                'CmnaRecep' => 'Santa Cruz',
            ],
        ],
        'Detalle' => [
            [
                'NmbItem' => 'Prueba integracion ContaFi 1',
                'MontoItem' => 50,
            ],
            [
                'NmbItem' => 'Prueba integracion ContaFi 2',
                'MontoItem' => 100,
            ],
        ],
    ];

    public static function setUpBeforeClass(): void
    {
        self::$verbose = env(varname: 'TEST_VERBOSE', default: false);
        self::$client = new Bte();
        self::$emisorRut = env('CONTAFI_CONTRIBUYENTE_RUT', '');
        self::$datosBte['Encabezado']['IdDoc']['FchEmis'] = date('Y-m-d');
        self::$datosBte['Encabezado']['Emisor']['RUTEmisor'] = self::$emisorRut;
    }

    /**
     * Método de test que prueba el recurso de emitir una BTE.
     *
     * @throws \contafi\api_client\ApiException si los datos son erróneos,
     * o si ocurre un error de conexión.
     * @return void
     */
    public function testEmitirBte(): void
    {
        try {
            $response = self::$client->emitir(self::$datosBte);

            $this->assertSame(200, $response->getStatusCode());

            if (self::$verbose) {
                echo "\n",
                'testEmitirBte() BTE: ',
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

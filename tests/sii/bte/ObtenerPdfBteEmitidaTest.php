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
 * Clase de pruebas para descargar el PDF de una Boleta de Terceros Electrónica emitida.
 */
class ObtenerPdfBteEmitidaTest extends TestCase
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
     * Número de BTE
     *
     * @var int|null
     */
    protected static $testNumero;

    public static function setUpBeforeClass(): void
    {
        self::$verbose = env(varname: 'TEST_VERBOSE', default: false);
        self::$client = new Bte();
        self::$testNumero = (int)env('TEST_NRO_BTE', null);
    }

    /**
     * Método de test que prueba el recurso de descargar un archivo PDF de
     * una BTE emitida.
     *
     * @throws \contafi\api_client\ApiException si la BTE no existe, si la
     * búsqueda falla, o si ocurre un error de conexión.
     * @return void
     */
    public function testObtenerPdfBteEmitida(): void
    {
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
            $response = self::$client->pdf(numero: self::$testNumero);

            $this->assertSame(200, $response->getStatusCode());

            // Ruta base para el directorio actual (archivo ejecutándose en
            // "tests/dte_facturacion")
            $currentDir = __DIR__;

            // Nueva ruta relativa para guardar el archivo PDF en "tests/archivos"
            $targetDir = dirname(
                dirname($currentDir)
            ) . '/archivos/bte_emitidas_pdf';

            // Define el nombre del archivo PDF en el nuevo directorio
            $filename = $targetDir . '/' . sprintf(
                'CONTAFI_BTE_%d.pdf',
                self::$testNumero
            );

            // Verifica si el directorio existe, si no, créalo
            if (!is_dir($targetDir)) {
                mkdir(
                    directory: $targetDir,
                    permissions: 0777,
                    recursive: true
                );
            }

            // Se genera el archivo PDF.
            file_put_contents($filename, $response->getBody());

            if (self::$verbose) {
                echo "\n",
                'testObtenerPdfBteEmitida() Archivo: ',
                $filename,
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

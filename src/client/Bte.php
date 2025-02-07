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

namespace contafi\api_client\client;

use contafi\api_client\ApiBase;

/**
 * Módulo que permite gestionar las BTE registradas y/o sincronizadas en ContaFi.
 */
class Bte extends ApiBase
{
    /**
     * Módulo que permite gestionar las BHE registradas y/o sincronizadas en ContaFi.
     *
     * @param string $token Token de autenticación del usuario. Si no se
     * proporciona, se intentará obtener de una variable de entorno.
     * @param string $rut RUT del emisor de ContaFi. Si no se proporciona,
     * se intentará obtener de una variable de entorno.
     * @param string $url URL base de la API. Si no se proporciona, se
     * usará una URL por defecto.
     */
    public function __construct(
        string $token = null,
        string $rut = null,
        string $url = null
    ) {
        parent::__construct($token, $rut, $url);
    }

    /**
     * Recurso que permite emitir una BTE.
     *
     * @param array $body Datos de la BTE a emitir.
     * @return \Psr\Http\Message\ResponseInterface Respuesta con la BTE emitida.
     */
    public function emitirBte(array $body)
    {
        $url = '/bte/emitir';

        $response = $this->post($url, $body);

        return $response;
    }

    /**
     * Recurso que permite obtener el listado paginado de boletas de terceros
     * electrónicas emitidas.
     *
     * @param array $filtros Filtros adicionales.
     * @return \Psr\Http\Message\ResponseInterface Respuesta con el listado
     * de BTEs emitidas.
     */
    public function listadoBtes(array $filtros = [])
    {
        $url = '/bte/boletas';

        if (count($filtros) > 0) {
            $queryString = http_build_query($filtros);
            $url = sprintf('%s?%s', $url, $queryString);
        }

        $response = $this->get($url);

        return $response;
    }

    /**
     * Recurso para obtener los datos de una boleta de terceros electrónica emitida.
     *
     * @param int $numero Número de la BTE a consultar.
     * @return \Psr\Http\Message\ResponseInterface Respuesta con los datos de la BTE.
     */
    public function datosBte(int $numero)
    {
        $url = sprintf('/bte/boletas/%d', $numero);

        $response = $this->get($url);

        return $response;
    }

    /**
     * Recurso para obtener el HTML de una boleta de terceros electrónica emitida.
     *
     * @param int $numero Número de la BTE a consultar.
     * @return \Psr\Http\Message\ResponseInterface Respuesta con el contenido
     * de la BTE en formato HTML.
     */
    public function htmlBte(int $numero)
    {
        $url = sprintf('/bte/html/%d', $numero);

        $response = $this->get($url);

        return $response;
    }

    /**
     * Recurso para obtener el PDF de una boleta de terceros electrónica emitida.
     *
     * @param int $numero Número de la BTE a consultar.
     * @param array $filtros Filtros adicionales.
     * @return \Psr\Http\Message\ResponseInterface Respuesta con el contenido
     * de la BTE en formato PDF.
     */
    public function pdfBte(int $numero, array $filtros = [])
    {
        $url = sprintf('/bte/pdf/%d', $numero);

        if (count($filtros) > 0) {
            $queryString = http_build_query($filtros);
            $url = sprintf('%s?%s', $url, $queryString);
        }

        $response = $this->get($url);

        return $response;
    }

    /**
     * Recurso que permite anular una boleta de terceros electrónica previamente emitida.
     *
     * @param int $numero Número de la BTE a anular.
     * @param array $body Datos a entregar (causa de anulación).
     * @return \Psr\Http\Message\ResponseInterface Respuesta con la BTE anulada.
     */
    public function anularBte(int $numero, array $body)
    {
        $url = sprintf('/bte/anular/%d', $numero);

        $response = $this->post($url, $body);

        return $response;
    }

    /**
     * Recurso que permite calcular el monto líquido a partir del monto bruto.
     *
     * @param int $bruto Monto bruto a convertir.
     * @param string $periodo Periodo a considerar para la conversión.
     * @return \Psr\Http\Message\ResponseInterface Respuesta con el valor líquido calculado.
     */
    public function calcularMontoLiquido(int $bruto, string $periodo)
    {
        $url = sprintf('/bte/liquido/%d/%s', $bruto, $periodo);

        $response = $this->get($url);

        return $response;
    }

    /**
     * Recurso que permite calcular el monto bruto a partir del monto líquido.
     *
     * @param int $liquido Monto líquido a convertir.
     * @param string $periodo Periodo a considerar para la conversión.
     * @return \Psr\Http\Message\ResponseInterface Respuesta con el valor bruto calculado.
     */
    public function calcularMontoBruto(int $liquido, string $periodo)
    {
        $url = sprintf('/bte/bruto/%d/%s', $liquido, $periodo);

        $response = $this->get($url);

        return $response;
    }

    /**
     * Recurso que permite obtener el listado paginado de receptores asociados a las BTE.
     *
     * @return \Psr\Http\Message\ResponseInterface Listado con los receptores
     * asociados a las BTE.
     */
    public function listarReceptores()
    {
        $url = '/bte/receptores';

        $response = $this->get($url);

        return $response;
    }
}

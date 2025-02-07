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
 * Módulo que permite gestionar las BHE registradas y/o sincronizadas en ContaFi.
 */
class Bhe extends ApiBase
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
     * Recurso que permite obtener el listado paginado de boletas de honorarios electrónicas recibidas.
     *
     * @param array $filtros Filtros de búsqueda.
     * @return \Psr\Http\Message\ResponseInterface Respuesta con el listado de BHEs recibidas.
     */
    public function listadoBhes(array $filtros)
    {
        $url = '/bhe/boletas';

        if (count($filtros) > 0) {
            $queryString = http_build_query($filtros);
            $url = sprintf('%s?%s', $url, $queryString);
        }

        $response = $this->get($url);

        return $response;
    }

    /**
     * Recurso para obtener los datos de una boleta de honorarios electrónica recibida.
     *
     * @param string $emisor RUT del emisor de la BHE, sin puntos y con DV.
     * @param int $numero Número de la BHE.
     * @return \Psr\Http\Message\ResponseInterface Respuesta con los datos de la BHE consultada.
     */
    public function datosBhe(string $emisor, int $numero)
    {
        $url = sprintf('/bhe/boletas/%s/%d', $emisor, $numero);

        $response = $this->get($url);

        return $response;
    }

    /**
     * Recurso para obtener el PDF de una boleta de honorarios electrónica recibida.
     *
     * @param string $emisor RUT del emisor de la BHE, sin puntos y con DV.
     * @param int $numero Número de la BHE.
     * @param array $filtros Filtros adicionales.
     * @return \Psr\Http\Message\ResponseInterface Respuesta con los datos del PDF
     * de la BHE consultada.
     */
    public function pdfBhe(string $emisor, int $numero, array $filtros = [])
    {
        $url = sprintf('/bhe/pdf/%s/%d', $emisor, $numero);

        if (count($filtros) > 0) {
            $queryString = http_build_query($filtros);
            $url = sprintf('%s?%s', $url, $queryString);
        }

        $response = $this->get($url);

        return $response;
    }

    /**
     * Recurso que permite observar una boleta de honorarios electrónica previamente recibida.
     *
     * @param string $emisor RUT del emisor de la BHE, sin puntos y con DV.
     * @param int $numero Número de la BHE.
     * @param array $body Datos de la observación de la BHE (causa).
     * @return \Psr\Http\Message\ResponseInterface Respuesta con la BHE observada
     */
    public function observarBhe(string $emisor, int $numero, array $body)
    {
        $url = sprintf('/bhe/observar/%s/%d', $emisor, $numero);

        $response = $this->post($url, $body);

        return $response;
    }

    /**
     * Recurso que permite obtener el listado paginado de emisores asociados a las BHE.
     *
     * @param string $nuevos Emisores que ha emitido por primera vez una
     * BHE en el período indicado
     * @return \Psr\Http\Message\ResponseInterface Respuesta con el listado de emisores.
     */
    public function listarEmisores(string $nuevos)
    {
        $url = sprintf('/bhe/emisores?nuevos=%s', $nuevos);

        $response = $this->get($url);

        return $response;
    }
}

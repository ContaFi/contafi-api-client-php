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

namespace contafi\api_client\client;

use contafi\api_client\ApiBase;
use Psr\Http\Message\ResponseInterface;

/**
 * Módulo que permite gestionar proveeedores, compras y ventas con facturación (DTE).
 */
class Facturacion extends ApiBase
{
    /**
     * Módulo que permite gestionar proveeedores, compras y ventas con
     * facturación (DTE).
     *
     * @param string|null $token Token de autenticación del usuario. Si no se
     * proporciona, se intentará obtener de una variable de entorno.
     * @param string|null $rut RUT del emisor de ContaFi. Si no se proporciona,
     * se intentará obtener de una variable de entorno.
     * @param string|null $url URL base de la API. Si no se proporciona, se
     * usará una URL por defecto.
     */
    public function __construct(
        string|null $token = null,
        string|null $rut = null,
        string|null $url = null
    ) {
        parent::__construct($token, $rut, $url);
    }

    /**
     * Recurso que permite obtener el listado paginado de resumenes asociados a ventas.
     *
     * @param string $periodo Periodo donde obtener el listado de resumen de ventas.
     * @return \Psr\Http\Message\ResponseInterface Respuesta con el listado
     * paginado de ventas.
     */
    public function resumenVentasSinDetalle(string $periodo): ResponseInterface
    {
        $url = sprintf('/dte/ventas/resumen?periodo=%s', $periodo);

        $response = $this->get($url);

        return $response;
    }

    /**
     * Recurso que permite obtener el listado paginado de documentos tributarios
     * electrónicos asociados a ventas.
     *
     * @param array $filtros Filtros de búsqueda.
     * @return \Psr\Http\Message\ResponseInterface Respuesta con el listado
     * paginado de DTEs de ventas, con detalle.
     */
    public function listadoVentas(array $filtros = []): ResponseInterface
    {
        $url = '/dte/ventas';

        if (count($filtros) > 0) {
            $queryString = http_build_query($filtros);
            $url = sprintf('%s?%s', $url, $queryString);
        }

        $response = $this->get($url);

        return $response;
    }

    /**
     * Recurso que permite obtener el listado paginado de documentos
     * tributarios electrónicos asociados a compras.
     *
     * @param int $estado Estado del documento en el registro de compras.
     * @param array $filtros Filtros de búsqueda.
     * @return \Psr\Http\Message\ResponseInterface Respuesta con el listado
     * paginado de DTEs de compras, con detalle.
     */
    public function listadoCompras(int $estado, array $filtros): ResponseInterface
    {
        $url = sprintf('/dte/compras?estado=%d', $estado);

        if (count($filtros) > 0) {
            $queryString = http_build_query($filtros);
            $url = sprintf('%s&%s', $url, $queryString);
        }

        $response = $this->get($url);

        return $response;
    }

    /**
     * Recurso que permite obtener el listado paginado de clientes
     * asociados a ventas.
     *
     * @return \Psr\Http\Message\ResponseInterface Respuesta con el listado
     * de clientes de ventas.
     */
    public function listadoClientes(): ResponseInterface
    {
        $url = '/dte/clientes';

        $response = $this->get($url);

        return $response;
    }

    /**
     * Recurso que permite obtener el listado paginado de proveedores
     * asociados a compras.
     *
     * @return \Psr\Http\Message\ResponseInterface Respuesta con el listado
     * de proveedores de compras.
     */
    public function listadoProveedores(): ResponseInterface
    {
        $url = '/dte/proveedores';

        $response = $this->get($url);

        return $response;
    }
}

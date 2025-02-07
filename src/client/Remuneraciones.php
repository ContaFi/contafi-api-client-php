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
 * Módulo que permite obtener información de las remuneraciones.
 */
class Remuneraciones extends ApiBase
{
    /**
     * Módulo que permite obtener información de las remuneraciones.
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
     * Recurso que permite obtener el listado paginado de remuneraciones del contribuyente.
     *
     * @param string $periodo Periodo de búsqueda para las remuneraciones.
     * @return \Psr\Http\Message\ResponseInterface Respuesta con el
     * listado de remuneraciones.
     */
    public function listadoRemuneraciones(string $periodo = null)
    {
        $url = '/remuneraciones';

        if (isset($periodo)) {
            $url = sprintf('%s?periodo=%s', $url, $periodo);
        }

        $response = $this->get($url);

        return $response;
    }
}

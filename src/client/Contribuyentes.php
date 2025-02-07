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
 * Módulo que permite gestionar contribuyentes en ContaFi, junto con los roles y permisos disponibles.
 */
class Contribuyentes extends ApiBase
{
    /**
     * Módulo que permite gestionar contribuyentes en ContaFi, junto con los roles y permisos disponibles.
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
     * Recurso que permite obtener la estadística de un contribuyente a partir de su RUT.
     *
     * @return \Psr\Http\Message\ResponseInterface Respuesta con las estadísticas.
     */
    public function estadisticasContribuyente()
    {
        $url = '/contribuyentes/estadisticas';

        $response = $this->get($url);

        return $response;
    }

    /**
     * Recurso que permite obtener los datos de un contribuyente a partir de su RUT.
     *
     * @param string $rut RUT del contribuyente a consultar, sin puntos y con DV.
     * @return \Psr\Http\Message\ResponseInterface Respuesta con los datos del contribuyente.
     */
    public function datosContribuyente(string $rut)
    {
        $url = sprintf('/contribuyentes/%s', $rut);

        $response = $this->get($url);

        return $response;
    }

    /**
     * Recurso que permite obtener los datos de una sucursal de un contribuyente a partir de su código.
     *
     * @param int $sucursal ID de la sucursal a consultar.
     * @return \Psr\Http\Message\ResponseInterface Respuesta con los datos de la sucursal.
     */
    public function sucursalContribuyente(int $sucursal)
    {
        $url = sprintf('/contribuyentes/sucursales/%d', $sucursal);

        $response = $this->get($url);

        return $response;
    }

    /**
     * Recurso que permite autorizar un usuario con cierto rol en un contribuyente.
     *
     * @param array $body Datos como el nombre de usuario y rol a asignar.
     * @return \Psr\Http\Message\ResponseInterface Respuesta con la información
     * del usuario autorizado.
     */
    public function agregarUsuarioAutorizado(array $body)
    {
        $url = '/contribuyentes/usuarios';

        $response = $this->put($url, $body);

        return $response;
    }

    /**
     * Recurso que permite quitar a un usuario con cierto rol en un contribuyente.
     *
     * @param string $usuario Nombre del usuario a remover.
     * @param int $rol Rol del usuario.
     * @return \Psr\Http\Message\ResponseInterface Respuesta con la información del usuario removido.
     */
    public function quitarUsuarioAutorizado(string $usuario, int $rol)
    {
        $url = sprintf(
            '/contribuyentes/usuarios/%s/%d',
            $usuario,
            $rol
        );

        $response = $this->delete($url);

        return $response;
    }

    /**
     * Recurso que entrega los roles de un contribuyente.
     *
     * @return \Psr\Http\Message\ResponseInterface Respuesta con el detalle de cada rol.
     */
    public function obtenerRoles()
    {
        $url = '/contribuyentes/roles';

        $response = $this->get($url);

        return $response;
    }

    /**
     * Recurso que permite agregar permisos a un rol.
     *
     * @param array $body Datos que incluyen el rol a modificar y sus permisos.
     * @return \Psr\Http\Message\ResponseInterface Respuesta con el rol modificado.
     */
    public function agregarPermisoRol(array $body)
    {
        $url = '/contribuyentes/roles';

        $response = $this->put($url, $body);

        return $response;
    }

    /**
     * Recurso que permite quitar un permiso asociado a un rol de un contribuyente.
     *
     * @param int $idRol Identificador único del rol
     * @param string $permiso Permiso que se desea remover
     * @return \Psr\Http\Message\ResponseInterface Respuesta con el rol modificado.
     */
    public function quitarPermisoRol(int $idRol, string $permiso)
    {
        $url = sprintf('/contribuyentes/roles/%d/%s', $idRol, $permiso);

        $response = $this->delete($url);

        return $response;
    }
}

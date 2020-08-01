<?php 

    if($peticionAjax)
        require_once "../modelos/administradorModelo.php";
    else 
        require_once "./modelos/administradorModelo.php";

    class administradorControlador extends administradorModelo {

        //CONTROLAR PARA AGREGAR ADMINISTRADORES
        public function agregar_usuario_controlador()
        {
            $dni = mainModel::limpiar_Cadena($_POST['dni-reg']);
            $nombre = mainModel::limpiar_Cadena($_POST['nombre-reg']);
            $apellido = mainModel::limpiar_Cadena($_POST['apellido-reg']);
            $telefono = mainModel::limpiar_Cadena($_POST['telefono-reg']);
            $direccion = mainModel::limpiar_Cadena($_POST['direccion-reg']);

            $usuario = mainModel::limpiar_Cadena($_POST['usuario-reg']);
            $pass1 = mainModel::limpiar_Cadena($_POST['password1-reg']);
            $pass2 = mainModel::limpiar_Cadena($_POST['password2-reg']);
            $email = mainModel::limpiar_Cadena($_POST['email-reg']);
            $genero = mainModel::limpiar_Cadena($_POST['optionsGenero']);
            $privilegio = mainModel::desctyption($_POST['optionsPrivilegio']);
            $privilegio = mainModel::limpiar_Cadena($privilegio);

            $foto = ($genero == 'Masculino') ? "Male3Avatar.png" : "Famele3Avatar.png";

            
            if($privilegio < 1 || $privilegio > 2){
                $alerta= [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El nivel de privilegio que intenta asignar es incorrecto",
                    "Tipo"=> "error"
                ];
            }else{
                if($pass1 != $pass2){
                    $alerta= [
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"Las contraseñas que acabas de ingresar no coinciden, intente nuevamente",
                        "Tipo"=> "error"
                    ];
                }else{
                    
                    $consulta1 = mainModel::ejecutar_consulta_simple("SELECT numIdentidad from usuario where numIdentidad = '$dni'");
                    if($consulta1->rowCount() >=1 )
                    {
                        $alerta= [
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"El DNI que acaba de ingresar ya se encuentra registrado en el sistema",
                            "Tipo"=> "error"
                        ];
                    }
                    else{
                        if($email != "")
                        {
                            $consulta2 = mainModel::ejecutar_consulta_simple("SELECT email FROM usuario WHERE email = '$email'");
                            $ec=$consulta2->rowCount();
                        }else
                            $ec=0;
                        
                        if($ec >= 1){
                            $alerta= [
                                "Alerta"=>"simple",
                                "Titulo"=>"Ocurrió un error inesperado",
                                "Texto"=>"El DNI que acaba de ingresar ya se encuentra registrado en el sistema",
                                "Tipo"=> "error"
                            ];
                        }else{
    
                            $consulta3 = mainModel::ejecutar_consulta_simple("SELECT cuentaUsuario from usuario where cuentaUsuario = '$usuario'");
                            if($consulta3->rowCount() >= 1 )
                            {
                                $alerta= [
                                    "Alerta"=>"simple",
                                    "Titulo"=>"Ocurrió un error inesperado",
                                    "Texto"=>"El Usuario que acaba de ingresar ya se encuentra registrado en el sistema",
                                    "Tipo"=> "error"
                                ];
                            }else{
    
                                $clave  = mainModel::encryption($pass1);
    
                                $dataAC = [
                                    "numiden"=>$dni,
                                    "nomCompleto"=>$nombre.' '.$apellido,
                                    "usuario"=>$usuario,
                                    "clave"=>$clave,
                                    "genero"=>$genero,
                                    "rol"=>$privilegio,
                                    "email"=>$email,
                                    "telefono"=>$telefono,
                                    "direccion"=>$direccion,
                                    "foto"=>$foto,
                                    "estado"=>0
                                ];
                                
                                $guardarCuenta = administradorModelo::agregar_cuenta_modelo($dataAC);
    
                                if($guardarCuenta->rowCount() >= 1){
                                    $alerta= [
                                        "Alerta"=>"limpiar",
                                        "Titulo"=>"Cuenta Registrada",
                                        "Texto"=>"La cuenta se registro exitosamente en el sistema.",
                                        "Tipo"=> "success"
                                    ];
                                }else{
                                    $alerta= [
                                        "Alerta"=>"simple",
                                        "Titulo"=>"Ocurrió un error inesperado",
                                        "Texto"=>"No hemos podido registrar el usuario.",
                                        "Tipo"=> "error"
                                    ];
                                }
                            }
                        }
                    }
                }
            }

            return mainModel::sweet_alert($alerta);

        }

        //CONTROLAR PARA PAGINAR ADMINISTRADORES
        public function paginador_administrador_controlador($pagina,$registro,$privilegios,$codigo,$busqueda)
        {
            $pagina = mainModel::limpiar_Cadena($pagina);
            $registro = mainModel::limpiar_Cadena($registro);
            $privilegios = mainModel::limpiar_Cadena($privilegios);
            $codigo = mainModel::limpiar_Cadena($codigo);
            $busqueda = mainModel::limpiar_Cadena($busqueda);
            $estado = 0;
            $tabla="";

            $pagina = (isset($pagina) && $pagina > 0 ) ? (int)$pagina : 1 ;
            $inicio = ($pagina > 0) ? (($pagina*$registro) - $registro ) : 0 ;


            if(isset($busqueda) && $busqueda != "")
            {
                $consulta="SELECT   SQL_CALC_FOUND_ROWS * 
                           FROM     usuario 
                           WHERE    ((nombreCompleto LIKE '%$busqueda%') or (numIdentidad LIKE '%$busqueda%') or (email LIKE '%$busqueda%')) AND 
                                    codUsuario != '$codigo' AND idRol != '1' AND Estado = '$estado' ORDER BY nombreCompleto ASC LIMIT $inicio,$registro" ;
                
                $paginaURL = "adminsearch";

            }else
            {
                $consulta="SELECT SQL_CALC_FOUND_ROWS * FROM usuario WHERE codUsuario != '$codigo' and idRol != '1' and Estado = '$estado' ORDER BY nombreCompleto ASC LIMIT $inicio,$registro ";
                $paginaURL = "adminlist";
            }
            
            $conexion = mainModel::conectar();
            $datos = $conexion->query($consulta);

            $datos = $datos->fetchAll();
            $total = $conexion->query("SELECT FOUND_ROWS()");
            $total = (int)$total->fetchColumn();

            $Npaginas = ceil($total/$registro);

            // CABECERA TABLA ADMINISTRACION USUARIOS
            $tabla.='	<div class="table-responsive">
                            <table class="table table-hover text-center">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">DNI</th>
                                        <th class="text-center">NOMBRES</th>
                                        <th class="text-center">USUARIO</th>
                                        <th class="text-center">TELÉFONO</th>';

                                    if($privilegios == 1)
                                    {
                                        $tabla.=
                                        '
                                        <th class="text-center">A. CUENTA</th>
                                        <th class="text-center">A. DATOS</th>
                                        <th class="text-center">ELIMINAR</th>
                                        ';
                                    }else
                                    {
                                        $tabla.=
                                        '
                                        <th class="text-center">A. CUENTA</th>
                                        <th class="text-center">A. DATOS</th>
                                        ';
                                    }
                                        
            $tabla.='               </tr>
                                </thead>
                                <tbody> 
                ';

            // CUERPO TABLA ADMINISTRACION USUARIOS
            if($total>=1 && $pagina<=$Npaginas)
            {
                $contador=$inicio+1;
                foreach ($datos as $rows) {
                    
                    $codusu = $rows['codUsuario'];
                    $tabla.='
                        <tr>
                            <td>'.$contador.'</td>
                            <td>'.$rows['numIdentidad'].'</td>
                            <td>'.$rows['nombreCompleto'].'</td>
                            <td>'.$rows['cuentaUsuario'].'</td>
                            <td>'.$rows['telefono'].'</td>';

                    if($privilegios == 1)
                    {
                        $tabla.=
                            '
                            <td>
                                <a href="'.SERVERURL.'myaccount/'.mainModel::encryption($codusu).'/" class="btn btn-success btn-raised btn-xs">
                                    <i class="zmdi zmdi-refresh"></i>
                                </a>
                            </td>
                            <td>
                                <a href="'.SERVERURL.'mydata/'.mainModel::encryption($codusu).'/" class="btn btn-success btn-raised btn-xs">
                                    <i class="zmdi zmdi-refresh"></i>
                                </a>
                            </td>
                            <td>
                                <form action="'.SERVERURL.'ajax/administradorAjax.php" method="POST" class="FormularioAjax" data-form="delete" entype="multipart/form-data" autocomplete ="off">
                                    <input type ="hidden" name ="codigo-del" value="'.mainModel::encryption($codusu).'">
                                    <input type ="hidden" name ="prigilegio-admin" value="'.mainModel::encryption($privilegios).'">
                                    <button type="submit" class="btn btn-danger btn-raised btn-xs">
                                        <i class="zmdi zmdi-delete"></i>
                                    </button>
                                    <div class="RespuestaAjax"></div>
                                </form>
                            </td>';
                    }
                    else{
                        $tabla.=
                            '
                            <td>
                                <a href="'.SERVERURL.'/myaccount/'.mainModel::encryption($codusu).'/" class="btn btn-success btn-raised btn-xs">
                                    <i class="zmdi zmdi-refresh"></i>
                                </a>
                            </td>
                            <td>
                                <a href="'.SERVERURL.'/mydata/'.mainModel::encryption($codusu).'/" class="btn btn-success btn-raised btn-xs">
                                    <i class="zmdi zmdi-refresh"></i>
                                </a>
                            </td>

                            ';                            
                    }

                    $tabla.='
                        </tr>                    
                        '; 
                    $contador++;
                }
            }else{

                if($total >=1 )
                {    
                    $tabla.='
                            <tr>
                                <td colspan ="5">
                                    <a href="'.SERVERURL.$paginaURL.'/" class="btn-sm btn-info btn-raised">Haga click aquí para recargar el listado</a>
                                </td>
                            </tr>
                    '; 
                }else
                {
                    $tabla.='
                            <tr>
                                <td colspan ="5">No hay registros en el sistema.</td>
                            </tr>
                    '; 
                }

            }

            // CIERRE TABLA ADMINISTRACION USUARIOS
            $tabla.='		    </tbody>
                            </table>
                        </div>';

            // PAGINADOR ADMINISTRACION USUARIOS
            if($total>=1 && $pagina<=$Npaginas)
            {
                $tabla.='
                    <nav class="text-center">
                        <ul class="pagination pagination-sm">';

                if($pagina == 1)
                {
                    $tabla.='
                           <li class="disabled"><a href="javascript:void(0)"><i class="zmdi zmdi-chevron-left"></i></a></li>
                    ';
                }else{
                    $tabla.='
                            <li><a href="'.SERVERURL.$paginaURL.'/'.($pagina-1).'/"><i class="zmdi zmdi-chevron-left"></i></a></li>
                    ';
                }

                for($i = 1; $i <= $Npaginas; $i++){
                    if($pagina == $i){
                        $tabla.='
                           <li class="active"><a href="'.SERVERURL.$paginaURL.'/'.$i.'/">'.$i.'</a></li>
                        ';
                    }
                    else{
                        $tabla.='
                            <li><a href="'.SERVERURL.$paginaURL.'/'.$i.'/">'.$i.'</a></li>
                        ';
                    }
                }

                if($pagina == $Npaginas)
                {
                    $tabla.='
                           <li class="disabled"><a href="javascript:void(0)"><i class="zmdi zmdi-chevron-right"></i></a></li>
                    ';
                }else{
                    $tabla.='
                            <li><a href="'.SERVERURL.$paginaURL.'/'.($pagina+ 1).'/"><i class="zmdi zmdi-chevron-right"></i></a></li>
                    ';
                }

                $tabla.='
                        </ul>
                    </nav>
                ';
            }
  
            return $tabla;
        }

        public function eliminar_cuenta_controlador()
        {
           $codigo = mainModel::desctyption($_POST['codigo-del']);
           $privilegio = mainModel::desctyption($_POST['prigilegio-admin']);

           $codigo = mainModel::limpiar_Cadena($codigo);
           $privilegio = mainModel::limpiar_Cadena($privilegio);
           $fechaActual = date("Y-m-d H:m:s");
           session_start(['name'=>'SHP']);

            if($privilegio == 1)
            {
                $query = mainModel::ejecutar_consulta_simple("SELECT codUsuario FROM usuario WHERE codUsuario = '$codigo' ");
                $datos = $query->fetch();

                if($datos['codUsuario'] != 7)
                {
                    $delUsu = administradorModelo::eliminar_cuenta_modelo($codigo);

                    if($delUsu->rowCount() >= 1)
                    {
                        $datosAuditoria=[
                            "modulo" => 'Administrador Usuario',
                            "codUsu" => $_SESSION['codusuario_shp'],
                            "fecha" => $fechaActual,
                            "accion" => 'Eliminar Usuario '.$codigo,
                            "codOpe" => ""
                        ];

                        $regAuditoria = mainModel::agregar_auditoria_modelo($datosAuditoria);

                        if($regAuditoria->rowCount() >= 1 )
                        {
                            $alerta= [
                                "Alerta"=>"recargar",
                                "Titulo"=>"Cuenta Eliminada",
                                "Texto"=>"La cuenta se elimino correctamente del sistema.",
                                "Tipo"=> "success"
                            ]; 
                        }

                    }else{
                        $alerta= [
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"Error al eliminar al usuario seleccionado.",
                            "Tipo"=> "error"
                        ]; 
                    }
                }else{
                    $alerta= [
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"No podemos eliminar el administrador principal del sistema.",
                        "Tipo"=> "error"
                    ]; 
                }

            }else{
                $alerta= [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No cuenta con los permisos para esta operación",
                    "Tipo"=> "error"
                ]; 
            }

            return mainModel::sweet_alert($alerta);
        }


        public function datos_admininstrador_controlador($tipo, $codigo)
        {
            $codigo = mainModel::desctyption($codigo);
            $tipo = mainModel::limpiar_Cadena($tipo);

            return administradorModelo::datos_admininstrador_modelo($tipo, $codigo);
        }

        public function actualizar_cuenta_controlar()
        {
            $dni = mainModel::limpiar_Cadena($_POST['dni-up']);
            $nombre = mainModel::limpiar_Cadena($_POST['nombre-up']);
            $apellido = mainModel::limpiar_Cadena($_POST['apellido-up']);
            $telefono = mainModel::limpiar_Cadena($_POST['telefono-up']);
            $direccion = mainModel::limpiar_Cadena($_POST['direccion-reg']);
        }
    }

    
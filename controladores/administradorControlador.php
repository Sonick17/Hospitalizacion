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
            $privilegio = mainModel::limpiar_Cadena($_POST['optionsPrivilegio']);

            $foto = ($genero == 'Masculino') ? "Male3Avatar.png" : "Famele3Avatar.png";

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

            return mainModel::sweet_alert($alerta);

        }

        //CONTROLAR PARA PAGINAR ADMINISTRADORES
        public function paginador_administrador_controlador($pagina,$registro,$privilegios,$codigo)
        {
            $pagina = mainModel::limpiar_Cadena($pagina);
            $registro = mainModel::limpiar_Cadena($registro);
            $privilegios = mainModel::limpiar_Cadena($privilegios);
            $codigo = mainModel::limpiar_Cadena($codigo);
            $estado = 0;
            $tabla="";

            $pagina = (isset($pagina) && $pagina > 0 ) ? (int)$pagina : 1 ;
            $inicio = ($pagina > 0) ? (($pagina*$registro) - $registro ) : 0 ;

            $conexion = mainModel::conectar();
            $datos = $conexion->query(" SELECT SQL_CALC_FOUND_ROWS * FROM usuario WHERE codUsuario != '$codigo' and idRol != '$privilegios' and Estado = '$estado' ORDER BY nombreCompleto ASC LIMIT $inicio,$registro ");

            $datos = $datos->fetchAll();
            $total = $conexion->query("SELECT FOUND_ROWS()");
            $total = (int)$total->fetchColumn();

            $Npaginas = ceil($total/$registro);

            $tabla.='	<div class="table-responsive">
                            <table class="table table-hover text-center">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">DNI</th>
                                        <th class="text-center">NOMBRES</th>
                                        <th class="text-center">USUARIO</th>
                                        <th class="text-center">TELÉFONO</th>
                                        <th class="text-center">A. CUENTA</th>
                                        <th class="text-center">A. DATOS</th>
                                        <th class="text-center">ELIMINAR</th>
                                    </tr>
                                </thead>
                                <tbody> 
                ';

            if($total>=1 && $pagina<=$Npaginas){
                $contador=$inicio+1;
                foreach ($datos as $rows) {
                    
                    $tabla.='
                        <tr>
                            <td>'.$contador.'</td>
                            <td>'.$rows['numIdentidad'].'</td>
                            <td>'.$rows['nombreCompleto'].'</td>
                            <td>'.$rows['cuentaUsuario'].'</td>
                            <td>'.$rows['telefono'].'</td>
                            <td>
                                <a href="#!" class="btn btn-success btn-raised btn-xs">
                                    <i class="zmdi zmdi-refresh"></i>
                                </a>
                            </td>
                            <td>
                                <a href="#!" class="btn btn-success btn-raised btn-xs">
                                    <i class="zmdi zmdi-refresh"></i>
                                </a>
                            </td>
                            <td>
                                <form>
                                    <button type="submit" class="btn btn-danger btn-raised btn-xs">
                                        <i class="zmdi zmdi-delete"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    '; 
                    $contador++;
                }
            }else{
                $tabla.='
                        <tr>
                            <td colspan ="5">No hay registros en el sistema.</td>
                        </tr>
                '; 
            }

            $tabla.='		    </tbody>
                            </table>
                        </div>';



            return $tabla;
        }


    }

    
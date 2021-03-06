<?php 

    if($peticionAjax)
        require_once "../modelos/loginModelo.php";
    else 
        require_once "./modelos/loginModelo.php";

    class loginControlador extends loginModelo {
        
        public function iniciar_sesion_controlador()
        {
            $usu = mainModel::limpiar_Cadena($_POST['usuario']);
            $pass = mainModel::limpiar_Cadena($_POST['clave']);

            $clave = mainModel::encryption($pass);

            $datosLogin = [
                "usuario" => $usu,
                "pass" => $clave
            ];

            $DatosCuenta = loginModelo::iniciar_sesion_modelo($datosLogin); 

            if($DatosCuenta->rowCount() == 1){
                $row = $DatosCuenta->fetch();
                $codusu = mainModel::ejecutar_consulta_simple("SELECT codUsuario FROM usuario WHERE cuentaUsuario = $usu");

                $fechaActual = date("Y-m-d H:m:s");
                $modulo = "Login";
                $accion = "Inicio de Sesión";

                $datosAuditoria=[
                    "modulo" => $modulo,
                    "codUsu" => $row['codUsuario'],
                    "fecha" => $fechaActual,
                    "accion" => $accion,
                    "codOpe" => ""
                ];
                
                $insertarAuditoria = mainModel::agregar_auditoria_modelo($datosAuditoria);

                if($insertarAuditoria->rowCount() >= 1){
                    
                    session_start(['name'=>'SHP']);
                    $_SESSION['usuario_shp'] = $row['cuentaUsuario'];
                    $_SESSION['nombres_shp'] = $row['nombreCompleto'];
                    $_SESSION['codusuario_shp'] = $row['codUsuario'];
                    $_SESSION['tipo_shp'] = $row['idRol'];
                    $_SESSION['foto_shp'] = $row['foto'];
                    $_SESSION['token_shp'] = md5(uniqid(mt_rand(),true));

                    if($row['idRol']== "1")
                        $url = SERVERURL."home/";
                    else    
                        $url = SERVERURL."catalog/";

                    return $urlLocation = '<script> window.location = "'.$url.'" </script>';
                        
                }else{
                    $alerta= [
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"No hemos podido iniciar la sesión por problemas técnicos, por favor intente nuevamente.",
                        "Tipo"=> "error"
                    ];
    
                    return mainModel::sweet_alert($alerta);
                }

            }else{
                $alerta= [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El nombre de usuario y contraseña no son correctos o su cuenta puede estar deshabilitada",
                    "Tipo"=> "error"
                ];

                return mainModel::sweet_alert($alerta);
            }
            
            
        }

        public function cerrar_session_controlador(){
            
            session_start(['name'=>'SHP']);
            
            $token = mainModel::desctyption($_GET['Token']);
            
            $modulo = "Login";
            $usuario = $_SESSION['codusuario_shp'];
            $fechaActual = date("Y-m-d H:m:s");
            $accion = "Cerrar Sesión";

            $Auditoria=[
                "modulo" => $modulo,
                "codUsu" => $usuario,
                "fecha" => $fechaActual,
                "accion" => $accion,
                "codOpe" => ""
            ];

            $datos = [
                "Usuario" => $usuario,
                "Token_S" => $_SESSION['token_shp'],
                "Token" => $token
            ];

            $AgregarAuditoria = loginModelo::agregar_auditoria_modelo($Auditoria);

            if($AgregarAuditoria->rowCount() < 1)
            {
                $alerta= [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El nombre de usuario y contraseña no son correctos o su cuenta puede estar deshabilitada",
                    "Tipo"=> "error"
                ];

                return mainModel::sweet_alert($alerta);
            }

            return loginModelo::cerrar_sesion_modelo($datos);

        }

        public function forzar_cierre_session_controlador(){
            session_destroy();
            return header("Location:".SERVERURL."login/");
        }

    }
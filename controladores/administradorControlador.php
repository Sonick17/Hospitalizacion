<?php 

    if($peticionAjax)
        require_once "../modelos/administradorModelo.php";
    else 
        require_once "./modelos/administradorModelo.php";

    class administradorControlador extends administradorModelo {

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

    }
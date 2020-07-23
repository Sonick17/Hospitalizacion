<?php 
    if($peticionAjax)
       require_once "../core/mainModel.php";
    else 
       require_once "./core/mainModel.php";


    class loginModelo extends mainModel {

        protected function iniciar_sesion_modelo($datos)
        {
            $sql = mainModel::conectar()->prepare("SELECT * FROM usuario where cuentaUsuario = :usuario and cuentaClave = :pass and estado = 0");
            $sql->bindParam(":usuario",$datos['usuario']);
            $sql->bindParam(":pass",$datos['pass']);            
            $sql->execute();

            return $sql;
        }

        protected function cerrar_sesion_modelo($datos)
        {
            if($datos['Usuario'] != "" && $datos['Token_S'] == $datos['Token']  ){
                session_unset();
                session_destroy();
                $respuesta = true;
            }else{
                $respuesta = false;
            }
            return $respuesta;
        }
    }
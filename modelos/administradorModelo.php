<?php 
    if($peticionAjax)
       require_once "../core/mainModel.php";
    else 
       require_once "./core/mainModel.php";


    class administradorModelo extends mainModel{
        
        protected function agregar_cuenta_modelo($datos) {

            $sql = mainModel::conectar()->prepare("INSERT INTO usuario (numIdentidad,nombreCompleto,cuentaUsuario,cuentaClave,genero,idrol,email,telefono,direccion,foto,estado) VALUES(:numiden,:nomCompleto,:usuario,:clave,:genero,:rol,:email,:telefono,:direccion,:foto,:estado)");
            $sql->bindParam(":numiden"      ,$datos['numiden']);
            $sql->bindParam(":nomCompleto"  ,$datos['nomCompleto']);
            $sql->bindParam(":usuario"      ,$datos['usuario']);
            $sql->bindParam(":clave"        ,$datos['clave']);
            $sql->bindParam(":genero"       ,$datos['genero']);
            $sql->bindParam(":rol"          ,$datos['rol']);
            $sql->bindParam(":email"        ,$datos['email']);
            $sql->bindParam(":telefono"     ,$datos['telefono']);
            $sql->bindParam(":direccion"    ,$datos['direccion']);
            $sql->bindParam(":foto"         ,$datos['foto']);
            $sql->bindParam(":estado"       ,$datos['estado']);
            $sql->execute();

            return $sql;
        }

        

    }

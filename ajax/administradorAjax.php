<?php 
    $peticionAjax=true;
    require_once "../core/configGeneral.php";

    if(isset($_POST['dni-reg']) || isset($_POST['codigo-del']) ){
        require_once "../controladores/administradorControlador.php";
        $insAdmin = new administradorControlador;

        if(isset($_POST['dni-reg']) &&  isset($_POST['nombre-reg']) &&  isset($_POST['apellido-reg']) &&  
           isset($_POST['usuario-reg']) ){
            echo $insAdmin->agregar_usuario_controlador();
        }

        if( isset($_POST['codigo-del']) && isset($_POST['prigilegio-admin'])  ){
            echo $insAdmin->eliminar_cuenta_controlador();
        }

        if( isset($_POST['dni-up']) && isset($_POST['nombre-up'])  ){
            echo $insAdmin->eliminar_cuenta_controlador();
        }

    }else{
        session_start();
        session_destroy();
        echo '<script> window.location.href="'.SERVERURL.'login/"; </script>';
    }
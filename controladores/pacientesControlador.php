<?php 

    if($peticionAjax)
        require_once "../modelos/administradorModelo.php";
    else 
        require_once "./modelos/administradorModelo.php";

    class pacienteControlador extends administradorModelo {
        
        public function listarPaciente_controlador()
        {
            
            $query = "SELECT a.codArea , a.nomArea FROM area a ";
            $consulta1 = mainModel::ejecutar_consulta_simple($query);
            
            if($consulta1->rowCount() >= 1){
                
                $listArea ='
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group label-floating">
                            <label class="control-label">Seleccione Área *</label>
                            <select  class="form-control" type="text" name="cbx_area" id="cbx_area" required="" maxlength="30">';

                $row = $consulta1->fetchAll();

                foreach($row as $r  )
                {
                    $listArea .= '<option value="'.$r['codArea'].'"> '.$r['nomArea'].'</option>';
                }
                
                $listArea .='  
                          </select>
                        </div>
                    </div>
                
                ';
            }
            else{
                $alerta= [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"La consulta no se llevo a cabo",
                    "Tipo"=> "error"
                ];

                return mainModel::sweet_alert($alerta);
            }

            return $listArea;
            

        }
    
    }
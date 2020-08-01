

<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles"><i class="zmdi zmdi-male-alt"></i> HOSPITALIZACIÓN <small></small></h1>
	</div>
</div>


</div>

<!-- Panel nuevo administrador -->
<div class="container-fluid">
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title"><i class="zmdi zmdi-plus"></i> &nbsp; NUEVA HOSPITALIZACIÓN</h3>
		</div>
		<div class="panel-body">
			<form data-form="save" action="<?php echo SERVERURL; ?>ajax/hospitalizacionajax.php" method="POST" class="FormularioAjax" 
			autocomplete= "off" enctype= "multipart/form-data">
		    	<fieldset>
		    		<legend><i class="zmdi zmdi-account-box"></i> &nbsp; Información de Paciente</legend>
		    		<div class="container-fluid">
		    			<div class="row">
		    				<div class="col-xs-12">
						    	<div class="form-group label-floating">
								  	<label class="control-label">CÓDIGO DE PACIENTE *</label>
								  	<input pattern="[0-9-]{1,30}" class="form-control" type="text" name="dni-reg" required="" maxlength="9">
								</div>
		    				</div>

							<?php
								require_once "./controladores/pacientesControlador.php";								  
								$listArea = new pacienteControlador();
								echo $listArea->listarPaciente_controlador();

							?>

		    				<div class="col-xs-12 col-sm-6">
								<div class="form-group label-floating">
								  	<label class="control-label">Seleccione Cama *</label>
								  	<select   class="form-control" type="text" name="cbx_cama" id="cbx_cama" required="" maxlength="30">
									  <option value="0">Cama</option>
									</select>								
								</div>
		    				</div>



			    <p class="text-center" style="margin-top: 20px;">
			    	<button type="submit" class="btn btn-info btn-raised btn-sm"><i class="zmdi zmdi-floppy"></i> Guardar</button>
			    </p>

				<div class="RespuestaAjax"></div>
		    </form>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles"><i class="zmdi zmdi-account zmdi-hc-fw"></i> Usuarios <small></small></h1>
	</div>
	<!-- <p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse voluptas reiciendis tempora voluptatum eius porro ipsa quae voluptates officiis sapiente sunt dolorem, velit quos a qui nobis sed, dignissimos possimus!</p> -->
</div>

<div class="container-fluid">
	<ul class="breadcrumb breadcrumb-tabs">
	  	<li>
	  		<a href="<?php echo SERVERURL; ?>admin/" class="btn btn-info">
	  			<i class="zmdi zmdi-plus"></i> &nbsp; NUEVO USUARIO
	  		</a>
	  	</li>
	  	<li>
	  		<a href="<?php echo SERVERURL; ?>adminlist/" class="btn btn-success">
	  			<i class="zmdi zmdi-format-list-bulleted"></i> &nbsp; LISTA DE USUARIO
	  		</a>
	  	</li>
	  	<li>
	  		<a href="<?php echo SERVERURL; ?>adminsearch/" class="btn btn-primary">
	  			<i class="zmdi zmdi-search"></i> &nbsp; BUSCAR USUARIO
	  		</a>
	  	</li>
	</ul>
</div>

<?php 
	require_once "./controladores/administradorControlador.php";
	$insAdmin = new administradorControlador();

	if(isset($_POST['busquedad_ini_admin']))
	{
		$_SESSION['busquedad_admin'] = $_POST['busquedad_ini_admin'];
	}

	if(isset($_POST['eliminar_busquedad_admin']))
	{
		unset($_SESSION['busquedad_admin']);
	}

	if(!isset($_SESSION['busquedad_admin']) && empty($_SESSION['busquedad_admin']) ):

?>

<div class="container-fluid">
	<form class="well" method="POST" action="">
		<div class="row">
			<div class="col-xs-12 col-md-8 col-md-offset-2">
				<div class="form-group label-floating">
					<span class="control-label">¿A quién estas buscando?</span>
					<input class="form-control" type="text" name="busquedad_ini_admin" required="">
				</div>
			</div>
			<div class="col-xs-12">
				<p class="text-center">
					<button type="submit" class="btn btn-primary btn-raised btn-sm"><i class="zmdi zmdi-search"></i> &nbsp; Buscar</button>
				</p>
			</div>
		</div>
	</form>
</div>

<?php else: ?>

<div class="container-fluid">
	<form class="well" method="POST" action="">
		<p class="lead text-center">Su última búsqueda  fue <strong><?php echo $_SESSION['busquedad_admin'];?></strong></p>
		<div class="row">
			<input class="form-control" type="hidden" name="eliminar_busquedad_admin" value="1">
			<div class="col-xs-12">
				<p class="text-center">
					<button type="submit" class="btn btn-danger btn-raised btn-sm"><i class="zmdi zmdi-delete"></i> &nbsp; Eliminar búsqueda</button>
				</p>
			</div>
		</div>
	</form>
</div>

<!-- Panel listado de busqueda de administradores -->
<div class="container-fluid">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title"><i class="zmdi zmdi-search"></i> &nbsp; BUSCAR USUARIOS</h3>
		</div>
		<div class="panel-body">
			
			<?php 
				$pagina = explode("/",$_GET['views']);
				echo $insAdmin->paginador_administrador_controlador($pagina[1],1, $_SESSION['tipo_shp'],$_SESSION['codusuario_shp'],$_SESSION['busquedad_admin'] );
			?>	

		</div>
	</div>
</div>

<?php endif; ?>
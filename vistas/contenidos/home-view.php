<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles">INFORMACIÓN <small></small></h1>
	</div>
</div>
<div class="full-box text-center" style="padding: 30px 10px;">

    <?php
        require_once "./controladores/administradorControlador.php";
        $idAdmin = new administradorControlador();
        $cAdmin = $idAdmin->datos_admininstrador_controlador("Conteo", 0);
    ?>

	<article class="full-box tile">
		<div class="full-box tile-title text-center text-titles text-uppercase">
			Disponibles
		</div>
		<div class="full-box tile-icon text-center">
			<i class="zmdi zmdi-male-alt"></i>
		</div>
		<div class="full-box tile-number text-titles">
			<p class="full-box"><?php echo $cAdmin->rowCount()  ?></p>
			<small>Ver reporte</small>
		</div>
	</article>
	<article class="full-box tile">
		<div class="full-box tile-title text-center text-titles text-uppercase">
			En Acondicionamiento
		</div>
		<div class="full-box tile-icon text-center">
			<i class="zmdi zmdi-info"></i>
		</div>
		<div class="full-box tile-number text-titles">
			<p class="full-box">10</p>
			<small>Ver reporte</small>
		</div>
	</article>
	<article class="full-box tile">
		<div class="full-box tile-title text-center text-titles text-uppercase">
			Ocupadas
		</div>
		<div class="full-box tile-icon text-center">
			<i class="zmdi zmdi-hospital"></i>
		</div>
		<div class="full-box tile-number text-titles">
			<p class="full-box">70</p>
			<small>Register</small>
		</div>
	</article>
</div>

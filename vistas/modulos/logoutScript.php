
<script>

    $(document).ready(function(){
        $('.btn-exit-system').on('click', function (e) {
                e.preventDefault();
                var Token = $(this).attr('href');
                swal({
                    title:'¿Estás Seguro?',
                    text: 'La session actual se cerrará y deberas iniciar sesión nuevamente.',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#03A9F4',
                    cancelButtonColor: '#F44336',
                    confirmButtonText: '<i class="zmdi zmdi-run"></i> Si, Cerrar!',
                    cancelButtonText: '<i class="zmdi zmdi-close-circle"></i> No, Cancelar!',
                }).then(function () {
                   $.ajax({
                        url:'<?php ECHO SERVERURL; ?>ajax/loginAjax.php?Token='+Token,
                        success: function(data){
                            if(data == true){
                                window.location.href = "<?php ECHO SERVERURL; ?>login/"
                            }else{
                                swal(
                                    "Ocurrió un error",
                                    "No se pudo cerrar la sesión",
                                    "error"
                                );
                            }
                            
                        }
                   });
                });
        });
    });

</script>
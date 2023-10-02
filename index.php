<?php
require "./core/init.php";

use Core\Classes\Menu;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <title>Cardinal</title>
        <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="./assets/css/cardinal.css">
        <link rel="icon" href="favicon.ico">
    </head>

    <body>
        <nav class="navbar navbar-dark navbar-expand-md bg-dark">
            <div class="container-fluid"><a class="navbar-brand" href="#">Cardinal System</a><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse d-lg-flex justify-content-lg-end justify-content-xl-start" id="navcol-1">
                    <ul class="navbar-nav">
                        <?php $menu = new Menu($config); $menu->make_menu();?>
                        <?php if(file_exists("./core/versions")):?>
                            <li class="nav-item">
                                <?php include "./core/versions/php_versions.php";?>
                            </li>
                        <?php else:?>
                            <li class="nav-item version"><small class="version">PHP v<?php echo phpversion(); ?></small></li>
                        <?php endif;?>
                    </ul>
                </div>
                <div class="d-flex d-xl-flex align-items-center align-items-xl-center">
                    <div class="input-group input-group-sm mr-2">
                        <input class="form-control" type="text" id="search">
                        <div class="input-group-append"><button class="btn btn-success" type="button" data-role="search">Go!</button></div>
                    </div><span class="d-lg-flex d-xl-flex align-items-lg-center align-items-xl-center navbar-text left d-none d-lg-inline-block">SQL&nbsp;
                        <?php $menu->sql_status();?>
                    </span><span class="navbar-text left col-1 m-auto" data-toggle="modal" data-target="#modal-1"><span class="icon-config"></span></span>
                </div>
            </div>
        </nav>

        <div class="container-fluid mt-5">
            <div class="row" id="projects_content">
                <div class="col-lg-2 col-md-6 col-sm-12 mb-3" id="newproject">
                    <div class="card">
                        <div class="tag">
                            <div class="tag_name">NUEVO</div>
                        </div>

                        <div class="card-img-top" style="background: url('assets/img/newproject.png'); height: 18.8rem;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL CONF-->

        <div class="modal fade" role="dialog" tabindex="-1" id="modal-1">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Configuracion</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group"><label>Ruta de backup:</label><input class="form-control" type="text" placeholder="X:\path" value="<?php echo $config->save_path; ?>" id="save_rute"></div>
                                    <div class="form-group"><label>Ruta de proyectos:</label><input class="form-control" type="text" placeholder="C:\xampp\htdocs" value="<?php echo $config->xampp_htdocs; ?>" id="xampp_htdocs"></div>
                                    <div class="form-group"><label>Ruta del editor (IDE):</label><input class="form-control" type="text" placeholder="X:\path\editor.exe" value="<?php echo $config->editor_path; ?>" id="editor_path"></div>
                                </div>
                                <div class="col">
                                    <div class="form-group"><label>Ruta XAMPP Control Panel:</label><input class="form-control" type="text" placeholder="C:\xampp\xampp-control.exe" value="<?php echo $config->xampp_control_exe; ?>" id="xampp_cp"></div>
                                    <div class="form-group"><label>Navicat:</label><input class="form-control" type="text" placeholder="X:\path\navicat.exe" value="<?php echo $config->navicat_exe; ?>" id="navicat"></div>
                                    <div class="form-group"><label>Zeal:</label><input class="form-control" type="text" placeholder="X:\path\zeal.exe" value="<?php echo $config->zeal_exe; ?>" id="zeal"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer"><button class="btn btn-light" type="button" data-dismiss="modal">Cancelar</button><button class="btn btn-success" type="button" data-success="send">Guardar</button></div>
                </div>
            </div>
        </div>

        <!-- MODAL NEW PROJECT -->

        <div class="modal fade" id="modal-2" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modal2" aria-hidden="true">
		    <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal2">Nuevo Proyecto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Nombre del proyecto:</label>
                            <input type="text" class="form-control" id="proyect_name">
                        </div>
                        <?php 
                            $dirs = scandir("./data/skeleton"); 

                            if(count($dirs)>2):
                        ?>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="skeleton">
                                <label class="custom-control-label" for="skeleton">Agregar estructura predefinida?</label>
                            </div>
                            <div id="folders" style="display:none"></div>
                        <?php else: ?>
                            <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                    <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                    </symbol>
                                    <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                                    </symbol>
                                    <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                    </symbol>
                                    </svg>
                            <div class="alert alert-warning d-flex align-items-center" role="alert">
                                <svg class="bi flex-shrink-0 mr-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                <div>
                                    No tiene ninguna estructura definida para nuevos proyectos. Para agregar una, agregue carpetas o copielas dentro de: /data/skeleton/
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="create">Crear</button>
                    </div>
                </div>
		    </div>
		</div>

        <!-- MODAL DEL -->

        <div class="modal fade" id="modal-3" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modal2" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="alert-erro-cont my-4"><div class="alert-erro"><span>x</span></div></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal" id="cancel">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="delete">Eliminar</button>
                    </div>
                </div>
            </div>
		</div>

        <!-- MODAL RENAME -->

        <div class="modal fade" id="modal-4" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modal2" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Escriba el nuevo nombre para el proyecto: <b></b></label>
                            <input type="text" class="form-control" id="new_name">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal" id="cancel">Cancelar</button>
                        <button type="button" class="btn btn-success" id="change">Cambiar</button>
                    </div>
                </div>
            </div>
		</div>

        <div id="up" style="display:none"><div class="upimage"></div></div>

        <!-- TOAST -->

        <div aria-live="polite" aria-atomic="true" style="position: fixed; min-height: 200px;bottom: 0px;right: 15px;z-index: 1000;" id="toastcontent">
            <!-- Position it -->
            <div style="position: absolute; top: 0; right: 0;" id="toasts">
            </div>
		</div>

        <script>
    	    const server 	= '<?php echo ($_SERVER['SERVER_ADDR']=="::1"?"localhost":$_SERVER['SERVER_ADDR']); ?>';
    	    const savep 	= <?php echo (!empty($config->save_path))?1:0; ?>;
    	    const sublime = <?php echo (!empty($config->editor_path))?1:0; ?>;
			const editor	= (sublime)?'<?php $editor = $menu->get_editor($config->editor_path); echo $editor["image"]?>':'';
        </script>
        <script src="./assets/js/jquery.min.js"></script>
        <script src="./assets/js/bootstrap.min.js"></script>
		<script src="./assets/js/cardinal.js"></script>
		<script>
			$('#settings').click(function(){
				$('.modal').modal('show')
			});
			app = App();

			$('[data-success="send"]').click(function(){
				datas = {
					rute: $('#save_rute').val(),
					xampp: $('#xampp_htdocs').val(),
					editor: $('#editor_path').val(),
					xcp: $('#xampp_cp').val(),
					navicat: $('#navicat').val(),
					zeal: $('#zeal').val(),
				};

				$.ajax({
				  	url: "./api.php",
				  	data:{p:"update", data:datas},
				  	type:'POST',
					dataType:'json',
				}).done(function(data){
					if(data.status=='success'){
						$('#modal-1').modal('toggle');
					}
				});
			});

			$('[data-role="application"]').click(function(){
				app = $(this).data('appname');
				$.ajax({
				  url: "./api.php",
				  data:{p:app},
				  type:'POST',

				});
			})

			$(window).scroll(function(){
				if($(window).scrollTop() > 200){
					$('#up').fadeIn();
				}else{
					$('#up').fadeOut();
				}
			});

			$('#up').click(function(){
				$('html, body').stop().animate({
					scrollTop: 0
				}, 1000);
			});
			<?php  if((empty($config->editor_pat) && empty($config->save_path) && empty($config->xampp_htdocs) && empty($config->xampp_control_exe) && empty($config->navicat_exe) && empty($config->zeal_exe)) || 
				empty($config->xampp_htdocs)):?>
				$("#modal-1").modal('toggle');
			<?php endif; ?>
		</script>
<?php 
require_once './core/init.php';

use Core\Classes\Skeleton;
use Core\Classes\Tools;

if($_POST){

	$action = $_POST['p'];

	switch ($action) {
		case 'open':
			$program = $_POST['a'];

			$project = isset($_POST['r'])?$_POST['r']:'';

			$app = '';
			switch ($program) {
				case 'editor':
					$app = $config->editor_path;
				break;
			}
			$total_app = ($project!='')?'"'.$app.'" '.$config->xampp_htdocs.'\\'.$project:$app;
			Tools::open_folder($total_app); #Ya tiene puesto para ejecutar un comando junto con un parametro de ruta, solo hace falta agregarlo al js
		break;
		case 'scan':
			echo json_encode(Tools::load_localhost());
		break;
		case 'verify_backup':
			$path = $_POST['r'];

			echo json_encode(Tools::is_backup($path));
		break;
		case "xampp":
			Tools::run_p($config->xampp_control_exe);
		break;
		case "navicat":
			Tools::run_p($config->navicat_exe);
		break;
		case "zeal":
			Tools::run_p($config->zeal_exe);
		break;
		case "editor":
			Tools::run_p($config->editor_path);
		break;
		case "salvas":
			if (substr(php_uname(), 0, 7) == "Windows"){
		        Tools::open_folder('explorer.exe "'.$config->save_path.'"');
		    }
		    else {
		        Tools::open_folder('nautilus '.$config->save_path.''); 
		    }			
		break;
		case "htdocs":
			if (substr(php_uname(), 0, 7) == "Windows"){
		        Tools::open_folder('explorer.exe "'.$config->xampp_htdocs.'"');
		    }
		    else {
		        Tools::open_folder('nautilus '.$config->xampp_htdocs.''); 
		    }
		break;
		case "save":
			ini_set('max_execution_time', '420');
			$project = $_POST['r'];

			$files_to_zip = Tools::get_files_from_folder('../'.$project.'/');
			Tools::create_zip($files_to_zip, $config->save_path.'/'.$project.'.zip', $project);
		break;
		case "openproject":
			$path = isset($_POST['r'])?$_POST['r']:'';
			Tools::open_folder('explorer.exe '.$config->xampp_htdocs.'\\'.$path.'');
		break;
		#Verifica si ya estan realizadas las copias de seguridad
		case "update":
			$data = $_POST['data'];

			$save 			= $data['rute'];
			$xampp_path 	= $data['xampp'];
			$editor 		= $data['editor'];
			$xampp_control 	= $data['xcp'];
			$navicat 		= $data['navicat'];
			$zeal 			= $data['zeal'];

			$sentencia = $baseDeDatos->prepare("UPDATE configs SET 
				save_path = :save, 
				editor_path = :editor,  
				xampp_htdocs = :xampp_h, 
				xampp_control_exe = :control, 
				navicat_exe = :navicat, 
				zeal_exe = :zeal"
			);

			$sentencia->bindParam(":save", $save);
			$sentencia->bindParam(":editor", $editor);
			$sentencia->bindParam(":xampp_h", $xampp_path);
			$sentencia->bindParam(":control", $xampp_control);
			$sentencia->bindParam(":navicat", $navicat);
			$sentencia->bindParam(":zeal", $zeal);
			$qu = $sentencia->execute();
				
			if($qu){
				$return = json_encode(array('status' => 'success'));
			}else{
				$return = json_encode(array('status' => 'error'));
			}

			echo $return;
		break;
		case 'delete':
			$path = isset($_POST['r'])?$_POST['r']:'';
			if($path!=basename(dirname(__FILE__))){
				$delete = Tools::delete_folder($config->xampp_htdocs.'/'.$path);

				if($delete){
					if(file_exists('./data/sha/'.$path.'.php')){
						unlink('./data/sha/'.$path.'.php');
					}
					echo json_encode(array('status' => 'OK'));
					die();
				}
			}
		break;
		case "rename":
			$return = array(
				'status'	=> 'error',
				'msg'		=> 'Ocurrio un error al modificar el nombre del proyecto.'
			);
			$path 		= $_POST['r'];
			$newname 	= $_POST['newname'];

			$app = basename(dirname(__FILE__));

			if($_POST['newname'] != $app && $path != $app){
				if(file_exists($config->xampp_htdocs.'\\'.$path) && is_dir($config->xampp_htdocs.'\\'.$path)){
					if(!file_exists($config->xampp_htdocs.'\\'.$newname) && !is_dir($config->xampp_htdocs.'\\'.$newname)){
						if(rename($config->xampp_htdocs.'\\'.$path, $config->xampp_htdocs.'\\'.$newname)){
							if(file_exists('./data/sha/'.$path.'.php')){
								rename('./data/sha/'.$path.'.php', './data/sha/'.$newname.'.php');
							}
							$return = array(
								'status'	=> 'success',
								'msg'		=> 'El nombre del proyecto fue modificado con exito.'
							);
						}
					}
				}
			}

			echo json_encode($return);
		break;
		case "newproject":
			$new_project 		= $_POST['project'];
			$create_skeleton 	= $_POST['skeleton'];
			$skeleton_type 		= $_POST['skeleton_type'];

			if(!file_exists($config->xampp_htdocs.'/'.$_POST['project'])){
				if($create_skeleton == 'true'){
					$skeleton = Skeleton::get_skeletons();

					$skeleton_name = $skeleton[$skeleton_type]['name'];

					Tools::rcopy('./data/skeleton/'.$skeleton_name, $config->xampp_htdocs.'/'.$_POST['project']);
					#Poner aqui para la creacion del mysql
				}else{
					mkdir($config->xampp_htdocs.'/'.$_POST['project']);
				}
				$return = array(
					'status'	=> 'success',
					'msg'		=> 'Proyecto '.$_POST['project'].' creado',
				);
			}else{
				$return = array(
					'status'	=> 'error',
					'msg'		=> 'Ya existe un proyecto con este nombre: '.$_POST['project'],
				);
			}
			echo json_encode($return);
		break;
		case "set_type":
			$return = array(
				'status'	=> 'error',
				'msg'		=> ''
			);


			if(file_exists($config->xampp_htdocs.'/'.$_POST['project'].'/mine')){
				unlink($config->xampp_htdocs.'/'.$_POST['project'].'/mine');
			}
			if(file_exists($config->xampp_htdocs.'/'.$_POST['project'].'/coop')){
				unlink($config->xampp_htdocs.'/'.$_POST['project'].'/coop');
			}
			if($_POST['type'] != 'none'){
				if(fopen($config->xampp_htdocs.'/'.$_POST['project'].'/'.$_POST['type'], 'w+')){
					$return = array(
						'status'	=> 'success',
						'msg'		=> '',
						'tag'		=> Tools::get_properties($config->xampp_htdocs.'/'.$_POST['project']),
					);
				}
			}else{
				$return = array(
					'status'	=> 'success',
					'msg'		=> '',
					'tag'		=> Tools::get_properties($config->xampp_htdocs.'/'.$_POST['project']),
				);
			}


				

			echo json_encode($return);
		break;
		case "get_skeleton":
			$skeletons = Skeleton::get_skeletons();
			echo json_encode($skeletons);
		break;
	}

}
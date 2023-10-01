<?php

namespace Core\Classes;

class Menu{

    private $config;
    public function __construct($config){
        $this->config = $config;
    }

    public function make_menu(){
        $menus = $this->find_menu();

        foreach ($menus as $menu) {
            $print = '<li class="nav-item"> <a class="nav-link" ';
            foreach ($menu['attr'] as $key => $value) {
                $print .= "$key='$value' ";
            }
            $print .= '"><img src="./assets/img/'.$menu['img'].'" width="25px" style="border-style: none;border-radius: 0px;"><span class="text-white-50 d-inline d-md-none" style="margin-left: 5px;">'.$menu['name'].'</span></a></li>';

            echo $print;
        }
    }

    public function find_menu(){
        $menu = [];
        
        if(!empty($this->config->xampp_control_exe)){
            $menu[] = [
                'attr'  => [
                    'data-role'     => "application",
                    'data-appname'  => "xampp",
                    'href'          => '#'
                ],
                'img'   => 'applications/xampp.png',
                'name'  => 'XAMPP Control Panel'
            ];
        }

        if(!empty($this->config->navicat_exe)){
            $menu[] = [
                'attr'  => [
                    'data-role'     => "application",
                    'data-appname'  => "navicat",
                    'href'          => '#'
                ],
                'img'   => 'applications/navicat.png',
                'name'  => 'Navicat'
            ];
        }

        if(!empty($this->config->zeal_exe)){
            $menu[] = [
                'attr'  => [
                    'data-role'     => "application",
                    'data-appname'  => "zeal",
                    'href'          => '#'
                ],
                'img'   => 'applications/zeal.png',
                'name'  => 'Zeal'
            ];
        }

        if(!empty($this->config->editor_path)){
            $editor = $this->get_editor($this->config->editor_path);
            $menu[] = [
                'attr'  => [
                    'data-role'     => "application",
                    'data-appname'  => "editor",
                    'href'          => '#'
                ],
                'img'   => 'applications/'.$editor['image'].'.png',
                'name'  => $editor['label']
            ];
        }

        if(!empty($this->config->save_path)){
            $menu[] = [
                'attr'  => [
                    'data-role'     => "application",
                    'data-appname'  => "salvas",
                    'href'          => '#'
                ],
                'img'   => 'applications/salvas.png',
                'name'  => 'Carpeta de salvas'
            ];
        }

        if(!empty($this->config->xampp_htdocs)){
            $menu[] = [
                'attr'  => [
                    'data-role'     => "application",
                    'data-appname'  => "htdocs",
                    'href'          => '#'
                ],
                'img'   => 'applications/htdocs.png',
                'name'  => 'Carpeta de Proyectos'
            ];
        }

        return $menu;
    }

    public function get_editor($exec = NULL){ #Devuelve array con valores del IDE. Hacer un explode y devolver el ultimo valor
        $temp = explode("\\", $exec);
        if(count($temp)<=1){
            $temp = explode("/", $exec);
        }
        $exec = end($temp);
        $exec = str_replace('.exe','',$exec);
        $exec = strtolower($exec);
        if($exec == 'sublime_text'){
            $return = array(
                'image' => 'sublime',
                'label' => 'Sublime Text',
            );
        }elseif($exec == 'code'){
            $return = array(
                'image' => 'vscode',
                'label' => 'Visual Studio Code',
            );
        }elseif($exec == 'notepad++'){
            $return = array(
                'image' => 'npp',
                'label' => 'Notepad++',
            );
        }elseif($exec == 'notepad'){
            $return = array(
                'image' => 'notepad',
                'label' => 'Notepad',
            );
        }else{
            $return = array(
                'image' => 'unknown',
                'label' => 'Desconocido',
            );
        }
    
        return $return;
    }

    public function sql_status(){
        $is_running = Tools::verify_app('mysqld');

        if($is_running){
            $status = 'success';
            $label  = 'OnLine';
        }else{
            $status = 'danger';
            $label  = 'OffLine';
        }

        echo '<span class="badge badge-'.$status.'">'.$label.'</span>';
    }
}
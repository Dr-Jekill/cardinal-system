<?php

namespace Core\Classes;

use Core\Classes\ArrayToPhP;

class Tools{

    public function __construct(){
        if(!extension_loaded('sqlite3')){
            self::init_extension('sqlite3');
        }
    }
    static function verify_app($name){ #Verifica si un proceso esta ejecutandose (principalmente creado para mysql)
        $full_name = (strpos(strtolower($name),'.exe') !== false)?$name:$name.'.exe';
    
        $proc = @popen('tasklist.exe /FI "IMAGENAME eq '.$full_name.'"', "r");
    
        $content = "";
    
        while (!feof($proc)) {
            $content .= fread($proc, 1024);
        }
    
        pclose($proc);
    
        return (strpos($content, 'no hay tareas') === false);
    }

    static function open_folder($cmd) {
        if (substr(php_uname(), 0, 7) == "Windows"){
            pclose(popen("start /B & $cmd", "r")); 
        }else {
            exec("$cmd > /dev/null 2>1 &");  
        }
    }

    static function load_localhost(){
        $app = basename(dirname(dirname(dirname(__FILE__))));
        $directorio = '../';
        $excluded_dirs = ['.', '..', 'dashboard', 'img', 'webalizer', 'xampp'];
        $dirs = array_diff(scandir($directorio), $excluded_dirs);
    
        // $dir_list = [];
    
        // foreach ($excluded_dirs as $excluded) {
        //     unset($dirs[array_search($excluded, $dirs, true)]);
        // }
    
        if(count($dirs) < 1) {
            return;
        }
    
        sort($dirs, SORT_NATURAL | SORT_FLAG_CASE);
    
        foreach($dirs as $dir){
            if(is_dir($directorio.'/'.$dir)) {
                $icon_directory = '/'.$dir .'/favicon.ico';
    
                if(file_exists($directorio.'/'.$dir .'/artisan')){
                    $icon_directory = '/'.$dir .'/public/favicon.ico';
                }elseif(file_exists($directorio.'/'.$dir .'/core/misc/favicon.ico')){
                    $icon_directory = '/'.$dir .'/core/misc/favicon.ico';
                }elseif(file_exists($directorio.'/'.$dir .'/img/prestashop@2x.png')){
                    $icon_directory = '/'.$dir .'/img/favicon.ico';
                }
    
                $dir_list[] = [
                    'path'		=> $directorio.$dir,
                    'have_icon'	=> file_exists($directorio.$icon_directory),
                    'icon'		=> $icon_directory,
                    'name'		=> $dir,
                    'url'       => (self::identify($directorio.$dir) == 'laravel')? "$dir/public/": $dir,
                    'tag'       => self::get_properties($directorio.'/'.$dir),
                    'save'		=> 'unknown',
                    'framework' => self::identify($directorio.$dir)
                ];
            }
        }
    
        return $dir_list;
    }

    static function identify($root_path){
        #Para identificar algunos tipos de frameworks por elementos en el root path
    
        $framework = '';
    
        if(file_exists("$root_path/artisan")){
            $framework = 'laravel';
        }elseif(file_exists("$root_path/wp-content")){
            $framework = 'wordpress';
        }elseif(file_exists("$root_path/core/misc/favicon.ico")){
            $framework = 'drupal';
        }elseif(file_exists("$root_path/img/prestashop@2x.png")){
            $framework = 'prestashop';
        }elseif(file_exists("$root_path/core/SysCore.php")){
            $framework = 'omnicore';
        }elseif(file_exists("$root_path/includes/pages/adm/ShowLoginPage.php")){
            $framework = 'Xmoons';
        }
    
        return $framework;
    }

    static function get_properties($rute){
        $property = array(
            'label' => '',
            'class' => '',
        );
    
        if(file_exists("$rute/mine")){
            $property = array(
                'label' => 'OWNER',
                'class' => '',
            );
        }elseif(file_exists("$rute/coop")){
            $property = array(
                'label' => 'CONTRIB',
                'class' => 'contribution',
            );
        }elseif(file_exists("$rute/core")){
            $property = array(
                'label' => 'CORE',
                'class' => 'core',
            );
        }
    
        return $property;
    }

    static function is_backup($path){
        $dir_list = [];
        
        if(is_dir($path)) {
            $temp = explode("/", $path);
            $end = end($temp);
            $path = str_replace('/'.$end, '', $path);
            $back = self::verify_sha($end, $path);

            if(!$back){
                $dir_list = [
                    'status' => 'ok'
                ];
            }
        }
        return $dir_list;
    }

    static function verify_sha($name, $dir){

        if(file_exists("./data/sha/$name.php")){
            include_once "./data/sha/$name.php";
    
            $files_to_verify = self::find_files($dir, $name);
    
            $to_compare = array();
    
            foreach ($files_to_verify as $key => $value) {
                if(key_exists($key, $sha)){
                    $to_compare[$key] = $key;
                }else{
                    return true;
                    #break;
                }
            }
    
            foreach($to_compare as $key => $value) {    
                if(key_exists($key, $sha)){
                    $actual_key = self::generate_sha($key);
                    if($sha[$key] != $actual_key){
                        return true;
                        #break;
                    }
                }else{
                    return true;
                    #break;
                }
            }
        }else{
            return true;
        }
            
        return false;
    }

    static function find_files($dir, $folder){
        global $files_to_verify;
    
        $rute = $dir.'/'.$folder;
    
        if(!file_exists($rute.'/.cignore')){
            $files = array_diff(scandir($rute), array('.','..','dashboard','xampp','webalizer','img', 'sha')); 
    
            foreach ($files as $file) { 
                #echo "$rute/$file<br>";
                if(is_dir("$rute/$file")){
                    self::find_files($rute, $file);
                }else{
                    $rute = str_replace('C:\xampp\htdocs/', '../', $rute);
                    
                    $files_to_verify["$rute/$file"] = $file;
                }
            }
    
        }
    
        return $files_to_verify;    
    }

    static function generate_sha($rute){
        return hash_file('sha1', $rute);
    }

    static function run_p($cmd) {
        if (substr(php_uname(), 0, 7) == "Windows"){
            pclose(popen("start /B & \"$cmd\"", "r")); 
            #echo "start /B & \"$cmd\"";
        }else {
            #exec($cmd ." > /dev/null &");  
            #$test = exec($cmd." > /dev/null 2>1 &");
            shell_exec("\"$cmd\" > /dev/null 2>1 "); 
        }
    }

    static function get_files_from_folder($directory) {
        global $files_to_zip;
    
        if(!file_exists($directory.'.cignore')){
            $files = array_diff(scandir($directory), array('.','..','dashboard','xampp','webalizer','img')); 
            $folders = array();
            foreach ($files as $file) { 
                if(is_file($directory.$file)) {
                    $files_to_zip[] = $directory.$file;
                }elseif(is_dir($directory.$file)) {
                    if(!file_exists($directory.$file.'/.cignore'))
                        self::get_files_from_folder($directory.$file.'/');
                }
            }
        }
        return $files_to_zip;
    }

    static function create_zip($files = array(), $destination = '', $name, $overwrite = false) {
    
        if(file_exists($destination)) { $overwrite = true;}
        
        $valid_files = array();
        
        if(is_array($files)) {
            foreach($files as $file) {
                
                if(file_exists($file)) {
                    $valid_files[] = $file;
                }
            }
        }
        
        if(count($valid_files)) {
            
            $zip = new \ZipArchive();
            
            if($zip->open($destination,$overwrite ? \ZIPARCHIVE::OVERWRITE : \ZIPARCHIVE::CREATE) !== true) {
                return false;
            }
            
            foreach($valid_files as $file) {
        
                $filed = str_replace("../", "", $file);
                $filed = str_replace("./", "", $filed);
            
                $zip->addFile($file,$filed);
            }
            
            $zip->close();
    
            if(file_exists($destination)){
                $hashing = self::generate_sha_file('../'.$name, $name);
    
                if($hashing){
                    require_once "ArrayToPHP.php";
    
                    $AtPp = new ArrayToPhP();
    
                    $convertp = $AtPp->atphash($hashing, 'sha', "", "/* FIN DEL HASH*/");
                    $AtPp->generate('./data/sha/'.$name.'.php',$convertp);
                }
            }
            
            $return = array(
                'status'    => 'success',
                'msg'       => 'El archivo fue comprimido satisfactoriamente.',
            );
        }else{
            $return = array(
                'status'    => 'error',
                'msg'       => 'Ocurrio un error al comprimir el archivo. Verifique que el proyecto no esta vacio.',
            );
        }
    
        echo json_encode($return);
    }

    static function generate_sha_file($rute, $name){
        global $sha1;
        if(!file_exists($rute.'/.cignore')){
            $files = array_diff(scandir($rute), array('.','..', 'sha'));
    
            foreach ($files as $file) { 
                if(is_dir("$rute/$file")){
                    self::generate_sha_file("$rute/$file", $file);
                }else{
                    $sha1["$rute/$file"] = self::generate_sha("$rute/$file");
                }
            }
        }
    
        return $sha1;
    }

    static function delete_folder($dir) { 
        $files = array_diff(scandir($dir), array('.','..')); 
        foreach ($files as $file) { 
          (is_dir("$dir/$file")) ? self::delete_folder("$dir/$file") : unlink("$dir/$file"); 
        } 
        return rmdir($dir); 
    }

    static function rcopy($src, $dst) { #Para crear proyectos con un "skeleton" predefinido
        if (is_dir($src)) {
            mkdir($dst);
            $files = scandir($src);
    
            foreach ($files as $file)
                if ($file != "." && $file != "..") self::rcopy("$src/$file", "$dst/$file"); 
        }elseif(file_exists($src))copy($src, $dst);
    }

    static function init_extension($ext = NULL){ #agregar una extencion a apache y reinicia, creado solo para windows
        $rute = dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/php/php.ini";
        $ini = fopen($rute, 'r+');
    
        $content = "";
    
        if(file_exists($rute)){
            while(!feof($ini)) {
                $content .= fgets ($ini,4096);
            }
    
            fclose($ini);
    
            $content = str_replace(';extension='.$ext, 'extension='.$ext, $content);
    
            file_put_contents($rute, $content);
        }
    
        if(self::run_app("apache_stop.bat")){
            sleep(3);
            self::run_app("apache_start.bat");
        }
    }
    
    static function run_app($app = NULL){
        $xampp = dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/";
        if(!is_null($app))
            exec($xampp.$app);
    }
}
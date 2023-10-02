<?php

namespace Core\Classes;

class Skeleton{
    static function get_skeletons(){
        $dirs = array_diff(scandir('./data/skeleton'), array('.', '..', 'index.php', 'Thumbs.db', '.cignore'));
        
        $skeletons = [];

        foreach ($dirs as $id => $dir) {
            $rute = './data/skeleton/'.$dir;
            if(is_dir('./data/skeleton/'.$dir)){
                $core = Tools::identify($rute);
                
                $skeletons[$id] = [
                    'name'      => $dir,
                    'framework' => $core,
                    'rute'      => $rute
                ];
            }
        }

        return $skeletons;
    }
}
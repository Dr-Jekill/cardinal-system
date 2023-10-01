<?php 

namespace Core\Classes;

/**
******************************************************************
* @brief Crea código PHP con un array como contenido
*
* @param $array    Array a insertar
* @param $array2   Segundo Array a insertar
* @param $phpvar   Variable PHP donde vamos a asignarlo
* @param $precode  Código PHP a incluir antes
* @param $postcode Código PHP a incluir después
*
* @return Código PHP
*
******************************************************************/

class ArrayToPhP{

	public function var2php(&$var)
	{
	  if (is_numeric($var))
	    $o=$var;
	  else
	    $o='\''.addslashes($var).'\'';

	  return $o;
	}

	public function array2php(&$array)
	{
	  $o='';
	  if (is_array($array))
	    {
	        $o.=' array(';
	        foreach ($array as $key=>$value) {
		      $o.=$this->var2php($key).' => '.$this->array2php($value).",\n";
		    }
	        $o.=')';
	    }
	  else{
	  	$o.=$this->var2php($array);
	  }
	    
	  return $o;
	}

    public function atphash(&$array, $phpvar, $precode, $postcode)
	{
		$comment = "\n\n/**";
		$comment .= "\n******************************************************************";
		$comment .= "\n*";
		$comment .= "\n* Script PHP ZSUPDATER";
		$comment .= "\n* Creado por Jekill";
		$comment .= "\n* v1.0";
		$comment .= "\n*";
		$comment .= "\n******************************************************************/";


		$o ="<?php\n ";
		$o.="/* Fichero generado automáticamente por ZSU el ".date("d-m-Y")." a las ".date("H:i");
	    $o.=" */\n".$comment."\n\n".$precode."\n\n/* HASH */\n\n";

		$o.='$'.$phpvar.' = '.$this->array2php($array).';'."\n";

		$o.="\n".$postcode."\n?>";

	 	return $o;
    }

    public function generate($rute, $convert){
    	@file_put_contents($rute, $convert);
    }
}
 ?>

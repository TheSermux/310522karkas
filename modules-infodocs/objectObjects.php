<?php

/**
 * @copyright 2021
 * @author Darkas (mod. Armaturine)
 * @copyright REDUIT Co.
 */
 
namespace RedCore\Infodocs;

class ObjectObjects extends \RedCore\Base\ObjectBase {
	
	public static function Create() {
	    return new ObjectObjects();
	}

	public function __construct() {
		
		$this->table = "objects";

		$this->properties = array(
		
			"id"         => "Number",
			"name"      => "String",
			"params" => array(
				
			),
			
		    "_deleted" => "Number",
		);

	}
}

?>
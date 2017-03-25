<?php

class seller extends user {	
	
	private $dbo;
	private $dbn;
	
	function __construct($db) {
		
		$this->dbo = $db;
		$this->dbn = Conf::DBNAME.".";
		parent::__construct($this->dbo);

	}

}
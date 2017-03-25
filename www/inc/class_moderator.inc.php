<?php

class moderator extends user {	
	
	private $dbo;
	private $dbn;
	
	function __construct($db) {
		
		$this->dbo = $db;
		$this->dbn = Conf::DBNAME.".";
		parent::__construct($this->dbo);

	}
	
	public function getSellerWaitlist() {		
		$data = $this->dbo->select($this->dbn."USERINFO", array("lvl" => 1, "upgrade" => "Y"), NULL, "*", FALSE, FALSE);
		if (count($data) > 0) {
			return $data;
		}
		return null;
	}
	
	public function getModWaitlist() {
		$data = $this->dbo->select($this->dbn."USERINFO", array("lvl" => 2, "upgrade" => "Y"), NULL, "*", FALSE, FALSE);
		if (count($data) > 0) {
			return $data;
		}
		return null;
	}
	
	public function approveAll($lvl=NULL) {
		if ($lvl == null) {
			return false;
		}		
		if ($this->account_type() == 3) {
			
			if ($lvl == 1) { $userquery = $this->getSellerWaitlist(); }			
			if ($lvl == 2) { $userquery = $this->getModWaitlist(); }
			
			foreach ($userquery as $q) {
				$this->dbo->update($this->dbn."USERINFO", array("lvl" => ($lvl+1), "upgrade" => "N"), array("uid" => $q["uid"]));				
			}
			return true;
		}
		return false;
	}
	
	public function denyAll($lvl=NULL) {
		if ($lvl == null) {
			return false;
		}
		if ($this->account_type() == 3) {		
			if ($this->dbo->update($this->dbn."USERINFO", array("upgrade" => "N"), array("lvl" => $lvl))) {
				return true;
			}			
		}
		return false;
	}
	
	public function batchApprove($post, $lvl) {
		if ($lvl == null || $post == NULL) {
			return false;
		}
		if ($this->account_type() == 3) {			
			while($element = current($post)) {
				if (strpos (key($post), "uid_") !== false) {					
					$uid = str_replace ("uid_", "", key($post));					
					$upgrade = prep($post[key($post)], $flag="a");					
					$ulvl = ($upgrade == "Y" ?  ($lvl+1) : $lvl);					
					$this->dbo->update($this->dbn."USERINFO", array("lvl" => $ulvl, "upgrade" => "N"), array("uid" => $uid));					
				}
				next($post);
			}
			return true;
		}
		return false;		
	}

}
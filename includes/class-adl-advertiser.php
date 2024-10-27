<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ADL_Advertiser {

	public static function init() {
	}
		
	public static function get_advertiser($args='role=advertiser'){
		$users = get_users($args);
		if(!empty($users)){
			return $users;
		}else{
			return false;
		}
	}
}
ADL_Post_types::init();

?>
<?php
	class ShangjifankuiModel extends Model {    //商机反馈表   
		public $_id='Shangjifankui_id';
		public $_sjid='Shangjifankui_sjid';                																  
		public $_content='Shangjifankui_content';                                            
		public $_person='Shangjifankui_person';
		public $_date='Shangjifankui_date';
		public $_url='Shangjifankui_url';
	}
// tb_shangjifankui    商机反馈表   （是否将最新反馈内容，更新到商机，项目状态？）
// Shangjifankui_id  //ID
// Shangjifanku_sjid     //商机ID
// Shangjifanku_content  //反馈内容
// Shangjifanku_person  //反馈人
// Shangjifanku_date  //反馈时间   

 // 字段内容做追加 原生sql：update table set a=concat(a,"123") where id=1;   a为字段；
				  // $data0['a'] = array('exp',"concat(`includes`,'/123')");
	
?>


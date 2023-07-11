<?php
	class SitesdzcModel extends Model {    //属地、职称类别" 
		public $_id='Sitesdzc_id';
		public $_panduan='Sitesdzc_panduan';                																  
		public $_name='Sitesdzc_name';                                            
		public $_nameid='Sitesdzc_nameid';
		public $_qxwgid='Sitesdzc_qxwgid';   //区县网格ID
		public $_person='Sitesdzc_person';
		public $_date='Sitesdzc_date';
	}
	
// tb_sitesdzc   属地、职称类别

// Sitesdzc_id       //ID
// Sitesdzc_panduan       //类别ID  （1 表示区县、2表示网格、3表示职称）
// Sitesdzc_name       //名称 
// Sitesdzc_nameid     //网格外键 区县名称   职称时可为空
// Sitesdzc_person      //创建时间
// Sitesdzc_date      //创建时间
	
?>





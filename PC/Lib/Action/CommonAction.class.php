<?php
class CommonAction extends Action{
	
	/*定义公共类库，完成页面编码方式的解析，会自动调用该方法，无需手动调用*/
	public function _initialize(){
		header("Content-Type:text/html; charset=utf-8");		
		if(isset($_SESSION['expiretime'])) {
			if($_SESSION['expiretime'] < time()) {
					session(null);
					// parent::PageRedirect();
					$this->redirect('__APP__/Index/index');	
			} else {
				$_SESSION['expiretime'] = time() + C("sitesessionouttime"); // 刷新时间戳
			}
		}	
	}
	
	
	
	
	/*随机码
     */
	public function GetRandStr($length){
		$str='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$len=strlen($str)-1;
		$randstr='';
		for($i=0;$i<$length;$i++){
			$num=mt_rand(0,$len);
			$randstr .= $str[$num];
		}
		return $randstr;
	}
	
	/*功能：实现页面的重定向
     * 参数：$pageindex 要重定向的操作或者页面路径
     * 参数：$param 要传递的参数，数组类型
     */
    public function PageRedirectParam($pageindex,$param){
        $this->redirect($pageindex,$param);
    }
    
	/*功能：实现提示信息的弹出对话框
		 * 参数：$message 弹出信息
		*/
	public function Windowalert($message){
		echo"<script>window.alert('".$message."');</script>";
	}
		/*功能：商机过期时间功能
		 * 参数：$message 弹出信息
		
	public function Shangji_datesession($message){
		$Shangji=new ShangjiModel(); 
		for(){
			
		}
	}*/	
	/*功能：实现返回上一页
		*/
	public function BackHistoryPage(){
		echo"<script>window.history.go(-1);</script>";
	}
	/*功能：实现页面的重定向
	 * 参数：$pageindex 要重定向的操作或者页面路径
	*/
	public function PageRedirect($pageindex){
		$this->redirect($pageindex);
	}
	public function YanZheng($sessionname,$page){
		if(C("siteloginonly")==1){
			if($sessionname!=null){
				$Person=new PersonModel();                              //唯一性登录
				$suijima=session('suijima');	
				$condition[$Person->_id]=session('loginemails');          
				$list=$Person->where($condition)->find();
				$panduan=$list["Person_yanzheng"];
				if($suijima!=$panduan){
					session(null);	
					echo "<script language=\"JavaScript\">\r\n";
					echo " alert(\"您已在其他客户端登录啦！请注意账号安全。\");\r\n";
					echo " location.replace(\" \");\r\n"; // 自己修改网址
					echo "</script>";
					exit;
					$this->redirect('__APP__/Index/index');
				}
			}
		}
		if(!session('?'.$sessionname)){
			$this->Windowalert('你不是合法访问该网页，请先登录！');
			$this->PageRedirect($page);	
		}

	}
	/*功能：对页面的保护
	网站设置功能
	*/
	
	public function website(){
		$Webset=new WebsetModel();
		$conditionwebset["Webset_id"]=1;
		$websitelist=$Webset->where($conditionwebset)->find();
		return $websitelist;
	}
	
	/*日志记录
	*/

	public function logadd($log_action,$log_kind,$log_group,$log_text){
		//记录日志
		$user_OSagent = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($user_OSagent, "Maxthon") && strpos($user_OSagent, "MSIE")) {
			$visitor_browser = "Maxthon(Microsoft IE)";
		} elseif (strpos($user_OSagent, "Maxthon 2.0")) {
			$visitor_browser = "Maxthon 2.0";
		} elseif (strpos($user_OSagent, "Maxthon")) {
			$visitor_browser = "Maxthon";
		} elseif (strpos($user_OSagent, "Edge")) {
			$visitor_browser = "Edge";
		} elseif (strpos($user_OSagent, "Trident")) {
			$visitor_browser = "IE";
		} elseif (strpos($user_OSagent, "MSIE")) {
			$visitor_browser = "IE";
		} elseif (strpos($user_OSagent, "MSIE")) {
			$visitor_browser = "MSIE 较高版本";
		} elseif (strpos($user_OSagent, "NetCaptor")) {
			$visitor_browser = "NetCaptor";
		} elseif (strpos($user_OSagent, "Netscape")) {
			$visitor_browser = "Netscape";
		} elseif (strpos($user_OSagent, "Chrome")) {
			$visitor_browser = "Chrome";
		} elseif (strpos($user_OSagent, "Lynx")) {
			$visitor_browser = "Lynx";
		} elseif (strpos($user_OSagent, "Opera")) {
			$visitor_browser = "Opera";
		} elseif (strpos($user_OSagent, "MicroMessenger")) {
			$visitor_browser = "微信浏览器";
		} elseif (strpos($user_OSagent, "Konqueror")) {
			$visitor_browser = "Konqueror";
		} elseif (strpos($user_OSagent, "Mozilla/5.0")) {
			$visitor_browser = "Mozilla";
		} elseif (strpos($user_OSagent, "Firefox")) {
			$visitor_browser = "Firefox";
		} elseif (strpos($user_OSagent, "U")) {
			$visitor_browser = "Firefox";
		} else {
			$visitor_browser = "其它";
		}

		$Logs=new LogModel();
		$yonghu=new PersonModel();
		$userid=session('loginemails');
		$yonghucondition[$yonghu->_id]=$userid;
		$yonghulist=$yonghu->where($yonghucondition)->find();
	
			
		$logdata["log_action"]=$log_action;
		$logdata["log_kind"]=$log_kind;
		$logdata["log_group"]=$log_group;
		$logdata["log_ip"]=$_SERVER["REMOTE_ADDR"];
		$logdata["log_view"]=$visitor_browser;
		$logdata["log_localxq"]=$yonghulist[$yonghu->_localxq];
		$logdata["log_person"]=$yonghulist[$yonghu->_name];
		$logdata["log_date"]=date('Y-m-d H:i',time());
		$logdata["log_text"]=$log_text;
				
		$Logs->add($logdata);	
	}
	
	
}
?>
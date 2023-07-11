<?php

class UsersAction extends CommonAction {

    public function personedit(){				//个人信息修改
		parent::YanZheng('loginemails','__APP__/Index/index');
		$User=new PersonModel();//dump($User);exit;
		$tempid=session('loginemails');
		$condition[$User->_id]=$tempid;
		$editlist=$User->where($condition)->find();
		if($editlist){
			$this->assign('editlist',$editlist);	
			$this->display();
		}else{
			echo 0;
			exit;
		}	
		
    } 
	public function personedit_action(){				//个人信息保存方法
		parent::YanZheng('loginemails','__APP__/Index/index');  //验证非法用户进出页面	
		$Person=new PersonModel();
		$condition1[$Person->_id]=session('loginemails');            //判定数据中心条件
		$list1=$Person->where($condition1)->find();
				$tempuserphone=trim($_POST['userphone']); 
				$tempusermail=trim($_POST['usermail']); 
				$tempsex=trim($_POST['sex']); 
				$tempmimaselect=trim($_POST['mimaselect']); 
				$tempmima=trim($_POST['mima']); 
				$tempremima=trim($_POST['remima']); 
				if(strlen($tempuserphone)>11){
					echo "手机格式错误或超出11位数！";
					exit;
				}
				if(!filter_var($tempusermail, FILTER_VALIDATE_EMAIL)){
				   echo "邮箱格式错误！";
				   exit;
				}
				if($tempmimaselect=="是"){
					if($tempmima == null and $tempremima == null){
						echo "输入密码不能为空！";
						exit;
					}
					if(strlen($tempmima)!=strlen($tempremima)){
						echo "两次密码长度不一致！";
						exit;
					}
					if($tempmima!=$tempremima){
						echo "两次密码不一致！";
						exit;
					}
					$data["Person_pwd"]=md5($tempremima); 
				}
			$data["Person_phone"]=$tempuserphone;
			$data["Person_mail"]=$tempusermail; 
			$data["Person_fex"]=$tempsex;  
			$condition["Person_id"]=session('loginemails'); 
			// 写入用户数据到数据库if($result !== false)
			$results = $Person->where($condition)->save($data);
			if($results!==false){
				echo 1;
			}
			else{
				echo "保存失败！联系管理员！";
				exit;
			}
    }
	
	public function userslist(){				//用户管理列表
		parent::YanZheng('loginemails','__APP__/Index/index');
		$User=new PersonModel();//dump($User);exit;
		$tempid=session('loginemails');
		$condition[$User->_id]=$tempid;
		$userlist=$User->where($condition)->find();
		$this->assign('loginusers',$userlist);
		
		$tiaojian["Person_localxq"]=$userlist["Person_localxq"];
		import('ORG.Util.Page');
		$Users=$User->where($tiaojian)->count();
		$P= new Page($Users,C("userpage"));
		$list=$User->where($tiaojian)->limit($P->firstRow.','.$P->listRows)->order('Person_int desc')->select();
		$Page=$P->show();
		$this->assign('list',$list);
		$this->assign('Page',$Page);
		
		
		$Sitesdzc=new SitesdzcModel();// 网格
		$Sitecontion[$Sitesdzc->_panduan]=2;
		$Sitecontion[$Sitesdzc->_nameid]=$userlist["Person_localxq"];
		$Sitelist=$Sitesdzc->where($Sitecontion)->order('Sitesdzc_id')->select();
		foreach ($Sitelist as $k => $v) {
			$Sitecontioncount["Person_localwg"]=$v['Sitesdzc_name'];
			$Sitelist[$k]["Ipsix"]=$User->where($Sitecontioncount)->count();
		}
		$this->assign('Sitelist',$Sitelist);   //网格快捷列表
		
		
		$this->display();
	}
	public function searchuserslist(){				//用户搜索
		parent::YanZheng('loginemails','__APP__/Index/index');
		$User=new PersonModel();//dump($User);exit;
		$tempid=session('loginemails');
		$condition[$User->_id]=$tempid;
		$userlist=$User->where($condition)->find();
		$this->assign('loginusers',$userlist);
	
		$temptype=$_GET["kind"];
		$tempkeywords=$_GET["guanjianzi"];
		$tiaojian[$temptype]=array('like','%'.$tempkeywords.'%');
		$s=$User->where($tiaojian)->select();
		if($s){
			import('ORG.Util.Page');
			$count=$User->where($tiaojian)->count();
			$Page= new Page($count,C("userpage"));
			foreach($tiaojian as $key=>$val) {
				$Page->parameter   .=   "$key=".urlencode($val).”&”;//分页跳转的时候保证查询条件
			}
			$list=$User->where($tiaojian)->limit($Page->firstRow.','.$Page->listRows)->order('Person_int desc')->select();
			$Page=$Page->show();
			$this->assign('list',$list);
			$this->assign('Page',$Page);
			$this->assign('temptype',$temptype);
			$this->assign('tempkeywords',$tempkeywords);
			$this->display();
			
		}else{
			$this->display();
		}

	}
	public function usersadd(){				//用户新增
		parent::YanZheng('loginemails','__APP__/Index/index');
		$User=new PersonModel();//dump($User);exit;
		$tempid=session('loginemails');
		$condition[$User->_id]=$tempid;
		$userlist=$User->where($condition)->find();
		$this->assign('loginusers',$userlist);
		
			$website=parent::website();
			$this->assign('website',$website);   
		
		
		if($userlist["Person_biaoshi"]==0){
			echo "非法操作！";
			exit;
		}
		$Sitesdzc=new SitesdzcModel();//
		$wanggecontion[$Sitesdzc->_panduan]=2;
		$wanggecontion[$Sitesdzc->_nameid]=$userlist["Person_localxq"];
		if($userlist["Person_biaoshi"]==2){
			$zhichengcontion[$Sitesdzc->_name]=array('like','%'."经理".'%');   //如果是网格主任只能添加***经理
		}	
		$zhichengcontion[$Sitesdzc->_panduan]=3;
		$wanggelist=$Sitesdzc->where($wanggecontion)->select();
		$zhichenglist=$Sitesdzc->where($zhichengcontion)->select();
		$this->assign('wanggelist',$wanggelist);   //属地
		$this->assign('zhichenglist',$zhichenglist);   //岗位职称
		$this->display();
	}
	public function add_user_action(){				//用户添加方法
		parent::YanZheng('loginemails','__APP__/Index/index');  //验证非法用户进出页面	 
		$Person=new PersonModel();
				$tempzhanghao=trim($_POST['zhanghao']);   
				$tempnames=trim($_POST['names']);
				$tempsex=trim($_POST['sex']);  
				$tempiphone=trim($_POST['iphone']);  
				$tempemail=trim($_POST['email']); 
				$tempfirst=trim($_POST['first']); 
				$tempsecond=trim($_POST['second']); 
				$tempzhicheng=trim($_POST['zhicheng']); 
				$tempqx=trim($_POST['qx']); 	
				$tempdate=date('Y-m-d H:i',time());
				if(strlen($tempzhanghao)>6 or strlen($tempzhanghao)<6){
					echo "账号格式错误或超出、不足6位数！";
					exit;
				}
				if(strlen($tempiphone)>11){
					echo "手机格式错误或超出11位数！";
					exit;
				}
				if(!filter_var($tempemail, FILTER_VALIDATE_EMAIL)){
				   echo "邮箱格式错误！";
				   exit;
				}
				//判断账号重复
				$panduantiaojian["Person_id"]=$tempzhanghao;
				$panduan=$Person->where($panduantiaojian)->find();
				if($panduan){
					echo "该账号已经存在啦！";
					exit;
				}
				if($tempsecond != null){
					if($tempqx==1){
						echo "权限矛盾，管理员权限全区县！";
						exit;
					}
				}	
			$data["Person_name"]=$tempnames;
			
			$website=parent::website();
			$data["Person_pwd"]=md5($website['Webset_usersdefaultpwd']);
			$data["Person_phone"]=$tempiphone; 
			$data["Person_id"]=$tempzhanghao;  
			$data["Person_biaoshi"]=$tempqx; 
 			$data["Person_gangwei"]=$tempzhicheng;
			$data["Person_fex"]=$tempsex;
			$data["Person_mail"]=$tempemail;
			$data["Person_zhucedate"]=$tempdate;
			$data["Person_yanzheng"]=123456;
			$data["Person_localxq"]=$tempfirst;
			$data["Person_localwg"]=$tempsecond;
			  // dump($data);exit;
			// 写入用户数据到数据库
			if($Person->add($data)){
				echo 1;
			}
			else{
				echo "添加失败，联系管理员！";
				exit;
			}
    }
	
	public function del_user(){                            //用户删除功能
		parent::YanZheng('loginemails','__APP__/Index/index');  //验证非法用户进出页
		$Person=new PersonModel();
		$condition["Person_int"]=$_GET['id'];
		$del=$Person->where($condition)->delete();
		if($del){
			$code = 1;
			echo  $code;	
		}else{
			$code = 0;
			echo  $code;
			exit;
		} 
	} 
	public function usersedit(){				//用户编辑
		parent::YanZheng('loginemails','__APP__/Index/index');  //验证非法用户进出页面	
		$Person=new PersonModel();
		$tempid=session('loginemails');
		$panduantiaojian[$Person->_id]=$tempid;
		$userlist=$Person->where($panduantiaojian)->find();
		$this->assign('loginusers',$userlist);
		
			$website=parent::website();
			$this->assign('website',$website);   
		
		
			if($userlist["Person_biaoshi"]==0){
				echo "非法操作！";
				exit;
			}
		$condition["Person_int"]=$_GET['id'];
		$editlist=$Person->where($condition)->find();
		if($editlist){
			$this->assign('editlist',$editlist);	
			$Sitesdzc=new SitesdzcModel();//
			$wanggecontion[$Sitesdzc->_panduan]=2;
			$wanggecontion[$Sitesdzc->_nameid]=$userlist["Person_localxq"];
			if($userlist["Person_biaoshi"]==2){
				$zhichengcontion[$Sitesdzc->_name]=array('like','%'."经理".'%');   //如果是网格主任只能添加***经理
			}	
			$zhichengcontion[$Sitesdzc->_panduan]=3;
			$wanggelist=$Sitesdzc->where($wanggecontion)->select();
			$zhichenglist=$Sitesdzc->where($zhichengcontion)->select();
			$this->assign('wanggelist',$wanggelist);   //属地
			$this->assign('zhichenglist',$zhichenglist);   //岗位职称
			$quxiancontion[$Sitesdzc->_panduan]=1;

			$this->display();
		}else{
			echo 0;
			exit;
		}	
	}
	public function edt_user_action(){				//用户编辑方法
		parent::YanZheng('loginemails','__APP__/Index/index');  //验证非法用户进出页面	 
		$Person=new PersonModel();
				$tempeditid=trim($_POST['editid']);    //获取主键ID
				$tempmimapan=trim($_POST['mimapan']);  //判断是否修改密码
			//	$tempzhanghao=trim($_POST['zhanghao']);   
				$tempnames=trim($_POST['names']);
				$tempsex=trim($_POST['sex']);  
				$tempiphone=trim($_POST['iphone']);  
				$tempemail=trim($_POST['email']); 
				$tempfirst=trim($_POST['first']); 
				$tempsecond=trim($_POST['second']); 
				$tempzhicheng=trim($_POST['zhicheng']); 
				$tempqx=trim($_POST['qx']); 	
				if(strlen($tempiphone)>11){
					echo "手机格式错误或超出11位数！";
					exit;
				}
				if(!filter_var($tempemail, FILTER_VALIDATE_EMAIL)){
				   echo "邮箱格式错误！";
				   exit;
				}
				if($tempsecond != null){
					if($tempqx==1){
						echo "权限矛盾，管理员权限全区县！";
						exit;
					}
				}	
			$data["Person_name"]=$tempnames;
			$data["Person_phone"]=$tempiphone; 
			$data["Person_biaoshi"]=$tempqx; 
 			$data["Person_gangwei"]=$tempzhicheng;
			$data["Person_fex"]=$tempsex;
			$data["Person_mail"]=$tempemail;
			if($tempmimapan == "是"){
				$website=parent::website();
				$data["Person_pwd"]=md5($website['Webset_usersdefaultpwd']);
			}
			$data["Person_localxq"]=$tempfirst;
			$data["Person_localwg"]=$tempsecond;
			
			$condition["Person_int"]=$tempeditid;
			// 写入用户数据到数据库
			if($Person->where($condition)->save($data)){
				echo 1;
			}
			else{
				echo "保存失败，或 未做修改！";
				exit;
			}
    }
	
	
}
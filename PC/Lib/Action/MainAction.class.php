<?php

class MainAction extends CommonAction {
	
    public function webset(){				//网站设置页面
		parent::YanZheng('loginemails','__APP__/Index/index');
		$User=new PersonModel();
		$tempid=session('loginemails');
		$condition[$User->_id]=$tempid;
		$userlist=$User->where($condition)->find();
		$this->assign('loginusers',$userlist);
		if($userlist["Person_biaoshi"]!=1){
			echo "您没权限！只有管理员可以操作此项。";
			exit;
		}
		$Webset=new WebsetModel();
		$conditionwebset["Webset_id"]=1;
		$websetlist=$Webset->where($conditionwebset)->find();
		$this->assign('websetlist',$websetlist);
		$this->display();
    }
	
    public function websetaction(){				//网站设置页面方法
		parent::YanZheng('loginemails','__APP__/Index/index');
		$User=new PersonModel();
		$tempid=session('loginemails');
		$condition[$User->_id]=$tempid;
		$userlist=$User->where($condition)->find();
		$this->assign('loginusers',$userlist);
		
		$Webset=new WebsetModel();
		$conditionwebset["Webset_id"]=1;
		$data["Webset_sitename"]=trim($_POST['Webset_sitename']); 
		$data["Webset_adminname"]=trim($_POST['Webset_adminname']); 
		$data["Webset_adminurl"]=trim($_POST['Webset_adminurl']); 
		$data["Webset_icpname"]=trim($_POST['Webset_icpname']); 
		$data["Webset_icpurl"]=trim($_POST['Webset_icpurl']); 
		$data["Webset_usersdefaultpwd"]=trim($_POST['Webset_usersdefaultpwd']); 
		$data["Webset_loginurl1"]=trim($_POST['Webset_loginurl1']); 
		$data["Webset_loginurl2"]=trim($_POST['Webset_loginurl2']); 
		$data["Webset_loginurl3"]=trim($_POST['Webset_loginurl3']); 
		//dump($data);exit;
		$res=$Webset->where($conditionwebset)->save($data);
			// 写入用户数据到数据库		
			//echo $res;
			if($res!=false){
				 //记录日志
				$logtext="编辑了网站设置！";
				parent::logadd('修改','编辑','websetaction',$logtext); 
				echo 1;
				  //属地
			}elseif($res==0){
				echo 1;
				
			}else{
				echo "保存失败，联系管理员！";
				exit;
			}
    }
	
	public function clear(){
/* 		$path = $this->options['temp'];
		$files = scandir($path);
		if($files){
			foreach($files as $file){
				if($file != '.' && $file != '..' && is_dir($path.$file)){
					array_map('unlink',glob($path.$file.'/*.*'));
				}elseif(is_file($path.$file)){
					unlink($path.$file);
				}
			}
			return true;
		}
		return false; */
/* 		$url="__ROOT__/PC/Runtime/";
		unlink($url);
		$cache = new \Think\Cache();
		$cache->getInstance()->clear(); */
		//RUNTIME_FILE常量是入口文件中配置的runtimefile的路径及文件名；
		if(file_exists(RUNTIME_FILE)){
			unlink(RUNTIME_FILE); //删除RUNTIME_FILE;
		}
		//光删除runtime_file还不够，要清空一下Cache文件夹中的文件；代码如下：
		$cachedir = RUNTIME_PATH."/Cache/";   //Cache文件的路径；
		if ($dh = opendir($cachedir)) {     //打开Cache文件夹；
			while (($file = readdir($dh)) !== false) {    //遍历Cache目录，
				unlink($cachedir.$file);                //删除遍历到的每一个文件；
			}
			closedir($dh);
		} 
		
	}
	
    public function urlsm(){				//urlsm
		parent::YanZheng('loginemails','__APP__/Index/index');
		$this->display();
    } 
    public function main(){				//main页面显示
		parent::YanZheng('loginemails','__APP__/Index/index');
		$User=new PersonModel();//dump($User);exit;
		$tempid=session('loginemails');
		$condition[$User->_id]=$tempid;
		$userlist=$User->where($condition)->find();
		$this->assign('loginusers',$userlist);
		
			$website=parent::website();
			$this->assign('website',$website);   
		
/* 		if($userlist["Person_biaoshi"]==10){   //判断权限如果是非10，跳转至区县控制台页面
			$url="http://".$_SERVER['HTTP_HOST'];
			header('location:'. $url);
		} */
		
		$tiaojian['Person_localxq']=$userlist["Person_localxq"];     //判断一个主任管多个网格，区县、手机号、权限为3、邮箱都相同
		$tiaojian['Person_phone']=$userlist["Person_phone"];
		$tiaojian['Person_biaoshi']=$userlist["Person_biaoshi"];
		$tiaojian['Person_mail']=$userlist["Person_mail"];
		$list=$User->where($tiaojian)->order('Person_int desc')->select();
		$listcount=$User->where($tiaojian)->order('Person_int desc')->count();
		if($listcount>1){
			$this->assign('panding',1);
		}
		$this->assign('list',$list);
		$this->display();
    } 
	function qiehuanuser(){  //切换用户
		parent::YanZheng('loginemails','__APP__/Index/index');
		$Users=new PersonModel();
		$logtext="PC端切换成功！";
		parent::logadd('修改','退出','qiehuanuser',$logtext);
		session(null);
		$condition["Person_id"]=$_GET['userid']; 
		$userlist=$Users->where($condition)->find();
	//	dump($userlist);exit;
		if($userlist["Person_pwd"]==$_GET['pwd']){
				$suijinumber=parent::GetRandStr(10);    //获取随机码
				session('loginemails', $userlist[$Users->_id]);
                session('loginnicknames', $userlist[$User->_name]);
				if(C("siteloginonly")==1){
					session('suijima', $suijinumber);								  //唯一性登录
				}
				$tiaojian[$Users->_id]=$userlist[$Users->_id];
				$data["Person_lastdate"]=date('Y-m-d H:i',time()); 
				if(C("siteloginonly")==1){
					$data["Person_yanzheng"]=$suijinumber;                            //唯一性登录
				}
				$_SESSION['expiretime'] = time() + C("sitesessionouttime");        //定义session过期时间，秒
				$Users->where($tiaojian)->save($data);
				$this->redirect('__APP__/Main/main');
				 //记录日志
				$logtext="PC端登录成功！账号为：".session('loginemails');
				parent::logadd('修改','登录','qiehuanuser',$logtext);
		}else{
			echo $userlist["Person_name"].$_GET['userid'].$userlist["Person_localwg"]."账号密码与切换前不一致。";
			exit;
		}
	}
    public function mainindex(){				//mainindex页面显示
		parent::YanZheng('loginemails','__APP__/Index/index');
		$User=new PersonModel();//dump($User);exit;
		$tempid=session('loginemails');
		$condition[$User->_id]=$tempid;
		$userlist=$User->where($condition)->find();
		$this->assign('loginusers',$userlist);
		
		$Shangji=new ShangjiModel();
		$tiaojian["Shangji_localqx"]=$userlist["Person_localxq"];
		
		//$shuzu = array("已报价","跟进中");
		//$tiaojian['Shangji_zhuangtai']=array("in",$shuzu);
		//商机设置情况应用
		$Siteset=new SitesetModel();
		$condition11[$Siteset->_personid]=session('loginemails');
		$condition11[$Siteset->_kind]=1;
		$list11=$Siteset->where($condition11)->find();      //页码设置
		$condition22[$Siteset->_personid]=session('loginemails');
		$condition22[$Siteset->_kind]=2;
		$condition22[$Siteset->_zhuangtai]=1;
		$list22=$Siteset->where($condition22)->select();    //状态条件设置
		$shuzu = array_filter(array($list22[0]['siteset_content'],$list22[1]['siteset_content'],$list22[2]['siteset_content'],$list22[3]['siteset_content'],$list22[4]['siteset_content'],$list22[5]['siteset_content']));
		$tiaojian['Shangji_zhuangtai']=array("in",$shuzu);
		
		
		$model=$Shangji->where($tiaojian)->field('count(Shangji_localwg) num,Shangji_localwg')->group('Shangji_localwg')->order('num desc ,Shangji_id desc')->select();
		$this->assign('shangjialllist',$model);
		
	   
		$Sitecphy=new SitecphyModel();// 产品、行业
		
		$chanpincontion[$Sitecphy->_panduan]=1;
		$chanpinlist=$Sitecphy->where($chanpincontion)->order('Sitecphy_id')->select();
		foreach ($chanpinlist as $k => $v) {
			$chanpinlisttiaojian["Shangji_product"]=array('like','%'.$v['Sitecphy_name'].'%');
			$chanpinlisttiaojian[$Shangji->_localqx]=$userlist["Person_localxq"];
			
	//	if(session('sjguolv')==1){
	//		$shuzu = array("已报价","跟进中");
	//		$chanpinlisttiaojian['Shangji_zhuangtai']=array("in",$shuzu);
	//	}
		//商机设置情况应用
		$Siteset=new SitesetModel();
		$condition11[$Siteset->_personid]=session('loginemails');
		$condition11[$Siteset->_kind]=1;
		$list11=$Siteset->where($condition11)->find();      //页码设置
		$condition22[$Siteset->_personid]=session('loginemails');
		$condition22[$Siteset->_kind]=2;
		$condition22[$Siteset->_zhuangtai]=1;
		$list22=$Siteset->where($condition22)->select();    //状态条件设置
		$shuzu = array_filter(array($list22[0]['siteset_content'],$list22[1]['siteset_content'],$list22[2]['siteset_content'],$list22[3]['siteset_content'],$list22[4]['siteset_content'],$list22[5]['siteset_content']));
		$chanpinlisttiaojian['Shangji_zhuangtai']=array("in",$shuzu);
		
		
		if($userlist["Person_biaoshi"]!=1){  //   主任、员工只能看到自己网格的
			$chanpinlisttiaojian["Shangji_localwg"]=$userlist["Person_localwg"];
		}
			$chanpinlist[$k]["Ipsix"]=$Shangji->where($chanpinlisttiaojian)->count();
		}
		$this->assign('chanpinlist',$chanpinlist);   //产品
		
		
		
		$tiaojianlaistlists["Shangji_localqx"]=$userlist["Person_localxq"];
		if($userlist["Person_biaoshi"]!=1){  //   主任、员工只能看到自己网格的  $where['a'] = array(14,10,'or'); $where="$temptselecttime > '$tempstartdate' and $temptselecttime <= '$tempjieshudate'";
			$tiaojianlaistlists["Shangji_localwg"]=$userlist["Person_localwg"];
		}
		$shuzu = array("已过期");
		$tiaojianlaistlists['Shangji_zhuangtai']=array("in",$shuzu);
		// $where="$temptselecttime > '$tempstartdate' and $temptselecttime <= '$tempjieshudate'";
		// $tiaojianlaistlists['_complex'] = $where;
		import('ORG.Util.Page');
		$Shangjis=$Shangji->where($tiaojianlaistlists)->count();
		$P= new Page($Shangjis,5);
		$list=$Shangji->where($tiaojianlaistlists)->limit($P->firstRow.','.$P->listRows)->order('Shangji_lastchulidate desc')->select();
		$Page=$P->show();
		
		
		$this->assign('list',$list);
		$this->assign('Page',$Page);
		

		$list1ss=$Siteset->where($condition22)->select();
		$this->assign('list1ss',$list1ss);                      //列出表头提示，依据商机设置。
		
		
		$this->display();
    } 

}
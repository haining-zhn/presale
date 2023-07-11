<?php

class IndexAction extends CommonAction {
    public function Index(){				//登录页面显示
		if(session('loginemails')==null){
			$website=parent::website();
			$this->assign('website',$website);
			$this->display();
		}else{
			parent::PageRedirect('__APP__/Main/main');
		}
    } 
	public function APP_loginaction(){ 
	//用户登录
		$Users=new PersonModel();
		$tempuseremail=$_POST['zhanghao'];
		$temppwd=$_POST['mima'];
		if(strlen($tempuseremail)<6 | strlen($tempuseremail)>6){
			echo '账号长度不对，请重新输入!';
			exit;
		}
		$condition[$Users->_id]=$tempuseremail;
		$userlist=$Users->where($condition)->find();
		$mima=md5($temppwd);
		if($userlist!=NULL){
            if($userlist[$Users->_pwd]==$mima){ 
				if($userlist["Person_biaoshi"] == C("nologin")){
					echo "该账号禁止登录，如有疑问，请联系管理员！";
					exit;
				}
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
				 echo 1;
				 //记录日志
				$logtext="PC端登录成功！账号为：".session('loginemails');
				parent::logadd('修改','登录','APP_loginaction',$logtext);
				
				//检查商机设置情况，没有会默认添加设置记录
				$Siteset=new SitesetModel();
				$condition11[$Siteset->_personid]=session('loginemails');
				$condition11[$Siteset->_kind]=1;
				
				$condition22[$Siteset->_personid]=session('loginemails');
				$condition22[$Siteset->_kind]=2;
				
				$shangjisetres11=$Siteset->where($condition11)->find();
				$shangjisetres22=$Siteset->where($condition22)->find();
				if($shangjisetres11==null){
					$data1["siteset_content"]=16;
					$data1["siteset_kind"]=1;
					$data1["siteset_personid"]=session('loginemails');
					$data1["siteset_zhuangtai"]=1;
					$Siteset->add($data1);
				}
				if( $shangjisetres22==null){
					$data2["siteset_content"]="初步洽谈";
					$data2["siteset_kind"]=2;
					$data2["siteset_personid"]=session('loginemails');
					$data2["siteset_zhuangtai"]=1;
					$Siteset->add($data2);
					
					$data3["siteset_content"]="已报价";
					$data3["siteset_kind"]=2;
					$data3["siteset_personid"]=session('loginemails');
					$data3["siteset_zhuangtai"]=1;
					$Siteset->add($data3);
					
					$data4["siteset_content"]="跟进中";
					$data4["siteset_kind"]=2;
					$data4["siteset_personid"]=session('loginemails');
					$data4["siteset_zhuangtai"]=1;
					$Siteset->add($data4);
					
					$data5["siteset_content"]="已签约";
					$data5["siteset_kind"]=2;
					$data5["siteset_personid"]=session('loginemails');
					$data5["siteset_zhuangtai"]=1;
					$Siteset->add($data5);
					
					$data6["siteset_content"]="已过期";
					$data6["siteset_kind"]=2;
					$data6["siteset_personid"]=session('loginemails');
					$data6["siteset_zhuangtai"]=1;
					$Siteset->add($data6);
					
					$data7["siteset_content"]="商机丢失";
					$data7["siteset_kind"]=2;
					$data7["siteset_personid"]=session('loginemails');
					$data7["siteset_zhuangtai"]=1;
					$Siteset->add($data7);
				}
				
				
            }
            else{
				echo "密码不正确";
				exit;
            }
		}else{
			echo "用户不存在！";
			exit;
        }
	}
	function APP_loginout(){  //用户退出
		$logtext="PC端退出成功！";
		parent::logadd('修改','退出','APP_loginout',$logtext);
		session(null);
        parent::Windowalert('退出系统成功!');
		parent::PageRedirect('index');
	}

}
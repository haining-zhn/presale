<?php

class ShangjiAction extends CommonAction {

    public function shangjiset(){				//商机设置页面
		parent::YanZheng('loginemails','__APP__/Index/index');
		$User=new PersonModel();
		$tempid=session('loginemails');
		$condition[$User->_id]=$tempid;
		$userlist=$User->where($condition)->find();
		$this->assign('loginusers',$userlist);

		$Siteset=new SitesetModel();
		$condition11[$Siteset->_personid]=session('loginemails');
		$condition11[$Siteset->_kind]=1;
		$list11=$Siteset->where($condition11)->select();
		$this->assign('list11',$list11);
		
		$condition22[$Siteset->_personid]=session('loginemails');
		$condition22[$Siteset->_kind]=2;
		$list22=$Siteset->where($condition22)->select();
		$this->assign('list22',$list22);
		$this->assign('list2201',$list22[0]["siteset_content"]);
		$this->assign('list2202',$list22[1]["siteset_content"]);
		$this->assign('list2203',$list22[2]["siteset_content"]);
		$this->assign('list2204',$list22[3]["siteset_content"]);
		$this->assign('list2205',$list22[4]["siteset_content"]);
		$this->assign('list2206',$list22[5]["siteset_content"]);
		$this->display();
    } 
    public function tubiaoindexset(){				//商机图表设置页面
		parent::YanZheng('loginemails','__APP__/Index/index');
		$User=new PersonModel();
		$tempid=session('loginemails');
		$condition[$User->_id]=$tempid;
		$userlist=$User->where($condition)->find();
		$this->assign('loginusers',$userlist);
		$startdate=$_GET['startdate'];
		$enddate=$_GET['enddate'];
		$guanjianzi=$_GET['guanjianzi'];
		if($startdate>$enddate){
			echo "<script type='text/javascript'>";
			echo " alert(\"开始时间不能大于结束时间！\");\r\n";
			echo " history.back();\r\n";
			echo "</script>";
			exit;
		}
	//	dump($guanjianzi);exit;
		session('tustartdate', $startdate);
		session('tuenddate', $enddate);
		session('tuguanjianzi', $guanjianzi);
		parent::PageRedirect('tubiaoindex');
	}
    public function tubiaoindex(){				//商机图表页面
		parent::YanZheng('loginemails','__APP__/Index/index');
		$User=new PersonModel();
		$tempid=session('loginemails');
		$condition[$User->_id]=$tempid;
		$userlist=$User->where($condition)->find();
		$this->assign('loginusers',$userlist);

		if($userlist["Person_biaoshi"]==0){
				echo "当前账号没权限查看，网格主任以上权限！";
				exit;
		}

		$Sitesdzc=new SitesdzcModel();// 网格
		$Sitecontion[$Sitesdzc->_panduan]=2;
		$Sitecontion[$Sitesdzc->_nameid]=$userlist["Person_localxq"];
		$Sitelist=$Sitesdzc->where($Sitecontion)->order('Sitesdzc_id desc')->select();
		$Sitelistcount=$Sitesdzc->where($Sitecontion)->count();
		$this->assign('Sitelist',$Sitelist);   //网格快捷列表
		
		//商机状态列表
		$Siteset=new SitesetModel();
		$condition22[$Siteset->_personid]=session('loginemails');
		$condition22[$Siteset->_kind]=2;
		$list22=$Siteset->where($condition22)->order('siteset_id asc')->select();    //商机状态列表
		$this->assign('list22',$list22);
		$this->assign('list2201',$list22[0]["siteset_content"]);
		$this->assign('list2202',$list22[1]["siteset_content"]);
		$this->assign('list2203',$list22[2]["siteset_content"]);
		$this->assign('list2204',$list22[3]["siteset_content"]);
		$this->assign('list2205',$list22[4]["siteset_content"]);
		$this->assign('list2206',$list22[5]["siteset_content"]);
		
		$sstustartdate=session('tustartdate');
		$sstuenddate=session('tuenddate');
		$sstuguanjianzi=session('tuguanjianzi');
		$temptShangji_date="Shangji_date";
 		$where="$temptShangji_date > '$sstustartdate' and $temptShangji_date <= '$sstuenddate'";
	if($sstustartdate!=null and $sstuenddate!=null){
		$tiaojian['_complex'] = $where;
		$tiaojian1['_complex'] = $where;
		$tiaojian2['_complex'] = $where;
		$tiaojian3['_complex'] = $where;
		$tiaojian4['_complex'] = $where;
		$tiaojian5['_complex'] = $where;
		$tiaojian6['_complex'] = $where;
	} 
	
	if($sstuguanjianzi!=null){
		if($sstuguanjianzi=="全网格"){
			
		}else{
			$tiaojian['Shangji_localwg'] = $sstuguanjianzi;
		}
	} 
		$tmpstartdate=date('Y-m-d');
		$tmpenddate=date('Y-m-d');
		$this->assign('tmpenddate',$tmpenddate);
		
		$Shangji=new ShangjiModel();
		$tiaojian["Shangji_localqx"]=$userlist["Person_localxq"];
		$tiaojian["Shangji_zhuangtai"]= '已签约';
		$sjcustmanager=$Shangji->where($tiaojian)->field('count(Shangji_custmanager) num,Shangji_custmanager')->group('Shangji_custmanager')->order('num desc ,Shangji_id desc')->limit(30)->select();     //客户经理已签约排行
		$this->assign('sjcustmanager',$sjcustmanager);
		
		for($i=0;$i<$Sitelistcount;$i++){	
			$tiaojian1["Shangji_localqx"]=$userlist["Person_localxq"];
			$tiaojian1["Shangji_zhuangtai"]=$list22[0]["siteset_content"];
			$tiaojian1["Shangji_localwg"]=$Sitelist[$i]["Sitesdzc_name"];
			$model1=$Shangji->where($tiaojian1)->count();      //状态1数据
            $newArr1[$i] = $model1;
		}
		$this->assign('newArr1',$newArr1);

	 	for($i=0;$i<$Sitelistcount;$i++){	
			$tiaojian2["Shangji_localqx"]=$userlist["Person_localxq"];
			$tiaojian2["Shangji_zhuangtai"]=$list22[1]["siteset_content"];
			$tiaojian2["Shangji_localwg"]=$Sitelist[$i]["Sitesdzc_name"];
			$model2=$Shangji->where($tiaojian2)->count();     //状态2数据
            $newArr2[$i] = $model2;
		}
		$this->assign('newArr2',$newArr2);
		
		for($i=0;$i<$Sitelistcount;$i++){	
			$tiaojian3["Shangji_localqx"]=$userlist["Person_localxq"];
			$tiaojian3["Shangji_zhuangtai"]=$list22[2]["siteset_content"];
			$tiaojian3["Shangji_localwg"]=$Sitelist[$i]["Sitesdzc_name"];
			$model3=$Shangji->where($tiaojian3)->count();     //状态3数据
            $newArr3[$i] = $model3;
		}
		$this->assign('newArr3',$newArr3);
		
		for($i=0;$i<$Sitelistcount;$i++){	
			$tiaojian4["Shangji_localqx"]=$userlist["Person_localxq"];
			$tiaojian4["Shangji_zhuangtai"]=$list22[3]["siteset_content"];
			$tiaojian4["Shangji_localwg"]=$Sitelist[$i]["Sitesdzc_name"];
			$model4=$Shangji->where($tiaojian4)->count();     //状态4数据
            $newArr4[$i] = $model4;
		}
		$this->assign('newArr4',$newArr4);
		
		for($i=0;$i<$Sitelistcount;$i++){	
			$tiaojian5["Shangji_localqx"]=$userlist["Person_localxq"];
			$tiaojian5["Shangji_zhuangtai"]=$list22[4]["siteset_content"];
			$tiaojian5["Shangji_localwg"]=$Sitelist[$i]["Sitesdzc_name"];
			$model5=$Shangji->where($tiaojian5)->count();     //状态5数据
            $newArr5[$i] = $model5;
		}
		$this->assign('newArr5',$newArr5);
		
		for($i=0;$i<$Sitelistcount;$i++){	
			$tiaojian6["Shangji_localqx"]=$userlist["Person_localxq"];
			$tiaojian6["Shangji_zhuangtai"]=$list22[5]["siteset_content"];
			$tiaojian6["Shangji_localwg"]=$Sitelist[$i]["Sitesdzc_name"];
			$model6=$Shangji->where($tiaojian6)->count();     //状态6数据
            $newArr6[$i] = $model6;
		}
		$this->assign('newArr6',$newArr6);
		$this->display();
	} 

	public function shangjiset_action(){				//商机设置保存方法
		parent::YanZheng('loginemails','__APP__/Index/index');  //验证非法用户进出页面	
		$Person=new PersonModel();
		$condition1[$Person->_id]=session('loginemails');            //判定数据中心条件
		$list1=$Person->where($condition1)->find();
		$Siteset=new SitesetModel();

			$sjyema=trim($_POST['sjyema']); 
			$sjzhuangtai0=trim($_POST['sjzhuangtai0']); 
			$sjzhuangtai1=trim($_POST['sjzhuangtai1']); 
			$sjzhuangtai2=trim($_POST['sjzhuangtai2']); 
			$sjzhuangtai3=trim($_POST['sjzhuangtai3']); 
			$sjzhuangtai4=trim($_POST['sjzhuangtai4']); 
			$sjzhuangtai5=trim($_POST['sjzhuangtai5']); 
				
			$data["siteset_content"]=$sjyema;
			$condition["siteset_personid"]=session('loginemails'); 
			$condition["siteset_kind"]=1; 
			// 写入用户数据到数据库if($result !== false)
			$results = $Siteset->where($condition)->save($data);
			$chaxun = $Siteset->where($condition)->find();
			if($chaxun["siteset_content"]!=$sjyema){
				echo "页码保存错误，请联系管理员！";
				exit;
			}
		//	dump($sjzhuangtai0);exit;

		if($sjzhuangtai0 == null and $sjzhuangtai1 == null and $sjzhuangtai2 == null and $sjzhuangtai3 == null and $sjzhuangtai4 == null and $sjzhuangtai5 == null){
			echo 1;
		}else{
			$tiaojian["siteset_personid"]=session('loginemails');     //查询类别名称作为下面保存条件
			$tiaojian["siteset_kind"]=2; 
			$reslist=$Siteset->where($tiaojian)->select();
		
			if($sjzhuangtai0==null){
				$data0["siteset_zhuangtai"]=0;
			}else{
				$data0["siteset_zhuangtai"]=1;
			}			
			$tiaojian0["siteset_personid"]=session('loginemails'); 
			$tiaojian0["siteset_kind"]=2; 
			$tiaojian0["siteset_content"]=$reslist[0]["siteset_content"];
			$results0 = $Siteset->where($tiaojian0)->save($data0);

			if($sjzhuangtai1==null){
				$data1["siteset_zhuangtai"]=0;
			}else{
				$data1["siteset_zhuangtai"]=1;
			}			
			$tiaojian1["siteset_personid"]=session('loginemails'); 
			$tiaojian1["siteset_kind"]=2; 
			$tiaojian1["siteset_content"]=$reslist[1]["siteset_content"];
			$results1 = $Siteset->where($tiaojian1)->save($data1);

			
			if($sjzhuangtai2==null){
				$data2["siteset_zhuangtai"]=0;
			}else{
				$data2["siteset_zhuangtai"]=1;
			}			
			$tiaojian2["siteset_personid"]=session('loginemails'); 
			$tiaojian2["siteset_kind"]=2; 
			$tiaojian2["siteset_content"]=$reslist[2]["siteset_content"];
			$results2 = $Siteset->where($tiaojian2)->save($data2);

			
			if($sjzhuangtai3==null){
				$data3["siteset_zhuangtai"]=0;
			}else{
				$data3["siteset_zhuangtai"]=1;
			}			
			$tiaojian3["siteset_personid"]=session('loginemails'); 
			$tiaojian3["siteset_kind"]=2; 
			$tiaojian3["siteset_content"]=$reslist[3]["siteset_content"];
			$results3 = $Siteset->where($tiaojian3)->save($data3);

			
			if($sjzhuangtai4==null){
				$data4["siteset_zhuangtai"]=0;
			}else{
				$data4["siteset_zhuangtai"]=1;
			}			
			$tiaojian4["siteset_personid"]=session('loginemails'); 
			$tiaojian4["siteset_kind"]=2; 
			$tiaojian4["siteset_content"]=$reslist[4]["siteset_content"];
			$results4 = $Siteset->where($tiaojian4)->save($data4);

			
			if($sjzhuangtai5==null){
				$data5["siteset_zhuangtai"]=0;
			}else{
				$data5["siteset_zhuangtai"]=1;
			}			
			$tiaojian5["siteset_personid"]=session('loginemails'); 
			$tiaojian5["siteset_kind"]=2; 
			$tiaojian5["siteset_content"]=$reslist[5]["siteset_content"];
			$results5 = $Siteset->where($tiaojian5)->save($data5);
			if($results0!=false or $results1!=false or $results2!=false or $results3!=false or $results4!=false or $results5!=false){
				echo 1;
			}elseif($results0==0 or $results1==0 or $results2==0 or $results3==0 or $results4==0 or $results5==0){
				echo 1;
			}else{
				echo "商机列表状态保存错误，请联系管理员！";
				exit;
			}
		}
    }


    public function shangji(){				//商机页面显示
		parent::YanZheng('loginemails','__APP__/Index/index');
		$User=new PersonModel();
		$tempid=session('loginemails');
		$condition[$User->_id]=$tempid;
		$userlist=$User->where($condition)->find();
		$this->assign('loginusers',$userlist);
		
	//	parent::flushzhuangtai();
		
		if(session('sjpaixu')==1 or session('sjpaixu')==null){
			$paixu = "Shangji_id";
		}
		if(session('sjpaixu')==2){
			$paixu = "Shangji_lastchulidate";
		}
		if(session('sjpaixu')==3){
			$paixu = "Shangji_datetime";
		}
		$Shangji=new ShangjiModel();
		$tiaojian["Shangji_localqx"]=$userlist["Person_localxq"];
		if($userlist["Person_biaoshi"]!=1){  //   主任、员工只能看到自己网格的  $where['a'] = array(14,10,'or');
			$tiaojian["Shangji_localwg"]=$userlist["Person_localwg"];
		}
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
		
		import('ORG.Util.Page');
		$Shangjis=$Shangji->where($tiaojian)->count();
		$P= new Page($Shangjis,$list11['siteset_content']);

		if(session('sjpaixu')==3){ //过期时间顺序
			$list=$Shangji->where($tiaojian)->limit($P->firstRow.','.$P->listRows)->order($paixu.' asc')->select();
		}else{
			$list=$Shangji->where($tiaojian)->limit($P->firstRow.','.$P->listRows)->order($paixu.' desc')->select();
		}
		
		$Page=$P->show();

		foreach ($list as $k => $v) {
			$tempendtime = mb_substr($list[$k]['Shangji_datetime'], 0, 10);
			$tempdate=date('Y-m-d');
			 $list[$k]["shengyudays"]=(strtotime($tempendtime)-strtotime($tempdate))/3600/24+1;
		}

		$this->assign('list',$list);
		$this->assign('Page',$Page);
		$this->display();
    } 

	public function flushzhuangtai(){
		parent::YanZheng('loginemails','__APP__/Index/index');  //验证非法用户进出页面  
		$User=new PersonModel();
		$tempperson=session('loginemails');
		$condition[$User->_id]=$tempperson;
		$userlist=$User->where($condition)->find();	
				
		$Shangji=new ShangjiModel();
		$tiaojianupdate["Shangji_localqx"]=$userlist["Person_localxq"];
		if($userlist["Person_biaoshi"]!=1){  //   主任、员工只能看到自己网格的
			$tiaojianupdate["Shangji_localwg"]=$userlist["Person_localwg"];
		}			
		$pandinglists=$Shangji->where($tiaojianupdate)->select();
		$pandingcounts=$Shangji->where($tiaojianupdate)->count();                       //更新过期状态
		$pandingcount=$pandingcounts-1;
		$dangqiandate=date('Y-m-d',time());
		for($i=0;$i<=$pandingcount;$i++)
		{ 
			if($pandinglists[$i]['Shangji_zhuangtai']=="已报价" or $pandinglists[$i]['Shangji_zhuangtai']=="跟进中" or $pandinglists[$i]['Shangji_zhuangtai']=="初步洽谈"){
				if($pandinglists[$i]["Shangji_datetime"]<$dangqiandate){
					$data["Shangji_zhuangtai"]="已过期";
					$updatetiaojian["Shangji_id"]=$pandinglists[$i]['Shangji_id'];
					$Shangji->where($updatetiaojian)->save($data);
					
					$Shangjifankui=new ShangjifankuiModel(); 
					$datas["Shangjifankui_sjid"]=$pandinglists[$i]['Shangji_id'];
					$datas["Shangjifankui_content"]="在".$pandinglists[$i]["Shangji_datetime"]."内未落地，已过期。";
					$datas["Shangjifankui_person"]="system"; 
					$datas["Shangjifankui_date"]=date('Y-m-d H:i',time());  
					// 写入用户数据到数据库
					$Shangjifankui->add($datas);
					
				}
			}
		}
		parent::PageRedirect('__APP__/Shangji/shangji');
	}
	
	public function searchshangji(){				//商机搜索
		parent::YanZheng('loginemails','__APP__/Index/index');
		$User=new PersonModel();//dump($User);exit;
		$tempid=session('loginemails');
		$condition[$User->_id]=$tempid;
		$userlist=$User->where($condition)->find();
		$this->assign('loginusers',$userlist);
		if(session('sjpaixu')==1 or session('sjpaixu')==null){
			$paixu = "Shangji_date";
		}
		if(session('sjpaixu')==2){
			$paixu = "Shangji_lastchulidate";
		}
		if(session('sjpaixu')==3){
			$paixu = "Shangji_datetime";
		}
		
		$Shangji=new ShangjiModel();
		$temptype=$_GET["kind"];
		$tempkeywords=$_GET["guanjianzi"];
	//	dump($_GET["id"]);exit;  titleid=a&kind=Shangji_product&guanjianzi={$vo.Sitecphy_name}
		$temptselecttime=$_GET['selecttime'];
		$tempstartdate=$_GET['startdate'];
		$tempjieshudate=$_GET['jieshudate'];
		$where="$temptselecttime > '$tempstartdate' and $temptselecttime <= '$tempjieshudate'";
	if($_GET["titleid"]==null){
		$tiaojian['_complex'] = $where;
	}
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
		
		
		if($temptype!=null){
			$tiaojian[$temptype]=array('like','%'.$tempkeywords.'%');
		}
		$tiaojian[$Shangji->_localqx]=$userlist["Person_localxq"];
		
		if($userlist["Person_biaoshi"]!=1){  //   主任、员工只能看到自己网格的
			$tiaojian["Shangji_localwg"]=$userlist["Person_localwg"];
		}
		
		$s=$Shangji->where($tiaojian)->select();
		if($s){
			import('ORG.Util.Page');
			$count=$Shangji->where($tiaojian)->count();
			$P= new Page($count,$list11['siteset_content']);
			foreach($tiaojian as $key=>$val) {
				$Page->parameter   .=   "$key=".urlencode($val).”&”;//分页跳转的时候保证查询条件
			}
			if(session('sjpaixu')==3){
				$list=$Shangji->where($tiaojian)->limit($P->firstRow.','.$P->listRows)->order($paixu.' asc')->select();
			}else{
				$list=$Shangji->where($tiaojian)->limit($P->firstRow.','.$P->listRows)->order($paixu.' desc')->select();
			}
			
			$Page=$P->show();
			
			foreach ($list as $a => $b) {
				$tempendtime = mb_substr($list[$a]['Shangji_datetime'], 0, 10);
				$tempdate=date('Y-m-d');
				$list[$a]["shengyudays"]=(strtotime($tempendtime)-strtotime($tempdate))/3600/24+1;
			}
			
			$this->assign('list',$list);
			$this->assign('Page',$Page);
			$this->assign('count',$count);
			$this->assign('temptselecttime',$temptselecttime);
			$this->assign('tempstartdate',$tempstartdate);
			$this->assign('tempjieshudate',$tempjieshudate);
			$this->assign('temptype',$temptype);
			$this->assign('tempkeywords',$tempkeywords);
			$this->display();
		}else{
			$this->assign('count',$count);
			$this->assign('temptselecttime',$temptselecttime);
			$this->assign('tempstartdate',$tempstartdate);
			$this->assign('tempjieshudate',$tempjieshudate);
			$this->assign('temptype',$temptype);
			$this->assign('tempkeywords',$tempkeywords);
			$this->display();
		}

	}
    public function setpaixu(){				//设置排序
		parent::YanZheng('loginemails','__APP__/Index/index');
		$paixuid=$_POST['setid'];
		session('sjpaixu',null);	  //清除之前值	
		session('sjpaixu', $paixuid);
		parent::PageRedirect('__APP__/Shangji/shangji');
		$this->display();
    } 

    public function shangjifankui(){				//商机反馈页面显示
		parent::YanZheng('loginemails','__APP__/Index/index');	
		$User=new PersonModel();
		$tempid=session('loginemails');
		$conditions[$User->_id]=$tempid;
		$userlist=$User->where($conditions)->find();
		$this->assign('loginusers',$userlist);
		
		$Shangji=new ShangjiModel();
		$condition["Shangji_id"]=$_GET['id'];
		$editlist=$Shangji->where($condition)->find();
		if($editlist){
			$this->assign('editlist',$editlist);
			
			$Shangjifankui=new ShangjifankuiModel();
			$tiaojian["Shangjifankui_sjid"]=$editlist["Shangji_id"];
			import('ORG.Util.Page');
			$Shangjifankuis=$Shangjifankui->where($tiaojian)->count();
			$P= new Page($Shangjifankuis,3);
			$list=$Shangjifankui->where($tiaojian)->limit($P->firstRow.','.$P->listRows)->order('Shangjifankui_id desc')->select();
			$Page=$P->show();
			$this->assign('list',$list);
			$this->assign('Page',$Page);
			
			$this->display();
		}else{
			echo "<script language=\"JavaScript\">\r\n";
			echo " alert(\"参数错误！或 项目不存在。\");\r\n";
			echo " window.close();\r\n";
			echo "</script>";
			exit;
		}	
    } 
	public function del_shangjifankui(){                            //商机反馈删除功能
		parent::YanZheng('loginemails','__APP__/Index/index');  //验证非法用户进出页
		$Shangjifankui=new ShangjifankuiModel();
		$fankuicondition["Shangjifankui_id"]=$_GET['id'];
		$del=$Shangjifankui->where($fankuicondition)->delete();    
		if($del){
			$code = 1;
			echo  $code;	
		}else{
			$code = 0;
			echo  $code;
			exit;
		} 
	} 
	public function add_shangjifankui_action(){				//商机反馈添加方法
		parent::YanZheng('loginemails','__APP__/Index/index');  //验证非法用户进出页面	Shangji_id Shangjifankui_content  zhuangtai baojiadan
		$User=new PersonModel();//dump($User);exit;
		$tempid=session('loginemails');
		$condition[$User->_id]=$tempid;
		$userlist=$User->where($condition)->find();
		$Shangji=new ShangjiModel(); 
		$Shangjifankui=new ShangjifankuiModel();
		$sjcontion["Shangji_id"]=trim($_POST['Shangji_id']);
		
		//dump(trim($_POST['qianyuedate']));exit;
		$sjlist=$Shangji->where($sjcontion)->find();
		
		
		if($sjlist["Shangji_zhuangtai"]!="商机丢失"  or $sjlist["Shangji_zhuangtai"]!="已签约"){                                 //商机等于过期或
			$shangji_datetime=date("Y-m-d H:i:s",mktime(date("h"),date("i"),date("s"),date("m")+3,date("d"),date("Y")));
			$dataw["Shangji_datetime"]=$shangji_datetime;
		}
		
		
		if($_POST['zhuangtai'] == "已报价" or $_POST['zhuangtai'] == "跟进中" or $_POST['zhuangtai'] == "已签约"){
			
			if($sjlist["Shangji_projectyusuan"]==null or $sjlist["Shangji_projectcanyu"]==null){
				echo "商机状态为：已报价、跟进中、已签约时，预算和参与金额不能为空，请编辑该商机！";
				exit;	
			}
		
		}
		if($_POST['zhuangtai'] == "初步洽谈" or $_POST['zhuangtai'] == "商机丢失" or $_POST['zhuangtai'] == "已报价" or $_POST['zhuangtai'] == "跟进中"){
			
			if($_POST['qianyuedate']!=null){
				echo "商机状态为：非“已签约”状态时，无需选择签约时间！";
				exit;	
			}
			if($_POST['qanyueway']!=null){
				echo "商机状态为：非“已签约”状态时，无需选择签约计费方式！";
				exit;	
			}
			if($_POST['qianyuejine']!=null){
				echo "商机状态为：非“已签约”状态时，无需填写签约金额！";
				exit;	
			}
		}
		if($_POST['zhuangtai'] == "已签约"){
			
				if($_POST['qianyuedate'] == null){
					echo "商机状态为：已签约时，签约时间不为空！";
					exit;	
				}
			if($_POST['qianyuedate']<$sjlist["Shangji_startdate"]){
				echo "签约时间不能小于商机获取时间（开始时间）！";
				exit;
			}	
				if($_POST['qanyueway'] == null){
					echo "商机状态为：已签约时，签约计费方式不为空！";
					exit;	
				}
				if($_POST['qianyuejine'] == null){
					echo "商机状态为：已签约时，签约金额不为空！";
					exit;	
				}

			$dataw["Shangji_qianyuedate"]=$_POST['qianyuedate'];
			$dataw["Shangji_qianyueway"]=$_POST['qanyueway'];
			$dataw["Shangji_qianyuejine"]=$_POST['qianyuejine'];
		}
				// import('ORG.Net.UploadFile');
				// $upload = new UploadFile();		// 实例化上传类
									// echo $upload;
					// exit;
				// $upload->maxSize  = 50145728;		// 设置附件上传大小
				// $upload->allowExts  = array('xlsx', 'docx', 'doc', 'pdf', 'xls');		// 设置附件上传类型
				// $upload->saveRule = $sjlist['Shangji_companyname'].date('YmdHms');//将上传文件名称 
				// $upload->uploadReplace = false;		// 存在同名文件是否是覆盖
				// $upload->savePath = './Uploads/baojiadan/'.$sjlist['Shangji_companyname'].'/'; // 设置附件上传目录 
				// $upload->subType = date; // 设置子目录命名类型 
				// $upload->autoSub = true; // 设置是否使用子目录 
				// $upload->dateFormat = date('Ymd'); // 设置子目录命令规则  savename
				// if (!$upload->upload()) {
					// echo "上传格式不对！xlsx/xls/docx/doc/pdf";
					// exit;
					// $this->error($upload->getErrorMsg());
				// } else {
					 // $info = $upload->getUploadFileInfo();
				// }
				// $url=$info[0]['savename'];

			$data["Shangjifankui_sjid"]=trim($_POST['Shangji_id']); 
			$data["Shangjifankui_content"]="状态:[".$_POST['zhuangtai']."]&nbsp;跟踪详情：".trim($_POST['Shangjifankui_content']);
			$data["Shangjifankui_person"]=$userlist['Person_name']; 
			$data["Shangjifankui_date"]=date('Y-m-d H:i',time());  
			$data["Shangjifankui_url"]=$url; 

			$dataw["Shangji_lastchulidate"]=date('Y-m-d H:i',time());
			$dataw["Shangji_lastbeizhu"]=trim($_POST['Shangjifankui_content']);
			$dataw["Shangji_zhuangtai"]=$_POST['zhuangtai'];
			$Shangji->where($sjcontion)->save($dataw);
			// 写入用户数据到数据库
			if($Shangjifankui->add($data)){
				echo 1;
			}
			else{
				echo "添加失败，联系管理员！";
				exit;
			}
    }
	public function getSection(){              //一级联下拉
		parent::YanZheng('loginemails','__APP__/Index/index');
		$User=new PersonModel();//dump($User);exit;
		$tempid=session('loginemails');
		$condition[$User->_id]=$tempid;
		$userlist=$User->where($condition)->find();
		
	   $s= M("Person");
	   if($userlist['Person_biaoshi']==1){
		   $tiaojian['Person_biaoshi']=2;
		   $tiaojian['Person_localxq']=$userlist["Person_localxq"];
	   }
	   if($userlist['Person_biaoshi']==2 or $userlist['Person_biaoshi']==0){
		   $tiaojian['Person_biaoshi']=2;
		   $tiaojian['Person_localwg']=$userlist['Person_localwg'];
		   $tiaojian['Person_localxq']=$userlist["Person_localxq"];
	   }
	   $list = $s->field('Person_localwg,Person_name')->where($tiaojian)->select();
	   echo json_encode($list);
	 }
  
	public function getCatid(){	              //二级联下拉
		parent::YanZheng('loginemails','__APP__/Index/index');
		$User=new PersonModel();
		$condition[$User->_name]=$_GET['id'];
		$userlist=$User->where($condition)->find();
		$c= M("Person");
		$tiaojian['Person_localwg']=$userlist["Person_localwg"];
		$data=$c->field('Person_localwg,Person_name')->where($tiaojian)->select();	   
		echo json_encode($data);
	}
	public function getCatidcust(){	              //二级联下拉，客户经理
		parent::YanZheng('loginemails','__APP__/Index/index');
		$User=new PersonModel();
		$condition[$User->_name]=$_GET['id'];
		$userlist=$User->where($condition)->find();
		$c= M("Person");
		$tiaojian['Person_localwg']=$userlist["Person_localwg"];
		$data=$c->field('Person_localwg,Person_name')->where($tiaojian)->select();	   
		echo json_encode($data);
	}   
	public function add_shangji(){				//商机新增
		parent::YanZheng('loginemails','__APP__/Index/index');
		$User=new PersonModel();//dump($User);exit;
		$tempid=session('loginemails');
		$condition[$User->_id]=$tempid;
		$userlist=$User->where($condition)->find();
		$this->assign('loginusers',$userlist);
		
		$Shangji = new ShangjiModel();
		$tiaojian["Shangji_localqx"]=$userlist["Person_localxq"];
		$kehualllist=$Shangji->where($tiaojian)->order('Shangji_id')->group('Shangji_companyname')->select();    //下拉菜单
		$this->assign('kehualllist',$kehualllist);
		$custerlist=$Shangji->order('Shangji_id')->group('Shangji_custmanager')->select();    //下拉菜单
		$this->assign('custerlist',$custerlist);
		
	    $Sitesdzc=new SitesdzcModel();//属地、职称
		$Sitecphy=new SitecphyModel();// 产品、行业
		
		$hangyecontion[$Sitecphy->_panduan]=2;
		$hangyelist=$Sitecphy->where($hangyecontion)->select();
		$this->assign('hangyelist',$hangyelist);   //行业
		$chanpincontion[$Sitecphy->_panduan]=1;
		$chanpinlist=$Sitecphy->where($chanpincontion)->order('Sitecphy_id')->select();
		$this->assign('chanpinlist',$chanpinlist);   //产品
		$laiyuancontion[$Sitecphy->_panduan]=3;
		$laiyuanlist=$Sitecphy->where($laiyuancontion)->order('Sitecphy_id')->select();
		$this->assign('laiyuanlist',$laiyuanlist);   //来源	
		
		$wanggecontion[$Sitesdzc->_panduan]=2;
		$wanggecontion[$Sitesdzc->_nameid]=$userlist["Person_localxq"];
		$wanggelist=$Sitesdzc->where($wanggecontion)->select();
		$zhichenglist=$Sitesdzc->where($zhichengcontion)->select();
		$this->assign('wanggelist',$wanggelist);   //属地
		
		$shangji_datetime=date("Y-m-d H:i:s",mktime(date("h"),date("i"),date("s"),date("m")+3,date("d"),date("Y")));
		$this->assign('shangji_datetime',$shangji_datetime);   
		
		$this->assign('shangji_dangqiantime',date("Y-m-d H:i:s"));  
		
		
		$tiaojianlist["Person_localxq"]=$userlist["Person_localxq"];
		$tiaojianlist["Person_biaoshi"]=1;
		$lists=$User->where($tiaojianlist)->select();   //管理员列表
		$this->assign('lists',$lists);
		
		
		$this->display();
	}
    public function execlput(){				//设置排序
		parent::YanZheng('loginemails','__APP__/Index/index');
		$User=new PersonModel();//dump($User);exit;
		$tempid=session('loginemails');
		$condition[$User->_id]=$tempid;
		$userlist=$User->where($condition)->find();
		$this->assign('loginusers',$userlist);

	    $Sitesdzc=new SitesdzcModel();//属地、职称
		
		$wanggecontion[$Sitesdzc->_panduan]=2;
		$wanggecontion[$Sitesdzc->_nameid]=$userlist["Person_localxq"];
		$wanggelist=$Sitesdzc->where($wanggecontion)->select();
		$zhichenglist=$Sitesdzc->where($zhichengcontion)->select();
		$this->assign('wanggelist',$wanggelist);   //属地  
		
		$this->assign('year01',date('Y'));
		$this->assign('year02',date('Y')-1);
		$this->assign('year03',date('Y')-2);
		$this->assign('year04',date('Y')-3);
		$this->display();
    } 
	public function add_shangji_action(){				//商机添加方法
		parent::YanZheng('loginemails','__APP__/Index/index');  //验证非法用户进出页面	 
		$User=new PersonModel();//dump($User);exit;
		$tempid=session('loginemails');
		$condition[$User->_id]=$tempid;
		$userlist=$User->where($condition)->find();
		$Shangji=new ShangjiModel(); 
		
		$custadmincont[$User->_name]=trim($_POST['Shangji_custmanageradmin']);    //查找自动填充区县、网格和网格ID  
		$custadmin=$User->where($custadmincont)->find();
		$Sitesdzc=new SitesdzcModel();
		$wanggecontion[$Sitesdzc->_name]=$custadmin['Person_localwg'];
		$wanggecontion[$Sitesdzc->_nameid]=$custadmin['Person_localxq'];
		$wanggelist=$Sitesdzc->where($wanggecontion)->find();
		
		if(trim($_POST['Shangji_datetime']) < date('Y-m-d H:i',time())){
			echo "过期时间不能小于当前时间！";
			exit;
		}
		if(trim($_POST['Shangji_startdate']) > date('Y-m-d H:i',time())){
			echo "开始时间不能大于当前时间！";
			exit;
		}
		if(trim($_POST['Shangji_zhuangtai']) == "已报价" or trim($_POST['Shangji_zhuangtai']) == "跟进中" or trim($_POST['Shangji_zhuangtai']) == "已签约"){
			
			if(trim($_POST['Shangji_projectyusuan'])==null or trim($_POST['Shangji_projectcanyu'])==null){
				echo "商机状态为：已报价、跟进中、已签约时，预算和参与金额不能为空！";
				exit;	
			}
		
		}
		
			$data["Shangji_companyname"]=trim($_POST['Shangji_companyname']); 
			$data["Shangji_custname"]=trim($_POST['Shangji_custname']);
			$data["Shangji_custphone"]=trim($_POST['Shangji_custphone']);  
			$data["Shangji_companylocal"]=trim($_POST['Shangji_companylocal']);    
			$data["Shangji_projectname"]=trim($_POST['Shangji_projectname']); 
 			$data["Shangji_product"]=trim($_POST['product']); 
			$data["Shangji_custhangye"]=trim($_POST['Shangji_custhangye']);
			$data["Shangji_level"]=trim($_POST['Shangji_level']); 
			
			$data["Shangji_laiyuan"]=trim($_POST['Shangji_laiyuan']);
			$data["Shangji_projectyusuan"]=trim($_POST['Shangji_projectyusuan']); 	
			$data["Shangji_projectcanyu"]=trim($_POST['Shangji_projectcanyu']);
			$data["Shangji_projectzhaobiao"]=trim($_POST['Shangji_projectzhaobiao']); 
			$data["Shangji_startdate"]=trim($_POST['Shangji_startdate']);
			$data["Shangji_custmanager"]=trim($_POST['Shangji_custmanager']);
			$data["Shangji_custmanageradmin"]=trim($_POST['Shangji_custmanageradmin']);		
			$data["Shangji_localqx"]=$custadmin['Person_localxq'];
			$data["Shangji_localwg"]=$custadmin['Person_localwg'];

			$data["Shangji_lastbeizhu"]=trim($_POST['Shangji_beizhu']);
			$data["Shangji_lastchulidate"]=date('Y-m-d H:i',time());
			
			$data["Shangji_zhuangtai"]=trim($_POST['Shangji_zhuangtai']);
			$data["Shangji_beizhu"]=trim($_POST['Shangji_beizhu']);

			$data["Shangji_person"]=$userlist['Person_name'];
			$data["Shangji_date"]=date('Y-m-d H:i',time());
			$data["Shangji_localwgid"]=$wanggelist["Sitesdzc_qxwgid"];
			$data["Shangji_datetime"]=trim($_POST['Shangji_datetime']);	
			$data["Shangji_adminperson"]=trim($_POST['Shangji_admin']);	
		//	dump($data);exit;
			// 写入用户数据到数据库
			if($Shangji->add($data)){
				echo 1;
			}
			else{
				echo "添加失败，联系管理员！";
				exit;
			}
    }
	
	public function edt_shangji(){				//商机编辑
		parent::YanZheng('loginemails','__APP__/Index/index');  //验证非法用户进出页面	
		$Person=new PersonModel();
		$tempid=session('loginemails');
		$panduantiaojian[$Person->_id]=$tempid;
		$userlist=$Person->where($panduantiaojian)->find();
		$this->assign('loginusers',$userlist);
		$Shangji=new ShangjiModel(); 
		$condition["Shangji_id"]=$_GET['id'];
		$editlist=$Shangji->where($condition)->find();
		if($editlist){
			$this->assign('editlist',$editlist);	

			$Shangji = new ShangjiModel();
			$tiaojian["Shangji_localqx"]=$userlist["Person_localxq"];
			$kehualllist=$Shangji->where($tiaojian)->order('Shangji_id')->group('Shangji_companyname')->select();    //下拉菜单
			$this->assign('kehualllist',$kehualllist);
			
			$Sitesdzc=new SitesdzcModel();//属地、职称
			$Sitecphy=new SitecphyModel();// 产品、行业
			
			$hangyecontion[$Sitecphy->_panduan]=2;
			$hangyelist=$Sitecphy->where($hangyecontion)->select();
			$this->assign('hangyelist',$hangyelist);   //行业
			$chanpincontion[$Sitecphy->_panduan]=1;
			$chanpinlist=$Sitecphy->where($chanpincontion)->order('Sitecphy_id')->select();
			$this->assign('chanpinlist',$chanpinlist);   //产品
			
			$custerlist=$Shangji->order('Shangji_id')->group('Shangji_custmanager')->select();    //下拉菜单
			$this->assign('custerlist',$custerlist);
			
			$laiyuancontion[$Sitecphy->_panduan]=3;
			$laiyuanlist=$Sitecphy->where($laiyuancontion)->order('Sitecphy_id')->select();
			$this->assign('laiyuanlist',$laiyuanlist);   //产品
			
			$wanggecontion[$Sitesdzc->_panduan]=2;
			$wanggecontion[$Sitesdzc->_nameid]=$userlist["Person_localxq"];
			$wanggelist=$Sitesdzc->where($wanggecontion)->select();
			$zhichenglist=$Sitesdzc->where($zhichengcontion)->select();
			$this->assign('wanggelist',$wanggelist);   //属地

			$tiaojianlist["Person_localxq"]=$userlist["Person_localxq"];
			$tiaojianlist["Person_biaoshi"]=1;
			$lists=$Person->where($tiaojianlist)->select();   //管理员列表
			$this->assign('lists',$lists);
			
			$this->display();
		}else{
			echo 0;
			exit;
		}	
	}
	public function edt_shangji_action(){				//商机编辑方法
		parent::YanZheng('loginemails','__APP__/Index/index');  //验证非法用户进出页面	 
		$User=new PersonModel();//dump($User);exit;
		$tempid=session('loginemails');
		$condition[$User->_id]=$tempid;
		$userlist=$User->where($condition)->find();
		$Shangji=new ShangjiModel(); 
		
		$Sitesdzc=new SitesdzcModel();
		$tempeditid=trim($_POST['editid']);    //获取主键ID
		$savecondition["Shangji_id"]=$tempeditid;
		$wanggecontion[$Sitesdzc->_name]=trim($_POST['Shangji_localwg']);
		$wanggecontion[$Sitesdzc->_nameid]=trim($_POST['Shangji_localqx']);
		$wanggelist=$Sitesdzc->where($wanggecontion)->find();
		
		if(trim($_POST['Shangji_datetime']) < date('Y-m-d H:i',time())){
			echo "过期时间不能小于当前时间！";
			exit;
		}
		if(trim($_POST['Shangji_startdate']) > date('Y-m-d H:i',time())){
			echo "开始时间不能大于当前时间！";
			exit;
		}
		if(trim($_POST['Shangji_zhuangtai']) == "已报价" or trim($_POST['Shangji_zhuangtai']) == "跟进中" or trim($_POST['Shangji_zhuangtai']) == "已签约"){
			
			if(trim($_POST['Shangji_projectyusuan'])==null or trim($_POST['Shangji_projectcanyu'])==null){
				echo "商机状态为：已报价、跟进中、已签约时，预算和参与金额不能为空！";
				exit;	
			}
		
		}
		
			$data["Shangji_companyname"]=trim($_POST['Shangji_companyname']); 
			$data["Shangji_custname"]=trim($_POST['Shangji_custname']);
			$data["Shangji_custphone"]=trim($_POST['Shangji_custphone']);  
			$data["Shangji_companylocal"]=trim($_POST['Shangji_companylocal']);    
			$data["Shangji_projectname"]=trim($_POST['Shangji_projectname']); 
			if(trim($_POST['product'])!=null){
				$data["Shangji_product"]=trim($_POST['product']); 
			}
			$data["Shangji_custhangye"]=trim($_POST['Shangji_custhangye']);
			$data["Shangji_level"]=trim($_POST['Shangji_level']); 
			$data["Shangji_laiyuan"]=trim($_POST['Shangji_laiyuan']); 
			$data["Shangji_projectyusuan"]=trim($_POST['Shangji_projectyusuan']); 	
			$data["Shangji_projectcanyu"]=trim($_POST['Shangji_projectcanyu']);
			$data["Shangji_projectzhaobiao"]=trim($_POST['Shangji_projectzhaobiao']); 
			$data["Shangji_startdate"]=trim($_POST['Shangji_startdate']);
			if(trim($_POST['Shangji_custmanageradmin'])!=null){
				$data["Shangji_custmanager"]=trim($_POST['Shangji_custmanager']);
				$data["Shangji_custmanageradmin"]=trim($_POST['Shangji_custmanageradmin']);
			}
			$data["Shangji_localqx"]=trim($_POST['Shangji_localqx']);
			$data["Shangji_localwg"]=trim($_POST['Shangji_localwg']);
			$data["Shangji_zhuangtai"]=trim($_POST['Shangji_zhuangtai']);
			$data["Shangji_beizhu"]=trim($_POST['Shangji_beizhu']);
			$data["Shangji_adminperson"]=trim($_POST['Shangji_admin']);
			$data["Shangji_datetime"]=trim($_POST['Shangji_datetime']);	
			
			$neirong=$userlist['Person_name'].'['.date('Y-m-d H:i',time()).']'.'编辑该商机。';
			$data["Shangji_localwgid"]=$wanggelist["Sitesdzc_qxwgid"];
			// 写入用户数据到数据库		
			if($Shangji->where($savecondition)->save($data)){
				 //记录日志
				$Shangjifankui=new ShangjifankuiModel(); 
				$datas["Shangjifankui_sjid"]=$tempeditid;
				$datas["Shangjifankui_content"]=$neirong;
				$datas["Shangjifankui_person"]="system"; 
				$datas["Shangjifankui_date"]=date('Y-m-d H:i',time());  
				// 写入用户数据到数据库
				$Shangjifankui->add($datas);
				//dump($_POST['httpref']);exit;
				// $this->assign('httpref',$_POST['httpref']); 
				// echo $_POST['httpref'];
				echo 1;
				  //属地
			}
			else{
				echo "保存失败，联系管理员！";
				exit;
			}
    }
	
	public function del_shangji(){                            //删除功能
		parent::YanZheng('loginemails','__APP__/Index/index');  //验证非法用户进出页
		$Shangjifankui=new ShangjifankuiModel();
		$fankuicondition["Shangjifankui_sjid"]=$_GET['id'];
		$Shangjifankui->where($fankuicondition)->delete();   //删除商机反馈  
		$Shangji=new ShangjiModel();
		$condition["Shangji_id"]=$_GET['id'];
		$del=$Shangji->where($condition)->delete();
		if($del){
			$code = 1;
			echo  $code;	
		}else{
			$code = 0;
			echo  $code;
			exit;
		} 
	} 
	public function exportExcel($expTitle,$expCellName,$expTableData){
		parent::YanZheng('loginemails','__APP__/Index/index');  //验证非法用户进出页面	 
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = 商机表_.date('Ymd');//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);
        vendor("PHPExcel.PHPExcel");
        $objPHPExcel = new PHPExcel();
        $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ'); 
        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格 
        for($i=0;$i<$cellNum;$i++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]); 
        } 
        for($i=0;$i<$dataNum;$i++){
          for($j=0;$j<$cellNum;$j++){
            $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
          }             
        }  
		ob_clean();
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
        $objWriter->save('php://output'); 
        exit;   
    }
	
    function execlputaction(){//导出Excel
		parent::YanZheng('loginemails','__APP__/Index/index');  //验证非法用户进出页面	 

		$Person=new PersonModel();
		$biaoshi=$_GET['biaoshi'];
		$condition1[$Person->_id]=session('loginemails');            //判定数据中心条件
		$list1=$Person->where($condition1)->find();
		$xlsModel = new ShangjiModel(); 
		if($biaoshi==1){                                                           //全部导出
			$xlsName  = date('Ymd');
			$xlsCell  = array(
				array('Shangji_localqx','区县'),
				array('Shangji_localwgid','网格代码'),
				array('Shangji_localwg','网格'),
				array('Shangji_product','涉及的产品'),
				array('Shangji_level','商机级别'), 
				array('Shangji_custhangye','行业'),
				array('Shangji_projectname','项目名称'),
				array('Shangji_companyname','客户名称'),
				array('Shangji_projectyusuan','项目预算(万）'),
				array('Shangji_projectcanyu','预计可参与金额'),
				array('Shangji_projectzhaobiao','预计招标时间'),
				array('Shangji_custmanager','客户经理'),
				array('Shangji_custmanageradmin','责任人'),
				array('Shangji_startdate','开始时间'),
				array('Shangji_lastchulidate','最后处理时间'),
				array('Shangji_zhuangtai','状态'),
				array('Shangji_lastbeizhu','阶段进展'),
				array('Shangji_person','创建人'),
				array('Shangji_date','录入时间'),
				array('Shangji_beizhu','备注'),
				array('Shangji_qianyuedate','签约时间'),
				array('Shangji_qianyueway','签约计费方式'),
				array('Shangji_qianyuejine','签约金额'),
				array('Shangji_adminperson','商机对接人'),
				array('Shangji_laiyuan','商机来源')
			);
			$condition["Shangji_localqx"]=$list1["Person_localxq"];   //全部导出
			if($list1["Person_biaoshi"]!=1){  //   主任、员工只能看到自己网格的  
				$condition["Shangji_localwg"]=$list1["Person_localwg"];
			}
			
		}else if($biaoshi==2){                                                           //商机首页导出
			$a1 = substr($_GET['Shangji_id'],0,strrpos($_GET['Shangji_id'],"/"));
			$a2=substr($_GET['Shangji_id'],strripos($_GET['Shangji_id'],"/")+1);
			$b1 = substr($_GET['Shangji_localqx'],0,strrpos($_GET['Shangji_localqx'],"/"));
			$b2=substr($_GET['Shangji_localqx'],strripos($_GET['Shangji_localqx'],"/")+1);
			$c1 = substr($_GET['Shangji_localwg'],0,strrpos($_GET['Shangji_localwg'],"/"));
			$c2=substr($_GET['Shangji_localwg'],strripos($_GET['Shangji_localwg'],"/")+1);
			$d1 = substr($_GET['Shangji_localwgid'],0,strrpos($_GET['Shangji_localwgid'],"/"));
			$d2=substr($_GET['Shangji_localwgid'],strripos($_GET['Shangji_localwgid'],"/")+1);
			$e1 = substr($_GET['Shangji_companyname'],0,strrpos($_GET['Shangji_companyname'],"/"));
			$e2=substr($_GET['Shangji_companyname'],strripos($_GET['Shangji_companyname'],"/")+1);
			$f1 = substr($_GET['Shangji_custname'],0,strrpos($_GET['Shangji_custname'],"/"));
			$f2=substr($_GET['Shangji_custname'],strripos($_GET['Shangji_custname'],"/")+1);
			$g1 = substr($_GET['Shangji_custphone'],0,strrpos($_GET['Shangji_custphone'],"/"));
			$g2=substr($_GET['Shangji_custphone'],strripos($_GET['Shangji_custphone'],"/")+1);
			$h1 = substr($_GET['Shangji_companylocal'],0,strrpos($_GET['Shangji_companylocal'],"/"));
			$h2=substr($_GET['Shangji_companylocal'],strripos($_GET['Shangji_companylocal'],"/")+1);
			$i1 = substr($_GET['Shangji_projectname'],0,strrpos($_GET['Shangji_projectname'],"/"));
			$i2=substr($_GET['Shangji_projectname'],strripos($_GET['Shangji_projectname'],"/")+1);
			$j1 = substr($_GET['Shangji_product'],0,strrpos($_GET['Shangji_product'],"/"));
			$j2=substr($_GET['Shangji_product'],strripos($_GET['Shangji_product'],"/")+1);
			$k1 = substr($_GET['Shangji_custhangye'],0,strrpos($_GET['Shangji_custhangye'],"/"));
			$k2=substr($_GET['Shangji_custhangye'],strripos($_GET['Shangji_custhangye'],"/")+1);
			$l1 = substr($_GET['Shangji_level'],0,strrpos($_GET['Shangji_level'],"/"));
			$l2=substr($_GET['Shangji_level'],strripos($_GET['Shangji_level'],"/")+1);
			$m1 = substr($_GET['Shangji_projectyusuan'],0,strrpos($_GET['Shangji_projectyusuan'],"/"));
			$m2=substr($_GET['Shangji_projectyusuan'],strripos($_GET['Shangji_projectyusuan'],"/")+1);
			$n1 = substr($_GET['Shangji_projectcanyu'],0,strrpos($_GET['Shangji_projectcanyu'],"/"));
			$n2=substr($_GET['Shangji_projectcanyu'],strripos($_GET['Shangji_projectcanyu'],"/")+1);
			$o1 = substr($_GET['Shangji_projectzhaobiao'],0,strrpos($_GET['Shangji_projectzhaobiao'],"/"));
			$o2=substr($_GET['Shangji_projectzhaobiao'],strripos($_GET['Shangji_projectzhaobiao'],"/")+1);
			$p1 = substr($_GET['Shangji_custmanager'],0,strrpos($_GET['Shangji_custmanager'],"/"));
			$p2=substr($_GET['Shangji_custmanager'],strripos($_GET['Shangji_custmanager'],"/")+1);
			$q1 = substr($_GET['Shangji_custmanageradmin'],0,strrpos($_GET['Shangji_custmanageradmin'],"/"));
			$q2=substr($_GET['Shangji_custmanageradmin'],strripos($_GET['Shangji_custmanageradmin'],"/")+1);
			$r1 = substr($_GET['Shangji_startdate'],0,strrpos($_GET['Shangji_startdate'],"/"));
			$r2=substr($_GET['Shangji_startdate'],strripos($_GET['Shangji_startdate'],"/")+1);
			$s1 = substr($_GET['Shangji_zhuangtai'],0,strrpos($_GET['Shangji_zhuangtai'],"/"));
			$s2=substr($_GET['Shangji_zhuangtai'],strripos($_GET['Shangji_zhuangtai'],"/")+1);
			$t1 = substr($_GET['Shangji_lastchulidate'],0,strrpos($_GET['Shangji_lastchulidate'],"/"));
			$t2=substr($_GET['Shangji_lastchulidate'],strripos($_GET['Shangji_lastchulidate'],"/")+1);
			$u1 = substr($_GET['Shangji_lastbeizhu'],0,strrpos($_GET['Shangji_lastbeizhu'],"/"));
			$u2=substr($_GET['Shangji_lastbeizhu'],strripos($_GET['Shangji_lastbeizhu'],"/")+1);
			$v1 = substr($_GET['Shangji_person'],0,strrpos($_GET['Shangji_person'],"/"));
			$v2=substr($_GET['Shangji_person'],strripos($_GET['Shangji_person'],"/")+1);
			$w1 = substr($_GET['Shangji_date'],0,strrpos($_GET['Shangji_date'],"/"));
			$w2=substr($_GET['Shangji_date'],strripos($_GET['Shangji_date'],"/")+1);
			$x1 = substr($_GET['Shangji_beizhu'],0,strrpos($_GET['Shangji_beizhu'],"/"));
			$x2=substr($_GET['Shangji_beizhu'],strripos($_GET['Shangji_beizhu'],"/")+1);
			$y1 = substr($_GET['Shangji_datetime'],0,strrpos($_GET['Shangji_datetime'],"/"));
			$y2=substr($_GET['Shangji_datetime'],strripos($_GET['Shangji_datetime'],"/")+1);
			$z1 = substr($_GET['Shangji_qianyuedate'],0,strrpos($_GET['Shangji_qianyuedate'],"/"));
			$z2=substr($_GET['Shangji_qianyuedate'],strripos($_GET['Shangji_qianyuedate'],"/")+1);
			
			$aa1 = substr($_GET['Shangji_qianyueway'],0,strrpos($_GET['Shangji_qianyueway'],"/"));
			$aa2=substr($_GET['Shangji_qianyueway'],strripos($_GET['Shangji_qianyueway'],"/")+1);
			$ab1 = substr($_GET['Shangji_qianyuejine'],0,strrpos($_GET['Shangji_qianyuejine'],"/"));
			$ab2=substr($_GET['Shangji_qianyuejine'],strripos($_GET['Shangji_qianyuejine'],"/")+1);
			$ac1 = substr($_GET['Shangji_adminperson'],0,strrpos($_GET['Shangji_adminperson'],"/"));
			$ac2=substr($_GET['Shangji_adminperson'],strripos($_GET['Shangji_adminperson'],"/")+1);
			
			$ad1 = substr($_GET['Shangji_laiyuan'],0,strrpos($_GET['Shangji_laiyuan'],"/"));
			$ad2=substr($_GET['Shangji_laiyuan'],strripos($_GET['Shangji_laiyuan'],"/")+1);
			
			$tempwg=$_GET['shangjiwg'];
			$tempzhuantai=$_GET['shangjizhuangtai'];
			$temptype=$_GET['select2'];
			$tempstartdate=$_GET['startdate'];
			$tempjieshudate=$_GET['jieshudate'];
			if($tempjieshudate<$tempstartdate){
				parent::Windowalert('结束时间不能小于开始时间。');
				parent::BackHistoryPage();
				exit;
			}
			$where="$temptype > '$tempstartdate' and $temptype <= '$tempjieshudate'";     //时间导出
			$condition['_complex'] = $where;
			$condition["Shangji_localqx"]=$list1["Person_localxq"];	
			if($tempwg!=1){
				$condition["Shangji_localwg"]=$tempwg;
			}	
			if($tempzhuantai==2){
			//	$shuzu = array("已报价","跟进中");
			//	$condition['Shangji_zhuangtai']=array("in",$shuzu);		
				
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
				$condition['Shangji_zhuangtai']=array("in",$shuzu);
				
			}
			$xlsName  = date('Ymd');
			$xlsCells  = array(
				array_filter(array($a1,$a2)),
				array_filter(array($b1,$b2)),
				array_filter(array($c1,$c2)),
				array_filter(array($d1,$d2)),
				array_filter(array($e1,$e2)),
				array_filter(array($f1,$f2)),
				array_filter(array($g1,$g2)),
				array_filter(array($h1,$h2)),
				array_filter(array($i1,$i2)),
				array_filter(array($j1,$j2)),
				array_filter(array($k1,$k2)),
				array_filter(array($l1,$l2)),
				array_filter(array($m1,$m2)),
				array_filter(array($n1,$n2)),
				array_filter(array($o1,$o2)),
				array_filter(array($p1,$p2)),
				array_filter(array($q1,$q2)),
				array_filter(array($r1,$r2)),
				array_filter(array($s1,$s2)),
				array_filter(array($t1,$t2)),
				array_filter(array($u1,$u2)),
				array_filter(array($v1,$v2)),
				array_filter(array($w1,$w2)),
				array_filter(array($x1,$x2)),
				array_filter(array($y1,$y2)),
				array_filter(array($z1,$z2)),
				array_filter(array($aa1,$aa2)),
				array_filter(array($ab1,$ab2)),
				array_filter(array($ac1,$ac2)),
				array_filter(array($ad1,$ad2))
			);
			foreach ($xlsCells as $key=>$value)         //删除数组元素并重新排序, 防止导出有空格列
			{
			  if ($value[0] === null)
				unset($xlsCells[$key]);
			}
			$xlsCell =array_values($xlsCells);
		}else if($biaoshi==3){                                                           //商机搜索导出
			$a1 = substr($_GET['Shangji_id'],0,strrpos($_GET['Shangji_id'],"/"));
			$a2=substr($_GET['Shangji_id'],strripos($_GET['Shangji_id'],"/")+1);
			$b1 = substr($_GET['Shangji_localqx'],0,strrpos($_GET['Shangji_localqx'],"/"));
			$b2=substr($_GET['Shangji_localqx'],strripos($_GET['Shangji_localqx'],"/")+1);
			$c1 = substr($_GET['Shangji_localwg'],0,strrpos($_GET['Shangji_localwg'],"/"));
			$c2=substr($_GET['Shangji_localwg'],strripos($_GET['Shangji_localwg'],"/")+1);
			$d1 = substr($_GET['Shangji_localwgid'],0,strrpos($_GET['Shangji_localwgid'],"/"));
			$d2=substr($_GET['Shangji_localwgid'],strripos($_GET['Shangji_localwgid'],"/")+1);
			$e1 = substr($_GET['Shangji_companyname'],0,strrpos($_GET['Shangji_companyname'],"/"));
			$e2=substr($_GET['Shangji_companyname'],strripos($_GET['Shangji_companyname'],"/")+1);
			$f1 = substr($_GET['Shangji_custname'],0,strrpos($_GET['Shangji_custname'],"/"));
			$f2=substr($_GET['Shangji_custname'],strripos($_GET['Shangji_custname'],"/")+1);
			$g1 = substr($_GET['Shangji_custphone'],0,strrpos($_GET['Shangji_custphone'],"/"));
			$g2=substr($_GET['Shangji_custphone'],strripos($_GET['Shangji_custphone'],"/")+1);
			$h1 = substr($_GET['Shangji_companylocal'],0,strrpos($_GET['Shangji_companylocal'],"/"));
			$h2=substr($_GET['Shangji_companylocal'],strripos($_GET['Shangji_companylocal'],"/")+1);
			$i1 = substr($_GET['Shangji_projectname'],0,strrpos($_GET['Shangji_projectname'],"/"));
			$i2=substr($_GET['Shangji_projectname'],strripos($_GET['Shangji_projectname'],"/")+1);
			$j1 = substr($_GET['Shangji_product'],0,strrpos($_GET['Shangji_product'],"/"));
			$j2=substr($_GET['Shangji_product'],strripos($_GET['Shangji_product'],"/")+1);
			$k1 = substr($_GET['Shangji_custhangye'],0,strrpos($_GET['Shangji_custhangye'],"/"));
			$k2=substr($_GET['Shangji_custhangye'],strripos($_GET['Shangji_custhangye'],"/")+1);
			$l1 = substr($_GET['Shangji_level'],0,strrpos($_GET['Shangji_level'],"/"));
			$l2=substr($_GET['Shangji_level'],strripos($_GET['Shangji_level'],"/")+1);
			$m1 = substr($_GET['Shangji_projectyusuan'],0,strrpos($_GET['Shangji_projectyusuan'],"/"));
			$m2=substr($_GET['Shangji_projectyusuan'],strripos($_GET['Shangji_projectyusuan'],"/")+1);
			$n1 = substr($_GET['Shangji_projectcanyu'],0,strrpos($_GET['Shangji_projectcanyu'],"/"));
			$n2=substr($_GET['Shangji_projectcanyu'],strripos($_GET['Shangji_projectcanyu'],"/")+1);
			$o1 = substr($_GET['Shangji_projectzhaobiao'],0,strrpos($_GET['Shangji_projectzhaobiao'],"/"));
			$o2=substr($_GET['Shangji_projectzhaobiao'],strripos($_GET['Shangji_projectzhaobiao'],"/")+1);
			$p1 = substr($_GET['Shangji_custmanager'],0,strrpos($_GET['Shangji_custmanager'],"/"));
			$p2=substr($_GET['Shangji_custmanager'],strripos($_GET['Shangji_custmanager'],"/")+1);
			$q1 = substr($_GET['Shangji_custmanageradmin'],0,strrpos($_GET['Shangji_custmanageradmin'],"/"));
			$q2=substr($_GET['Shangji_custmanageradmin'],strripos($_GET['Shangji_custmanageradmin'],"/")+1);
			$r1 = substr($_GET['Shangji_startdate'],0,strrpos($_GET['Shangji_startdate'],"/"));
			$r2=substr($_GET['Shangji_startdate'],strripos($_GET['Shangji_startdate'],"/")+1);
			$s1 = substr($_GET['Shangji_zhuangtai'],0,strrpos($_GET['Shangji_zhuangtai'],"/"));
			$s2=substr($_GET['Shangji_zhuangtai'],strripos($_GET['Shangji_zhuangtai'],"/")+1);
			$t1 = substr($_GET['Shangji_lastchulidate'],0,strrpos($_GET['Shangji_lastchulidate'],"/"));
			$t2=substr($_GET['Shangji_lastchulidate'],strripos($_GET['Shangji_lastchulidate'],"/")+1);
			$u1 = substr($_GET['Shangji_lastbeizhu'],0,strrpos($_GET['Shangji_lastbeizhu'],"/"));
			$u2=substr($_GET['Shangji_lastbeizhu'],strripos($_GET['Shangji_lastbeizhu'],"/")+1);
			$v1 = substr($_GET['Shangji_person'],0,strrpos($_GET['Shangji_person'],"/"));
			$v2=substr($_GET['Shangji_person'],strripos($_GET['Shangji_person'],"/")+1);
			$w1 = substr($_GET['Shangji_date'],0,strrpos($_GET['Shangji_date'],"/"));
			$w2=substr($_GET['Shangji_date'],strripos($_GET['Shangji_date'],"/")+1);
			$x1 = substr($_GET['Shangji_beizhu'],0,strrpos($_GET['Shangji_beizhu'],"/"));
			$x2=substr($_GET['Shangji_beizhu'],strripos($_GET['Shangji_beizhu'],"/")+1);
			$y1 = substr($_GET['Shangji_datetime'],0,strrpos($_GET['Shangji_datetime'],"/"));
			$y2=substr($_GET['Shangji_datetime'],strripos($_GET['Shangji_datetime'],"/")+1);
			$z1 = substr($_GET['Shangji_qianyuedate'],0,strrpos($_GET['Shangji_qianyuedate'],"/"));
			$z2=substr($_GET['Shangji_qianyuedate'],strripos($_GET['Shangji_qianyuedate'],"/")+1);
			
			$aa1 = substr($_GET['Shangji_qianyueway'],0,strrpos($_GET['Shangji_qianyueway'],"/"));
			$aa2=substr($_GET['Shangji_qianyueway'],strripos($_GET['Shangji_qianyueway'],"/")+1);
			$ab1 = substr($_GET['Shangji_qianyuejine'],0,strrpos($_GET['Shangji_qianyuejine'],"/"));
			$ab2=substr($_GET['Shangji_qianyuejine'],strripos($_GET['Shangji_qianyuejine'],"/")+1);
			$ac1 = substr($_GET['Shangji_adminperson'],0,strrpos($_GET['Shangji_adminperson'],"/"));
			$ac2=substr($_GET['Shangji_adminperson'],strripos($_GET['Shangji_adminperson'],"/")+1);
			
			$ad1 = substr($_GET['Shangji_laiyuan'],0,strrpos($_GET['Shangji_laiyuan'],"/"));
			$ad2=substr($_GET['Shangji_laiyuan'],strripos($_GET['Shangji_laiyuan'],"/")+1);
			$tempselectid=$_GET['tempselectid'];
			$tempstartdate=$_GET['tempstartdate'];
			$tempjieshudate=$_GET['tempjieshudate'];
			$tempkind=$_GET['tempkind'];
			$tempguanjianzi=$_GET['tempguanjianzi'];
			//dump($tempkind);exit;
			if($tempselectid!=null or $tempstartdate!=null or $tempjieshudate!=null){
				if($tempjieshudate<$tempstartdate){
					parent::Windowalert('结束时间不能小于开始时间。');
					parent::BackHistoryPage();
					exit;
				}
				$where="$tempselectid > '$tempstartdate' and $tempselectid <= '$tempjieshudate'";     //时间导出
				$condition['_complex'] = $where;
				$condition["Shangji_localqx"]=$list1["Person_localxq"];	
			}
			if($tempkind!=null and $tempguanjianzi!=null){
				$condition[$tempkind]=array('like','%'.$tempguanjianzi.'%');
			}
				if($list1["Person_biaoshi"]!=1){  //   主任、员工只能看到自己网格的
					$condition["Shangji_localwg"]=$list1["Person_localwg"];
				}
				$countduibi  = $xlsModel->where($condition)->count();
				if($countduibi != $_GET['count']){
				//	if(session('sjguolv')==1){
				//		$shuzu = array("已报价","跟进中");
				//		$condition['Shangji_zhuangtai']=array("in",$shuzu);
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
					$condition['Shangji_zhuangtai']=array("in",$shuzu);

				}
			$xlsName  = date('Ymd');
			$xlsCells  = array(
				array_filter(array($a1,$a2)),
				array_filter(array($b1,$b2)),
				array_filter(array($c1,$c2)),
				array_filter(array($d1,$d2)),
				array_filter(array($e1,$e2)),
				array_filter(array($f1,$f2)),
				array_filter(array($g1,$g2)),
				array_filter(array($h1,$h2)),
				array_filter(array($i1,$i2)),
				array_filter(array($j1,$j2)),
				array_filter(array($k1,$k2)),
				array_filter(array($l1,$l2)),
				array_filter(array($m1,$m2)),
				array_filter(array($n1,$n2)),
				array_filter(array($o1,$o2)),
				array_filter(array($p1,$p2)),
				array_filter(array($q1,$q2)),
				array_filter(array($r1,$r2)),
				array_filter(array($s1,$s2)),
				array_filter(array($t1,$t2)),
				array_filter(array($u1,$u2)),
				array_filter(array($v1,$v2)),
				array_filter(array($w1,$w2)),
				array_filter(array($x1,$x2)),
				array_filter(array($y1,$y2)),
				array_filter(array($z1,$z2)),
				array_filter(array($aa1,$aa2)),
				array_filter(array($ab1,$ab2)),
				array_filter(array($ac1,$ac2)),
				array_filter(array($ad1,$ad2))
			);
			foreach ($xlsCells as $key=>$value)         //删除数组元素并重新排序, 防止导出有空格列
			{
			  if ($value[0] === null)
				unset($xlsCells[$key]);
			}
			$xlsCell =array_values($xlsCells);
		}else{
			echo "参数错误！";
			exit;
		}
   
		if($biaoshi==1){
			//$xlsData  = $xlsModel->Field('Shangji_localqx,Shangji_localwgid,Shangji_localwg,Shangji_product,Shangji_level,Shangji_custhangye,Shangji_projectname,Shangji_companyname,Shangji_projectyusuan,Shangji_projectcanyu,Shangji_projectzhaobiao,Shangji_custmanager,Shangji_custmanageradmin,Shangji_startdate,Shangji_lastchulidate,Shangji_zhuangtai,Shangji_lastbeizhu,Shangji_person,Shangji_date,Shangji_beizhu,Shangji_qianyuedate,Shangji_qianyueway,Shangji_qianyuejine,Shangji_adminperson')->select();
			$xlsData  = $xlsModel->where($condition)->Field('Shangji_localqx,Shangji_localwgid,Shangji_localwg,Shangji_product,Shangji_level,Shangji_custhangye,Shangji_projectname,Shangji_companyname,Shangji_projectyusuan,Shangji_projectcanyu,Shangji_projectzhaobiao,Shangji_custmanager,Shangji_custmanageradmin,Shangji_startdate,Shangji_lastchulidate,Shangji_zhuangtai,Shangji_lastbeizhu,Shangji_person,Shangji_date,Shangji_beizhu,Shangji_qianyuedate,Shangji_qianyueway,Shangji_qianyuejine,Shangji_adminperson,Shangji_laiyuan')->select();
		
		}else{
			$xlsData  = $xlsModel->where($condition)->Field(array_filter(array($a1,$b1,$c1,$d1,$e1,$f1,$g1,$h1,$i1,$j1,$k1,$l1,$m1,$n1,$o1,$p1,$q1,$r1,$s1,$t1,$u1,$v1,$w1,$x1,$y1,$z1,$aa1,$ab1,$ac1,$ad1)))->select();
		}
		$this->exportExcel($xlsName,$xlsCell,$xlsData);
    }
	
	public function execlput1(){				//导出页面
		parent::YanZheng('loginemails','__APP__/Index/index');  //验证非法用户进出页面	
		$Person=new PersonModel();
		$tempid=session('loginemails');
		$panduantiaojian[$Person->_id]=$tempid;
		$userlist=$Person->where($panduantiaojian)->find();
		$this->assign('loginusers',$userlist);

			$Sitesdzc=new SitesdzcModel();//属地、职称
			$Sitecphy=new SitecphyModel();// 产品、行业
			
			$hangyecontion[$Sitecphy->_panduan]=2;
			$hangyelist=$Sitecphy->where($hangyecontion)->select();
			$this->assign('hangyelist',$hangyelist);   //行业
			$chanpincontion[$Sitecphy->_panduan]=1;
			$chanpinlist=$Sitecphy->where($chanpincontion)->order('Sitecphy_id')->select();
			$this->assign('chanpinlist',$chanpinlist);   //产品
			
			$wanggecontion[$Sitesdzc->_panduan]=2;
			$wanggecontion[$Sitesdzc->_nameid]=$userlist["Person_localxq"];
			$wanggelist=$Sitesdzc->where($wanggecontion)->select();
			$zhichenglist=$Sitesdzc->where($zhichengcontion)->select();
			$this->assign('wanggelist',$wanggelist);   //属地

			$this->display();
	}
	public function execlput2(){				//搜索导出页面
		parent::YanZheng('loginemails','__APP__/Index/index');  //验证非法用户进出页面	
		$Person=new PersonModel();
		$tempid=session('loginemails');
		$panduantiaojian[$Person->_id]=$tempid;
		$userlist=$Person->where($panduantiaojian)->find();
		$this->assign('loginusers',$userlist);

		$this->assign('tempselectid',$_GET['selectid']);
		$this->assign('tempstartdate',$_GET['start']);
		$this->assign('tempjieshudate',$_GET['end']);
		$this->assign('tempkind',$_GET['kind']);
		$this->assign('tempguanjianzi',$_GET['guanjianzi']);
		$this->assign('count',$_GET['count']);
	//	dump($_GET['count']);exit;
		$this->display();
	}
	
}
?>
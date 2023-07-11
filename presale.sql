-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2023 �?07 �?11 �?06:17
-- 服务器版本: 5.5.53
-- PHP 版本: 5.6.27

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `presale`
--

-- --------------------------------------------------------

--
-- 表的结构 `tb_log`
--

CREATE TABLE IF NOT EXISTS `tb_log` (
  `log_id` int(255) NOT NULL AUTO_INCREMENT COMMENT '自动增量',
  `log_action` varchar(100) NOT NULL COMMENT '日志动作（新增、删除、修改）',
  `log_kind` varchar(100) NOT NULL COMMENT '日志类别 ',
  `log_group` varchar(100) NOT NULL COMMENT '日志分组（来源模块）',
  `log_date` varchar(100) NOT NULL COMMENT '日志时间',
  `log_ip` varchar(50) DEFAULT NULL COMMENT 'IP',
  `log_view` varchar(100) DEFAULT NULL COMMENT '使用的浏览器',
  `log_text` text NOT NULL COMMENT '日志内容',
  `log_person` varchar(10) NOT NULL COMMENT '操作人',
  `log_localxq` varchar(100) NOT NULL COMMENT '所属县区',
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='日志表（Log）：' AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `tb_log`
--

INSERT INTO `tb_log` (`log_id`, `log_action`, `log_kind`, `log_group`, `log_date`, `log_ip`, `log_view`, `log_text`, `log_person`, `log_localxq`) VALUES
(1, '修改', '登录', 'APP_loginaction', '2023-07-11 14:02', '127.0.0.1', 'Firefox', 'PC端登录成功！账号为：110110', '袁与张', '昆山'),
(2, '修改', '编辑', 'websetaction', '2023-07-11 14:12', '127.0.0.1', 'Firefox', '编辑了网站设置！', '袁与张', '昆山'),
(3, '修改', '退出', 'APP_loginout', '2023-07-11 14:15', '127.0.0.1', 'Firefox', 'PC端退出成功！', '袁与张', '昆山'),
(4, '修改', '登录', 'APP_loginaction', '2023-07-11 14:16', '127.0.0.1', 'Firefox', 'PC端登录成功！账号为：110110', '袁与张', '昆山'),
(5, '修改', '退出', 'APP_loginout', '2023-07-11 14:16', '127.0.0.1', 'Firefox', 'PC端退出成功！', '袁与张', '昆山');

-- --------------------------------------------------------

--
-- 表的结构 `tb_person`
--

CREATE TABLE IF NOT EXISTS `tb_person` (
  `Person_int` int(20) NOT NULL AUTO_INCREMENT COMMENT '主键（自动增量）',
  `Person_name` varchar(30) DEFAULT NULL COMMENT '员工姓名',
  `Person_pwd` varchar(40) DEFAULT NULL COMMENT '员工密码',
  `Person_phone` varchar(50) DEFAULT NULL COMMENT '联系方式',
  `Person_id` int(6) NOT NULL COMMENT '员工编号（主键）',
  `Person_biaoshi` int(50) NOT NULL COMMENT '0：表示员工，1：县区管理员 2 表示网格（乡镇）主任',
  `Person_gangwei` varchar(50) NOT NULL DEFAULT '售前技术支撑' COMMENT '岗位职称',
  `Person_fex` varchar(2) DEFAULT NULL COMMENT '性别',
  `Person_mail` varchar(80) DEFAULT '请及时更新' COMMENT '邮箱',
  `Person_lastdate` varchar(80) DEFAULT '0000-00-00 00:00' COMMENT '最后一次登录时间',
  `Person_zhucedate` date NOT NULL COMMENT '注册时间',
  `Person_yanzheng` text COMMENT '随机码，验证登陆唯一性',
  `Person_localxq` varchar(20) DEFAULT NULL COMMENT '县区',
  `Person_localwg` varchar(20) DEFAULT NULL COMMENT '乡镇',
  PRIMARY KEY (`Person_int`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='员工表' AUTO_INCREMENT=218 ;

--
-- 转存表中的数据 `tb_person`
--

INSERT INTO `tb_person` (`Person_int`, `Person_name`, `Person_pwd`, `Person_phone`, `Person_id`, `Person_biaoshi`, `Person_gangwei`, `Person_fex`, `Person_mail`, `Person_lastdate`, `Person_zhucedate`, `Person_yanzheng`, `Person_localxq`, `Person_localwg`) VALUES
(1, '袁与张', '9c3e4bd513ea0196122783851989badd', '18888888888', 110110, 1, '移动云支撑', '男', 'zhn@ytyzhn.cn', '2023-07-11 14:16', '2016-01-01', 'u1JxzTCNP9', '昆山', ''),
(217, '温经理', '020f36f9b19d3979b6cf6478d1315875', '18888888888', 666666, 0, '客户经理', '男', 'wen@test.com', '0000-00-00 00:00', '2023-07-11', '123456', '昆山', '巴城'),
(216, '王主任', '020f36f9b19d3979b6cf6478d1315875', '18888888888', 122222, 2, '网格主任', '男', 'wang@test.com', '0000-00-00 00:00', '2023-07-11', '123456', '昆山', '巴城');

-- --------------------------------------------------------

--
-- 表的结构 `tb_shangji`
--

CREATE TABLE IF NOT EXISTS `tb_shangji` (
  `Shangji_id` int(20) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `Shangji_companyname` varchar(80) NOT NULL COMMENT '公司名称',
  `Shangji_custname` varchar(20) NOT NULL COMMENT '客户联系人',
  `Shangji_custphone` varchar(20) NOT NULL COMMENT '客户联系方式',
  `Shangji_companylocal` text NOT NULL COMMENT '客户公司地址',
  `Shangji_projectname` varchar(80) NOT NULL COMMENT '项目名称',
  `Shangji_product` varchar(200) NOT NULL DEFAULT '' COMMENT '涉及的产品',
  `Shangji_custhangye` varchar(11) NOT NULL COMMENT '客户所属行业（企业、政务、医疗）',
  `Shangji_level` varchar(50) NOT NULL COMMENT '客户级别（潜在、一般、重要、非常重要）',
  `Shangji_laiyuan` varchar(255) DEFAULT NULL COMMENT '商机来源',
  `Shangji_projectyusuan` varchar(100) DEFAULT NULL COMMENT '项目预算 （万为单位，不涉及可为空）',
  `Shangji_projectcanyu` varchar(100) DEFAULT NULL COMMENT '预计可参与金额 （万为单位，不涉及可为空）',
  `Shangji_projectzhaobiao` varchar(50) DEFAULT NULL COMMENT '预计招标时间   可为空',
  `Shangji_custmanager` varchar(20) NOT NULL COMMENT '客户经理',
  `Shangji_custmanageradmin` varchar(20) NOT NULL COMMENT '责任人（主任）',
  `Shangji_zhuangtai` varchar(20) NOT NULL COMMENT '商机状态（跟进中、已报价、已签约）',
  `Shangji_startdate` varchar(50) NOT NULL COMMENT '项目开始时间',
  `Shangji_lastchulidate` varchar(50) DEFAULT NULL COMMENT '项目最后跟踪时间',
  `Shangji_person` varchar(20) NOT NULL COMMENT '//创建人 ',
  `Shangji_date` varchar(50) NOT NULL COMMENT '商机录入时间',
  `Shangji_localqx` varchar(50) NOT NULL COMMENT '区县',
  `Shangji_localwg` varchar(50) DEFAULT NULL COMMENT '网格',
  `Shangji_localwgid` varchar(50) DEFAULT NULL COMMENT '网格ID',
  `Shangji_lastbeizhu` text COMMENT '项目最后跟踪详情',
  `Shangji_beizhu` text COMMENT '备注',
  `Shangji_datetime` varchar(100) NOT NULL DEFAULT '2022-12-07 00:00:00' COMMENT '过期时间',
  `Shangji_qianyuedate` varchar(50) DEFAULT NULL COMMENT '签约时间',
  `Shangji_qianyueway` varchar(20) DEFAULT NULL COMMENT '签约计费方式',
  `Shangji_qianyuejine` varchar(80) DEFAULT NULL COMMENT '签约金额',
  `Shangji_adminperson` varchar(50) DEFAULT NULL COMMENT '商机对接人（管理员）',
  PRIMARY KEY (`Shangji_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='商机表' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `tb_shangji`
--

INSERT INTO `tb_shangji` (`Shangji_id`, `Shangji_companyname`, `Shangji_custname`, `Shangji_custphone`, `Shangji_companylocal`, `Shangji_projectname`, `Shangji_product`, `Shangji_custhangye`, `Shangji_level`, `Shangji_laiyuan`, `Shangji_projectyusuan`, `Shangji_projectcanyu`, `Shangji_projectzhaobiao`, `Shangji_custmanager`, `Shangji_custmanageradmin`, `Shangji_zhuangtai`, `Shangji_startdate`, `Shangji_lastchulidate`, `Shangji_person`, `Shangji_date`, `Shangji_localqx`, `Shangji_localwg`, `Shangji_localwgid`, `Shangji_lastbeizhu`, `Shangji_beizhu`, `Shangji_datetime`, `Shangji_qianyuedate`, `Shangji_qianyueway`, `Shangji_qianyuejine`, `Shangji_adminperson`) VALUES
(1, '初步洽谈商机客户', '1', '1', '1', '1', '移动云', '企业', '一般', '规上、纳税百强客户', '10', '10', '', '温经理', '王主任', '初步洽谈', '2023-07-11 14:06:27', '2023-07-11 14:07', '袁与张', '2023-07-11 14:07', '昆山', '巴城', '110301', '初步洽谈商机客户', '初步洽谈商机客户', '2023-10-11 02:06:27', NULL, NULL, NULL, '袁与张'),
(2, '已报价商机客户', '1', '1', '1', '1', '政务云', '企业', '重要', '异云集团', '100', '100', '2023-07-11 14:07:47', '王主任', '王主任', '已报价', '2023-07-11 14:07:05', '2023-07-11 14:08', '袁与张', '2023-07-11 14:08', '昆山', '巴城', '110301', '已报价商机客户', '已报价商机客户', '2023-10-11 02:07:05', NULL, NULL, NULL, '袁与张'),
(3, '已签约商机客户', '1', '1', '1', '1', '云桌面', '教育', '潜在', '企查查-注册≥1000万成员≥100人', '2', '2', '2023-07-11 14:08:45', '温经理', '王主任', '已签约', '2023-07-11 14:08:10', '2023-07-11 14:10', '袁与张', '2023-07-11 14:09', '昆山', '巴城', '110301', '已完成签约，签约厂家：**', '已签约商机客户', '2023-10-11 02:10:34', '2023-07-12', '一次性支付', '2', '袁与张');

-- --------------------------------------------------------

--
-- 表的结构 `tb_shangjifankui`
--

CREATE TABLE IF NOT EXISTS `tb_shangjifankui` (
  `Shangjifankui_id` int(20) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `Shangjifankui_sjid` int(20) NOT NULL COMMENT '商机ID',
  `Shangjifankui_content` text NOT NULL COMMENT '反馈内容',
  `Shangjifankui_person` varchar(20) NOT NULL COMMENT '反馈人',
  `Shangjifankui_date` varchar(50) NOT NULL COMMENT '反馈时间',
  `Shangjifankui_url` text COMMENT 'url',
  PRIMARY KEY (`Shangjifankui_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='商机反馈表' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `tb_shangjifankui`
--

INSERT INTO `tb_shangjifankui` (`Shangjifankui_id`, `Shangjifankui_sjid`, `Shangjifankui_content`, `Shangjifankui_person`, `Shangjifankui_date`, `Shangjifankui_url`) VALUES
(1, 3, '状态:[已签约]&nbsp;跟踪详情：已完成签约，签约厂家：**', '袁与张', '2023-07-11 14:10', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `tb_sitecphy`
--

CREATE TABLE IF NOT EXISTS `tb_sitecphy` (
  `Sitecphy_id` int(20) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `Sitecphy_panduan` int(2) NOT NULL COMMENT '类别ID  （1 表示产品、2表示行业）',
  `Sitecphy_name` varchar(20) NOT NULL COMMENT '名称',
  `Sitecphy_person` varchar(10) NOT NULL COMMENT '创建人',
  `Sitecphy_date` date NOT NULL COMMENT '创建时间',
  `Sitecphy_localqx` varchar(20) NOT NULL COMMENT '区县',
  PRIMARY KEY (`Sitecphy_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='产品、行业等类别' AUTO_INCREMENT=5008 ;

--
-- 转存表中的数据 `tb_sitecphy`
--

INSERT INTO `tb_sitecphy` (`Sitecphy_id`, `Sitecphy_panduan`, `Sitecphy_name`, `Sitecphy_person`, `Sitecphy_date`, `Sitecphy_localqx`) VALUES
(1, 1, '移动云', '袁与张', '2022-04-11', '昆山'),
(2, 1, '政务云', '袁与张', '2022-04-11', '昆山'),
(3, 1, '云电脑', '袁与张', '2022-03-01', '昆山'),
(4, 1, '云桌面', '袁与张', '2022-03-01', '昆山'),
(5, 1, '云存储', '袁与张', '2022-03-01', '昆山'),
(6, 1, '国际云转售', '袁与张', '2022-03-01', '昆山'),
(7, 1, 'CDN(网络部)', '袁与张', '2022-04-11', '昆山'),
(8, 1, 'IDC业务', '袁与张', '2022-04-11', '昆山'),
(9, 1, 'ICT业务', '袁与张', '2022-04-11', '昆山'),
(10, 1, '短信业务', '袁与张', '2022-04-11', '昆山'),
(11, 1, '专线线路', '袁与张', '2022-04-11', '昆山'),
(20, 1, '5G业务', '袁与张', '2022-04-11', '昆山'),
(21, 1, '其他', '袁与张', '2022-04-11', '昆山'),
(1001, 2, '企业', '袁与张', '2022-03-01', '昆山'),
(1002, 2, '政府', '袁与张', '2022-03-01', '昆山'),
(1003, 2, '医疗', '袁与张', '2022-03-01', '昆山'),
(1004, 2, '教育', '袁与张', '2022-03-01', '昆山'),
(5000, 3, '企查查-注册≥1000万成员≥100人', '袁与张', '2023-03-30', '昆山'),
(5001, 3, '规上、纳税百强客户', '袁与张', '2023-03-31', '昆山'),
(5002, 3, '独角兽企业', '袁与张', '2023-04-01', '昆山'),
(5003, 3, '异云策反目标用户', '袁与张', '2023-04-02', '昆山'),
(5004, 3, '异云集团', '袁与张', '2023-04-03', '昆山'),
(5005, 3, '异云大单集团', '袁与张', '2023-04-04', '昆山'),
(5006, 3, '国企', '袁与张', '2023-04-05', '昆山'),
(5007, 3, '自动驾驶技术企业', '袁与张', '2023-04-06', '昆山');

-- --------------------------------------------------------

--
-- 表的结构 `tb_sitesdzc`
--

CREATE TABLE IF NOT EXISTS `tb_sitesdzc` (
  `Sitesdzc_id` int(20) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `Sitesdzc_panduan` int(2) NOT NULL COMMENT '类别ID  （1 表示区县、2表示网格、3表示职称）',
  `Sitesdzc_name` varchar(20) NOT NULL COMMENT '名称 ',
  `Sitesdzc_qxwgid` varchar(20) DEFAULT NULL COMMENT '区县网格ID',
  `Sitesdzc_nameid` varchar(20) DEFAULT NULL COMMENT '网格外键 区县名称   职称时可为空',
  `Sitesdzc_person` varchar(10) NOT NULL COMMENT '创建人',
  `Sitesdzc_date` date NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`Sitesdzc_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT=' 属地、职称类别' AUTO_INCREMENT=26 ;

--
-- 转存表中的数据 `tb_sitesdzc`
--

INSERT INTO `tb_sitesdzc` (`Sitesdzc_id`, `Sitesdzc_panduan`, `Sitesdzc_name`, `Sitesdzc_qxwgid`, `Sitesdzc_nameid`, `Sitesdzc_person`, `Sitesdzc_date`) VALUES
(1, 3, '网格主任', NULL, NULL, '袁与张', '2022-03-01'),
(2, 3, '移动云支撑', NULL, NULL, '袁与张', '2022-03-01'),
(3, 3, '客户经理', NULL, NULL, '袁与张', '2022-03-01'),
(4, 3, '区县管理员', NULL, NULL, '袁与张', '2022-03-01'),
(5, 1, '昆山', '1103', NULL, '袁与张', '2022-03-01'),
(6, 2, '巴城', '110301', '昆山', '袁与张', '2022-03-01'),
(7, 2, '青阳', '110310', '昆山', '袁与张', '2022-03-01'),
(8, 1, '工业园区', NULL, NULL, '袁与张', '2022-03-01'),
(10, 2, '唯亭', NULL, '工业园区', '袁与张', '2022-03-01'),
(11, 2, '斜塘', NULL, '工业园区', '袁与张', '2022-03-01'),
(12, 2, '柏庐', '110302', '昆山', '袁与张', '2022-03-01'),
(13, 2, '亭林', '110311', '昆山', '袁与张', '2022-03-01'),
(14, 2, '淀山湖', '110303', '昆山', '袁与张', '2022-03-01'),
(15, 2, '周庄', '110315', '昆山', '袁与张', '2022-03-01'),
(16, 2, '锦溪', '110306', '昆山', '袁与张', '2022-03-01'),
(17, 2, '花桥', '110305', '昆山', '袁与张', '2022-03-01'),
(18, 2, '高新区', '110304', '昆山', '袁与张', '2022-03-01'),
(19, 2, '开发区', '110307', '昆山', '袁与张', '2022-03-01'),
(20, 2, '陆家', '110308', '昆山', '袁与张', '2022-03-01'),
(21, 2, '千灯', '110309', '昆山', '袁与张', '2022-03-01'),
(22, 2, '震川', '110313', '昆山', '袁与张', '2022-03-01'),
(23, 2, '张浦', '110312', '昆山', '袁与张', '2022-03-01'),
(24, 2, '周市', '110314', '昆山', '袁与张', '2022-03-01'),
(25, 2, '行业', '110300', '昆山', '袁与张', '2022-04-01');

-- --------------------------------------------------------

--
-- 表的结构 `tb_siteset`
--

CREATE TABLE IF NOT EXISTS `tb_siteset` (
  `siteset_id` int(50) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `siteset_content` varchar(50) NOT NULL COMMENT '设置内容',
  `siteset_kind` varchar(50) NOT NULL COMMENT '设置类型',
  `siteset_personid` varchar(50) NOT NULL COMMENT '用户ID',
  `siteset_zhuangtai` varchar(50) NOT NULL COMMENT '0:暂停 1:启用',
  PRIMARY KEY (`siteset_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='设置表' AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `tb_siteset`
--

INSERT INTO `tb_siteset` (`siteset_id`, `siteset_content`, `siteset_kind`, `siteset_personid`, `siteset_zhuangtai`) VALUES
(1, '16', '1', '110110', '1'),
(2, '初步洽谈', '2', '110110', '1'),
(3, '已报价', '2', '110110', '1'),
(4, '跟进中', '2', '110110', '1'),
(5, '已签约', '2', '110110', '1'),
(6, '已过期', '2', '110110', '1'),
(7, '商机丢失', '2', '110110', '1');

-- --------------------------------------------------------

--
-- 表的结构 `tb_webset`
--

CREATE TABLE IF NOT EXISTS `tb_webset` (
  `Webset_id` int(11) NOT NULL AUTO_INCREMENT,
  `Webset_sitename` varchar(80) DEFAULT '' COMMENT '网站名称',
  `Webset_icpname` varchar(50) DEFAULT NULL COMMENT 'ICP备案号',
  `Webset_icpurl` text COMMENT 'ICP网址',
  `Webset_loginurl1` text COMMENT '登录URL1',
  `Webset_loginurl2` text COMMENT '登录url2',
  `Webset_loginurl3` text COMMENT '登录url3',
  `Webset_usersdefaultpwd` text,
  `Webset_adminurl` varchar(255) DEFAULT NULL COMMENT '站长主页URL',
  `Webset_adminname` varchar(255) DEFAULT NULL COMMENT '站长主页名字',
  PRIMARY KEY (`Webset_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='网站设置表' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `tb_webset`
--

INSERT INTO `tb_webset` (`Webset_id`, `Webset_sitename`, `Webset_icpname`, `Webset_icpurl`, `Webset_loginurl1`, `Webset_loginurl2`, `Webset_loginurl3`, `Webset_usersdefaultpwd`, `Webset_adminurl`, `Webset_adminname`) VALUES
(1, '云计算售前支撑管理v2.1', '苏ICP备2****号', 'https://beian.miit.gov.cn/', 'https://ytyzhn.top/usr/uploads/2023/06/4150586422.jpg', 'https://ytyzhn.top/usr/uploads/2023/06/2010730342.jpg', 'https://ytyzhn.top/usr/uploads/2023/06/1417599949.jpg', 'ytyzhn@123~', 'https://ytyzhn.top', '袁与张');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

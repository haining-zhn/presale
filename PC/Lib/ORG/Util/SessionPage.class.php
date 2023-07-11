<?php
class SessionPage {
    public $parameter  ;
    // Ĭ���б�ÿҳ��ʾ����
    public $listRows = 20;
    // ��ʼ����
    public $firstRow	;
    // ��ҳ��ҳ����
    protected $totalPages  ;
    // ������
    protected $totalRows  ;
    // ��ǰҳ��
    protected $nowPage    ;
    // ��ҳ��ʾ����
    protected $varPage;

    public function __construct($totalRows,$listRows='',$parameter='') {
        $this->totalRows = $totalRows;
        $this->parameter = $parameter;
        $this->varPage = C('VAR_PAGE') ? C('VAR_PAGE') : 'p' ;
        if(!empty($listRows)) {
            $this->listRows = intval($listRows);
        }
        $this->totalPages = ceil($this->totalRows/$this->listRows);     //��ҳ��
        $this->nowPage  = !empty($_SESSION[$this->varPage])?intval($_SESSION[$this->varPage]):1;
        if(!empty($this->totalPages) && $this->nowPage>$this->totalPages) {
            $this->nowPage = $this->totalPages;
        }
        $this->firstRow = $this->listRows*($this->nowPage-1);
    }

    public function setConfig($name,$value) {
        if(isset($this->config[$name])) {
            $this->config[$name]    =   $value;
        }
    }

    
    
}
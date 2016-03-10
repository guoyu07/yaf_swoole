<?php
/**
 * @name AdmincommonController
 * @author rain
 * @desc 公共的初始化操作的控制器后台的所有控制器继承此Controller
 */
class AdmincommonController extends InitController
{
	public function init()
	{
		parent::init();
		$this->loginCheck();
	}
	
	private function loginCheck()
	{
		if ($this->getRequest()->getActionName() != 'index' && $this->getRequest()->getControllerName() == 'Admin' && empty($this->session('adminlogin'))) {
			$this->location("/admin");
		}
	}
}

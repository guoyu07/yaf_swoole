<?php
/**
 * @name AdminController
 * @author rain
 * @desc 后台入口的控制器
 */
class AdminController extends AdmincommonController
{
	public function indexAction()
	{
		$data = HttpServer::$post;
		if (isset($data['un']) && isset($data['pw']) && !empty($data['un']) && !empty($data['pw'])) {
			$this->session('adminlogin', '3');
			$this->location('/admin/default');
		}
		return true;
	}

	public function defaultAction()
	{
		return true;
	}
}

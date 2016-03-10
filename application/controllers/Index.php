<?php
/**
 * @name IndexController
 * @author rain
 * @desc 默认控制器
 */
class IndexController extends CommonController
{
	public function indexAction()
	{
		//1. fetch query
		//$get = $this->getRequest()->getQuery("get", "default value");
		// $get = HttpServer::$get;

		return TRUE;
	}
}

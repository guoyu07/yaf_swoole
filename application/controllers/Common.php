<?php
/**
 * @name CommonController
 * @author rain
 * @desc 公共的初始化操作的控制器所有后台控制器都继承此Controller
 */
class CommonController extends InitController
{
	public function init()
	{
		parent::init();
		$confData = ConfModel::getConf();
		if (is_array($confData) && !empty($confData)) {
			foreach ($confData as $confKey => $confVal) {
				$this->getView()->assign($confKey, $confVal['val']);
			}
		}
		unset($confData);
	}
}

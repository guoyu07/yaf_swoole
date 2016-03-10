<?php
/**
 * @name ConfModel
 * @desc 网站配置
 * @author Rain
 */
class ConfModel
{
    
    /**
     * 获得网站配置
     * @param  $type 配置类型，1是网站配置
     * @return mixed 成功返回结果array数据，失败返回false
    */
    public static function getConf($type = 1)
    {
	$mysql = Mysql::getInstance();
	$data = $mysql->fetchAllCache('SELECT * FROM conf WHERE type='.$type);
	if (empty($data) || !is_array($data)) return;
	
	$ret = [];
	foreach ($data as $rs)
	{
		$ret[$rs['name']] = $rs;
	}
	return $ret;
    }
}

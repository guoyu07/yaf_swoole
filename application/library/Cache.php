<?php
/**
* 缓存操作控制类Cache，支持memcache和file cache
* @filename Cache.php
* @touch date 2014-07-24 09:32:24
* @author Rain<563268276@qq.com>
* @copyright 2014 http://www.94cto.com/
* @license http://www.apache.org/licenses/LICENSE-2.0   LICENSE-2.0
*/

/**
* 缓存操作控制类Cache，目前支持memcache和file cache
*/
class Cache
{
	/**
	* 存储相关配置信息的
	*/
	private $conf = null;

	/**
	* Cache类的实例对象
	*/
	private static $_instance = null;

	/**
	* Cache类的缓存类型，默认f代表文件缓存模式
	*/
	private  $_type = 'f';

	/**
	* Cache类的链接对象
	*/
	private static  $con = null;

	/**
	* Cache类是单例模式，不支持clone
	*/
	private function __clone()
	{
	}

	/**
	* Cache类构造方法，用来指定缓存模式和相应的缓存配置，仅供系统调用
	* @param string $type  缓存类型，可选值：f或m，其中f代表文件缓存，m代表内存缓存，默认f
	* @param string $conf  缓存配置，仅对内存缓存memcache有效
	* @return  void
	*/
	private function __construct($type = 'f', $conf = null)
	{
		$this->_type = $type;
		if ($type == 'f') return true;

		if ($type == 'm' && !extension_loaded('memcache'))
			throw new Exception("memcache extension not find");

		$_conf = Yaf_Registry::get('config');

		$this->conf = array(
			'host' => $_conf['memcache']['host'],
			'port' => $_conf['memcache']['port'],
			'timeout' => $_conf['memcache']['timeout'],
		);

		if (is_array($conf) && !empty($conf))
		{
			foreach ($conf as $k => $v)
			{
				if (!is_scalar($v) || !isset($this->conf[$k]))
				{
					unset($conf[$k]);
					continue;
				}
				$this->conf[$k] = $v;
			}
		}
	}

	/**
	* 构造获取对象，外部调用此方法获得Cache对象
	* <code>
	* Cache::getInstance();
	* </code>
	* @param string $type  缓存类型，可选值：f或m，其中f代表文件缓存，m代表内存缓存，默认f
	* @param string $conf  缓存配置，仅对内存缓存memcache有效
	* @return object 构造好的对象 
	*/
	public static function getInstance($type = 'f', $conf = null)
	{
		if (!(self::$_instance instanceof self))
			self::$_instance = new self($type, $conf);
		return self::$_instance;
	}

	/**
	* 构建链接，仅对memcache有效
	* <code>
	* Cache::connect();
	* </code>
	* @return bool 成功返回true，失败返回false
	*/
	public function connect()
	{
		if ($this->_type == 'f') return true;
		if (!is_null(self::$con))
			return self::$con;
		self::$con = new Memcache;
		$ret = self::$con->connect($this->conf['host'], $this->conf['port'], 1);
		if (!$ret)
			throw new Exception("memcache connect error");
	}

	/**
	* 清除缓存，仅对memcache有效
	* <code>
	* Cache::clear();
	* </code>
	* @return bool 成功返回true，失败返回false
	*/
	public function clear()
	{
		if ($this->_type == 'f') return true;
		if ($this->_type == 'm')
		{
			if (is_null(self::$con))
				$this->connect();
			
			$ret = self::$con->flush();
			//$this->free();
			return $ret;
		}
	}

	/**
	* 获取缓存中的数据
	* <code>
	* Cache::get('key');
	* </code>
	* @param string $key  key
	* @return bool 成功返回对应的值，失败返回false
	*/
	public function get($key)
	{
		if ($this->_type == 'f')
		{
			$_conf = Yaf_Registry::get('config');
			$file = $_conf['application']['directory'].'/data/'.md5($key);
			if (file_exists($file) && filemtime($file) < time())
			{
				@unlink($file);
				return false;
			}
			if (file_exists($file) && filemtime($file) >= time())
			{
				return @unserialize(file_get_contents($file));
			}
		}
		elseif ($this->_type == 'm')
		{
			if (is_null(self::$con))
				$this->connect();
			$ret = self::$con->get($key);
			//$this->free();
			if (is_string($ret))
				return @unserialize($ret);
			else
				return @$ret;
		}
	}

	/**
	* 根据key删除缓存中的数据
	* <code>
	* Cache::rm('key');
	* </code>
	* @param string $key  key
	* @return bool 成功返回对应的值，失败返回false
	*/
	public function rm($key)
	{
		if ($this->_type == 'f')
		{
			$_conf = Yaf_Registry::get('config');
			$file = $_conf['application']['directory'].'/data/'.md5($key);
			if (file_exists($file))
			{
				@unlink($file);
				return true;
			}
		}
		elseif ($this->_type == 'm')
		{
			if (is_null(self::$con))
				$this->connect();
			$ret = self::$con->delete($key);
			//$this->free();
			return $ret;
		}
	}

	/**
	* 根据key删除缓存中的数据
	* <code>
	* Cache::set('key', 'value', 1200);
	* </code>
	* @param string $key  key
	* @param string $val  value
	* @param int $expire  过期时间，单位：秒，默认2小时
	* @return bool 成功返回对应的值，失败返回false
	*/
	public function set($key, $val, $expire = 1200)
	{
		if ($this->_type == 'f')
		{
			$_conf = Yaf_Registry::get('config');
			$file = $_conf['application']['directory'].'/data/'.md5($key);
			$ret = file_put_contents($file, @serialize($val));
			touch($file, time() + $expire);
			return $ret;
		}
		elseif ($this->_type == 'm')
		{
			if (is_null(self::$con))
				$this->connect();
			$ret = self::$con->set($key, @serialize($val), 1, $expire);
			//$this->free();
			return $ret;
		}
	}

	/**
	* 关闭缓存，释放资源，仅对memcache有效
	* <code>
	* Cache::free();
	* </code>
	* @return bool 成功返回true，失败返回false
	*/
	public function free()
	{
		if ($this->_type == 'f')
			return true;
		if (!is_null(self::$con))
			return self::$con->close();
	}
}

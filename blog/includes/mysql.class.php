<?php 
// 防止恶意调用
if (!defined('IN_TG')) {
	exit('Access Defined!');
}
/*
 * 数据库连接操作
 */


class DbMysqli {
	private $error = '';  
    private $errno = 0;  
    private $port;  
    private $host;  
    private $username;  
    private $password;  
    private $dbname;  
    private $charset;  
    private $db;  

     /** 
     * 构造函数 
     * @author aaron 
     * @return void  
     */  
    function __construct() {
        $this->host = 'localhost';  
        $this->username = 'root';  
        $this->password = '';  
        $this->dbname =  'blog';  
        $this->charset = 'UTF8';  
          
        $db = mysqli_connect($this->host, $this->username, $this->password, $this->dbname);  
        if(!$db){  
            $this->error = mysqli_connect_error();  
            $this->errno = mysqli_connect_errno();  
            return FALSE;  
        }  
        mysqli_set_charset($db, $this->charset);  
        $this->db = $db;  
    }  
    /** 
    * 数据库连接 
    * @author aaron 
    */  
    function _connect() { 
        return $this->db;  
    } 

    public function _error($connect) {
        return mysqli_error($this->_connect());
    }

    /** 
     * 运行Sql语句,不返回结果集 
     * @author eric 
     * @param string $sql  
     * @return mysqli_result|bool 
     */  
    public function runSql($sql)  
    {  
        $dbconnect = $this->_connect();  
        if ($dbconnect === false) {  
            return FALSE;  
        }  
        // 成功:TRUE 失败:FALSE  
        $res = mysqli_query($dbconnect,$sql);  
          
        // 保存错误  
        $this->save_error($dbconnect);  
          
        return $res;  
    }  

	public function _query($_sql) {
		$res = mysqli_query($this->db,$_sql);
		if (!$res) {
			echo "sql语句执行失败<br>";
			echo "错误编码是".mysqli_errno($this->db)."<br>";
			echo "错误信息是".mysqli_error($this->db)."<br>";
			exit;
		}
		return $res;
	}

	/** 
     * 获取受影响的行数 
     * @author aaron 
     * @param string $sql  
     * @return int 
     */  
    public function _affected_rows()  
    {  
        $dbconnect = $this->_connect();  
        if ($dbconnect === false) {  
            return FALSE;  
        }  
        $res = mysqli_affected_rows($dbconnect);  
        // 保存错误  
        $this->save_error($dbconnect);  
          
        return $res;  
    }  

    /**
     * [_insert_id description]
     * @param  [type] $_result [description]
     * @return [type]          [description]
     */
    function _insert_id($_result) {
        $dbconnect = $this->_connect();  
        if ($dbconnect === false) {  
            return FALSE;  
        }  
        return mysqli_insert_id($this->_connect());
    }

        /**
     * _free_result销毁结果集
     * @param $_result
     */

    function _free_result($_result) {
        mysqli_free_result($_result);
    }

	public function _fetch_array($_sql) { 
		return mysqli_fetch_array($this->_query($_sql),MYSQLI_ASSOC);
	}

    public function _fetch_array_list($_result) {
        return mysqli_fetch_array($_result,MYSQLI_ASSOC);
    }
	
	/**
	* 
	* @param $_sql
	* @param $_info
	*/
	public function _is_repeat($_sql,$_info) {
		if ( self::_fetch_array($_sql)) {
			_alert_back($_info);
		}
	}

	 /** 
     * 关闭数据库连接 
     * @author aaron 
     * @return bool 
     */  
    public function closeDb()  
    {  
        @mysqli_close($this->_connect());  
    }  
      
      
    public function errno()  
    {  
        return $this->errno;  
    }  
    public function errmsg()  
    {  
        return $this->error;  
    }  
    private function save_error($dbconnect)  
    {  
        $this->errno = mysqli_errno($dbconnect);  
        $this->error = mysqli_error($dbconnect);  
    }    
} 
 ?>


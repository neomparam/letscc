<?
require_once("config.php");

class dbConn{
	var $dbhost = DB_HOST;
	var $dbuser,$dbpasswd,$db,$connect;

	function dbConn($db=DB_NAME,$dbuser=DB_USER,$dbpasswd=DB_PASSWD) {
		$this->dbuser = $dbuser;
		$this->dbpasswd = $dbpasswd;
		$this->db=$db;

		$this->connect=mysql_connect($this->dbhost,$this->dbuser,$this->dbpasswd) ;
		$_GET = array_map('trim', $_GET); 
		$_POST = array_map('trim', $_POST); 
		$_COOKIE = array_map('trim', $_COOKIE); 
		$_REQUEST = array_map('trim', $_REQUEST); 
		if(get_magic_quotes_gpc()): 
		    $_GET = array_map('stripslashes', $_GET); 
		    $_POST = array_map('stripslashes', $_POST); 
		    $_COOKIE = array_map('stripslashes', $_COOKIE); 
		    $_REQUEST = array_map('stripslashes', $_REQUEST); 
		endif; 
		$_GET = array_map('mysql_real_escape_string', $_GET); 
		$_POST = array_map('mysql_real_escape_string', $_POST); 
		$_COOKIE = array_map('mysql_real_escape_string', $_COOKIE); 
		$_REQUEST = array_map('mysql_real_escape_string', $_REQUEST); 		
		mysql_select_db($this->db,$this->connect);

		mysql_query("set names 'utf8'",$this->connect);
	}

	function getConnection() {
		return $this->connect;
	}

	function setResult($query) {
		$result[result] = mysql_query($query,$this->connect);
		$result[cnt] = @mysql_affected_rows();
		$result[query] =$query;
		return $result;
	}

	function removeQuot($str) {
		$str = str_replace("\"","",$str);
		$str = str_replace("'","",$str);
		return trim($str);
	}
	function addSlash($str) {
		$str = trim($str);
		$str = addslashes($str);
		return trim($str) ;
	}

	function stripSlash($str) {
		$str = stripslashes($str);
		return trim($str);
	}

	function alertMsg($ment,$url,$parent="",$opt="") {
		echo "<script>alert(\"$ment\");" .$parent . "location.href='$url';" . $opt."</script>";
		exit;
	}

	function alertNotMsg($url,$parent="",$opt="") {
		echo "<script>".$parent . "location.href='$url';" . $opt."</script>";
		exit;
	}

	function metaMsg($url) {
		echo "<meta http-equiv=Refresh content='0;url=$url'>";
		exit;
	}

	function historyBack($ment) {
		echo "<script>alert(\"$ment\");history.back();</script>";
		exit;
	}

	function historyBackNoMsg() {
		echo "<script>history.back();</script>";
		exit;
	}

	function dbQuery($q) {
		$re = $this->setResult($q);
		return $re;
	}

	function dbSelect($table,$where,$field="*") {
		$q = "select $field from $table $where";
		$re = $this->setResult($q);
		return $re;
	}

	function dbInsert($table,$arr,$arrValue) {
		for($i=0;$i<count($arrValue);$i++) {
			if($i == 0) $arrVal="'" . $arrValue[$i] . "'";
			else $arrVal .= ",'" . $arrValue[$i] . "'";
		}
		for($i=0;$i<count($arr);$i++) {
			if($i == 0 || $i == count($arr)) $arrSal .=  $arr[$i] ;
			else $arrSal .= "," . $arr[$i];
		}
		$q = "insert into $table ($arrSal) values ($arrVal)";
		$re = $this->setResult($q);

		return $re;
	}

	function dbUpdate($table,$arr,$where) {
		$i = 0 ;
		while(list($key,$val) = each($arr)) {
			if ($i==0) $arrVal = $key ."='" . $val . "'";
			else $arrVal .= "," . $key ."='" . $val . "'" ;
			$i++ ;
		}
		$q = "update $table set $arrVal $where ";
		$re = $this->setResult($q) ;
		return $re;
	}

	function dbDelete($table,$where) {
		$q = "delete from $table $where ";

		$re = $this->setResult($q) ;
		return $re;
	}

	function memGrade() {
		if($_SESSION[user]) {
			$q = "select memtype from member where  id='$_SESSION[user]'";
			$re_memgrade = $this->setResult($q);
			if($re_memgrade[cnt] >0) {
				$row_grade = mysql_fetch_array($re_memgrade[result]);
				return $row_grade[memtype];
			}
		}
	}
}

?>

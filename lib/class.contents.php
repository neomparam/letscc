<?
class clsContents {
	var $conn;

	function __construct( $conn ) {
		$this->conn = $conn;
	}

	function save($array) {
		$result = array();

		if( $array['c_id'] != "" && $array['c_type'] != "" && $array['s_name'] != "" ) {
			$query = "select idx from contents where c_type = '".$array['c_type']."' and c_id = '".$array['c_id']."' and s_name = '".$array['s_name']."'";
			$res = mysql_query($query,$this->conn) or die ("select query error!!");

			if( @mysql_affected_rows() > 0 ) {
				$row = mysql_fetch_array($res);

				$query = "update contents set c_info = '".$array['c_info']."' where idx = ".$row['idx'];
				mysql_query($query,$this->conn) or die ("content update query error!!");

				$result['r'] = "exist";
				$result['idx'] = $row['idx'];
				$result['msg'] = "이미 등록되어 있는 콘텐츠 정보입니다.";
			} else {
				$query = "insert into contents ( c_id, c_type, s_name, c_info, regdate )";
				$query .= " values ( '".$array['c_id']."', '".$array['c_type']."', '".$array['s_name']."', '".$array['c_info']."', now() )";
				$res = mysql_query($query,$this->conn) or die ("insert query error!!");

				if( $res ) {
					$result['r'] = "success";
					$result['idx'] = mysql_insert_id();
					$result['msg'] = "콘텐츠가 등록되었습니다.";
				} else {
					$result['r'] = "error";
					$result['msg'] = "콘텐츠 등록에 실패하였습니다.";
				}
			}

			$this->saveSearchStatus( $result['idx'], $array['k'], $array['c'], $array['d'] );

		} else {
			$result['r'] = 'error';
			$result['msg'] = "콘텐츠 입력 정보가 올바르지 않습니다.";
		}

		return $result;
	}

	function saveSearchStatus( $c_idx, $keyword, $c, $d ) {
		$result = array();

		$query = "select idx from contents_search_status where c_idx = ".$c_idx." and c='".$c."' and d='".$d."' and search_word='".$keyword."'";
		$res = mysql_query($query,$this->conn) or die ("select SearchStatus query error!!");

		if( @mysql_affected_rows() <= 0 ) {
			$query = "insert into contents_search_status ( c_idx, search_word, c, d )";
			$query .= " values ( ".$c_idx.", '".$keyword."', '".$c."', '".$d."' )";
			$res = mysql_query($query,$this->conn) or die ("insert SearchStatus query error!!");

			if( $res ) {
				$result['r'] = "success";
				$result['idx'] = mysql_insert_id();
				$result['msg'] = "콘텐츠가 등록되었습니다.";
			} else {
				$result['r'] = "error";
				$result['msg'] = "콘텐츠 등록에 실패하였습니다.";
			}
		}

		return $result;
	}

	function incrementFavorite( $idx ) {
		$query = "select f_count from contents  where idx = ".$idx;
		$res = mysql_query($query,$this->conn) or die ("incrementFavorite select query error!!");
		$row = mysql_fetch_array($res);

		$f_count = (int)$row['f_count'] + 1;

		$query = "update contents set f_count = ".$f_count." where idx = ".$idx;
		$res = mysql_query($query,$this->conn) or die ("incrementFavorite update query error!!");

		return $f_count;
	}

	function decrementFavorite( $idx ) {
		$query = "select f_count from contents  where idx = ".$idx;
		$res = mysql_query($query,$this->conn) or die ("decrementFavorite select query error!!");
		$row = mysql_fetch_array($res);

		$f_count = (int)$row['f_count'] - 1;
		if( $f_count < 0 ) $f_count = 0;

		$query = "update contents set f_count = ".$f_count." where idx = ".$idx;
		$res = mysql_query($query,$this->conn) or die ("decrementFavorite update query error!!");

		return $f_count;
	}

	function getData($idx) {
		$result = array();

		$query = "select * from contents where idx = ".$idx;
		$res = mysql_query($query,$this->conn) or die ("getContent select query error!!");

		if( @mysql_affected_rows() > 0 ) {
			$row = mysql_fetch_array($res);
			return $row;
		} else {
			return NULL;
		}
	}

	function getSearchContents($c_type, $s=0, $len=0, $k, $c, $d, $enable="" ) {
		$where_enable = ""; $where_temp = "";
		if( $enable != "" ) $where_enable = " and c.f_enable='".$enable."'";

		$result = array();
		$limit = "";
		if( $len > 0 ) $limit = " limit ".$s.",".$len;
		else if( $s > 0 ) $limit = " limit ".$s;

		$query = "select *, s.idx as s_idx from contents_search_status as s, contents as c ";
		$query .= " where s.c_idx = c.idx and c.c_type = '".$c_type."' and c.f_count > 0 and s.c='".$c."' and s.d = '".$d."' and s.search_word = '".$k."'";
		$query .= " ".$where_enable;
		$query .= " group by c.idx ";
		$query .= " order by f_count desc".$limit;

		$res = mysql_query($query,$this->conn) or die ("getSearchContents select query error!!");

		if( @mysql_affected_rows() > 0 ) {
			while( $row = mysql_fetch_array($res) ) {
				$result[] = $row;
			}
			return $result;
		}

		return $result;
	}

	function getSearchContentsCount($c_type,$k,$c,$d,$enable="") {
		$where_enable = ""; $where_temp = "";
		if( $enable != "" ) $where_enable = " and c.f_enable='".$enable."'";

		$query = "select c.idx from contents_search_status as s, contents as c ";
		$query .= " where s.c_idx = c.idx and c.c_type = '".$c_type."' and c.f_count > 0 and s.c='".$c."' and s.d = '".$d."' and s.search_word = '".$k."'";
		$query .= " ".$where_enable;
		$query .= " group by c.idx ";

		$res = mysql_query($query,$this->conn) or die ("getSearchContentsCount select query error!!");
		$row = mysql_fetch_array($res);

		return @mysql_affected_rows();
	}

	function getFavoriteContents($c_type, $s=0, $len=0, $enable="" ) {
		$where_enable = "";
		if( $enable != "" ) $where_enable = " and f_enable='".$enable."'";

		$result = array();
		$limit = "";
		if( $len > 0 ) $limit = " limit ".$s.",".$len;
		else if( $s > 0 ) $limit = " limit ".$s;

		$query = "select * from contents where c_type='".$c_type."' and f_count > 0 ".$where_enable." order by f_count desc".$limit;
		$res = mysql_query($query,$this->conn) or die ("getFavoriteDatas select query error!!");

		if( @mysql_affected_rows() > 0 ) {
			while( $row = mysql_fetch_array($res) ) {
				$result[] = $row;
			}
			return $result;
		}

		return $result;
	}

	function getFavoriteContentsCount($c_type,$enable="") {
		$where_enable = "";
		if( $enable != "" ) $where_enable = " and f_enable='".$enable."'";

		$query = "select count(idx) as cnt from contents where c_type='".$c_type."' and f_count > 0 ".$where_enable;
		$res = mysql_query($query,$this->conn) or die ("getFavoriteContentsCount select query error!!");
		$row = mysql_fetch_array($res);

		return $row['cnt'];
	}

	function getMyFavoriteContents($type, $m_idx, $keyword='', $s=0, $len=0 ) {
		$result = array();

		if( $keyword != '' ) $where_temp = " and ( f.search_word = '".$keyword."'  or tags like '%".$keyword."%' ) ";
		$limit = "";
		if( $len > 0 ) $limit = " limit ".$s.",".$len;
		else if( $s > 0 ) $limit = " limit ".$s;

		$query = "select *, f.idx as f_idx from favorites as f, contents as c ";
		$query .= " where f.m_idx = ".$m_idx." and c.c_type='".$type."' and f.c_idx = c.idx ".$where_temp." order by f_idx desc".$limit;
		$res = mysql_query($query,$this->conn) or die ("getMyFavoriteContents select query error!!");

		if( @mysql_affected_rows() > 0 ) {
			while( $row = mysql_fetch_array($res) ) {
				$result[] = $row;
			}
			return $result;
		}

		return $result;
	}

	function getMyFavoriteContentsCount($type, $m_idx, $keyword='') {

		if( $keyword != '' ) $where_temp = " and ( search_word = '".$keyword."' or tags like '%".$keyword."%' ) ";

		$query = "select count(c.idx) as cnt from favorites as f, contents as c ";
		$query .= " where m_idx = ".$m_idx." and c_type='".$type."' and f.c_idx = c.idx ".$where_temp;

		$res = mysql_query($query,$this->conn) or die ("getMyFavoriteContentsCount select query error!!");
		$row = mysql_fetch_array($res);

		return $row['cnt'];
	}

	function getServerList()
	{
		$result = array();

		$query = "select c_type, s_name ";
		$query .= " from contents group by s_name order by c_type asc, s_name asc";
		$res = mysql_query($query,$this->conn) or die ("getServerList select query error!!");

		if( @mysql_affected_rows() > 0 ) {
			while( $row = mysql_fetch_array($res) ) {
				$result[] = $row;
			}
			return $result;
		}

		return $result;
	}
}
?>
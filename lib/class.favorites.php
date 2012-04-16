<?
class clsFavorites {
	var $conn;

	function __construct( $conn ) {
		$this->conn = $conn;
	}

	function save($array) {
		$result = array();

		if( $array['c_idx'] != "" && $array['m_idx'] != "" ) {

			$query = "insert into favorites ( m_idx, c_idx, search_word, tags, regdate )";
			$query .= " values ( ".$array['m_idx'].", ".$array['c_idx'].", '".$array['search_word']."', '".$array['tags']."', now() )";
			$res = mysql_query($query,$this->conn) or die ("insert favorite query error!!");

			if( $res ) {
				$result['r'] = "success";
				$result['idx'] = mysql_insert_id();
				$result['c_idx'] = $array['c_idx'];
				$result['msg'] = "Content has been added to favorites.";

				$tags = explode (",", $array['tags']);
				for( $i=0; $i < count($tags); $i++ ) {
					$tag = trim($tags[$i]);		if( $tag == "" ) continue;
					$query = "insert into tags set f_idx = ".$result['idx'].", m_idx = ".$array['m_idx'].", tag = '".$tag."'";
					$query .= " ON DUPLICATE KEY UPDATE f_idx = ".$result['idx'].", m_idx = ".$array['m_idx'].", tag = '".$tag."'";
					mysql_query($query,$this->conn) or die ("insert favorite tag query error!!");
				}

			} else {
				$result['r'] = "error";
				$result['msg'] = "Favorite failed to add content.";
			}
		} else {
			$result['r'] = 'error';
			$result['msg'] = "Enter the information is incorrect favorites.";
		}

		return $result;
	}

	function modify($idx, $m_idx, $arr) {
		$result = array();

		$i = 0;
		while(list($key,$val) = each($arr)) {
			if ($i==0) $arrVal = $key ."='" . $val . "'";
			else $arrVal .= "," . $key ."='" . $val . "'" ;

			if( $key == 'tags' ) {
				$query = "delete from tags where  f_idx = ".$idx." and m_idx = ".$m_idx;
				mysql_query($query,$this->conn) or die ("delete favorite tag query error!!");

				$tags = explode (",", $val);
				for( $i=0; $i < count($tags); $i++ ) {
					$tag = trim($tags[$i]);		if( $tag == "" ) continue;
					$query = "insert into tags set f_idx = ".$idx.", m_idx = ".$m_idx.", tag = '".$tag."'";
					mysql_query($query,$this->conn) or die ("insert favorite tag query error!!");
				}
			}

			$i++ ;
		}
		$q = "update favorites set $arrVal where idx =".$idx;
		$res = mysql_query($q,$this->conn) or die ("modify favorite query error!!");

		if( $res ) {
			$result['r'] = 'success';
			$result['msg'] = "정보가 수정되었습니다..";
		} else {
			$result['r'] = 'error';
			$result['msg'] = "정보 수정에 실패하였습니다.";
		}

		return $result;
	}

	function existFavorite($midx, $cidx) {
		$query = "select idx from favorites where m_idx = ".$midx." and c_idx = ".$cidx." limit 1";
		$res = mysql_query($query,$this->conn) or die ("select existFavorite query error!!");

		if( @mysql_affected_rows() > 0 ) {
			$row = mysql_fetch_array($res);
			return $row['idx'];
		} else {
			return false;
		}
	}

	function getData($idx) {
		$query = "select * from favorites where idx = ".$idx;
		$res = mysql_query($query,$this->conn) or die ("getFavorite select query error!!");

		if( @mysql_affected_rows() > 0 ) {
			$row = mysql_fetch_array($res);
			return $row;
		} else {
			return NULL;
		}
	}

	function getDatasFromContentIdx($cidx) {
		$result = array();

		$query = "select * from favorites where c_idx = ".$cidx;
		$res = mysql_query($query,$this->conn) or die ("getFavorite select query error!!");

		if( @mysql_affected_rows() > 0 ) {
			while( $row = mysql_fetch_array($res) ) {
				$result[] = $row;
			}
			return $result;
		}

		return $result;
	}

	function getDatasFromMemberIdx($midx) {
		$result = array();

		$query = "select * from favorites where m_idx = ".$midx;
		$res = mysql_query($query,$this->conn) or die ("getFavorite select query error!!");

		if( @mysql_affected_rows() > 0 ) {
			while( $row = mysql_fetch_array($res) ) {
				$result[] = $row;
			}
		}

		return $result;
	}

	function delDatasFromContentIdx( $cidx ) {
		$result = array();

		$query = "delete from favorites where c_idx = ".$cidx;
		$res = mysql_query($query,$this->conn) or die ("del Data From MemberIdx query error!!");

		if( $res ) {
			$result['r'] = 'success';
			$result['cnt'] = $res;
			$result['msg'] = "삭제 성공하였습니다.";
		} else {
			$result['r'] = 'error';
			$result['msg'] = "삭제 실패하였습니다.";
		}
		return $res;
	}

	function delDatasFromMemberIdx( $midx ) {
		$result = array();

		$query = "delete from tags where m_idx = ".$midx;
		$res = mysql_query($query,$this->conn) or die ("del tag Data From MemberIdx query error!!");

		$query = "delete from favorites where m_idx = ".$midx;
		$res = mysql_query($query,$this->conn) or die ("del Data From MemberIdx query error!!");

		if( $res ) {
			$result['r'] = 'success';
			$result['cnt'] = $res;
			$result['msg'] = "삭제 성공하였습니다.";
		} else {
			$result['r'] = 'error';
			$result['msg'] = "삭제 실패하였습니다.";
		}
		return $res;
	}

	function delData( $idx, $m_idx ) {
		$result = array();
		$data = $this->getData($idx);

		$query = "delete from tags where f_idx = ".$idx." and m_idx=".$m_idx;
		$res = mysql_query($query,$this->conn) or die ("del tag Data From MemberIdx query error!!");

		$query = "delete from favorites where idx = ".$idx." and m_idx=".$m_idx;
		$res = mysql_query($query,$this->conn) or die ("del Data From MemberIdx query error!!");

		if( $res ) {
			$result['c_idx'] = $data['c_idx'];
			$result['r'] = 'success';
			$result['msg'] = "삭제 성공하였습니다.";
		} else {
			$result['r'] = 'error';
			$result['msg'] = "삭제 실패하였습니다.";
		}

		return $result;
	}

	function getMyTagList($m_idx) {
		$result = array();

		$query = "SELECT m_idx, tag, count(tag) as cnt";
		$query .= " FROM tags WHERE m_idx = ".$m_idx;
		$query .= " group by tag order by cnt desc, tag";

		$res = mysql_query($query,$this->conn) or die ("getTagList query error!!");

		if( @mysql_affected_rows() > 0 ) {
			while( $row = mysql_fetch_array($res) ) {
				$result[] = $row;
			}
		}

		return $result;
	}
}
?>
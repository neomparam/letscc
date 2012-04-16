<?
class pageSet
{
	var $nowPage = 1;
	var $BlockSize = 10;
	var $PageSize = 10;
	var $start_num;
	var $end_num;
	var $st_limit;
	var $totalRecord;
	var $tails;

	function pageSet($nowPage, $BlockSize,$PageSize,$totalRecord,$arr="")
	{
		$this->nowPage = $nowPage;
		$this->BlockSize= $BlockSize;
		$this->PageSize = $PageSize;
		$this->totalRecord = $totalRecord;

		if($this->nowPage % $this->BlockSize == 0){     
			 $this->start_num = $this->nowPage - $this->BlockSize + 1;
		}else{
			 $this->start_num = floor($this->nowPage/$this->BlockSize)*$this->BlockSize + 1;
		}

		$this->end_num = $this->start_num + $this->BlockSize - 1; 

		if($this->nowPage == 1){
		   $this->st_limit = 0;
		}else{
		   $this->st_limit = $this->nowPage * $this->PageSize - $this->PageSize;
		}

		$this->before_block = $this->start_num - 1;
		$this->next_block = $this->end_num + 1;
		$this->end_page = ceil($this->totalRecord / $this->PageSize);

		if(is_array($arr))
			while(list($key,$val) = each($arr)) $tails .= "$key=$val&";
			$this->tails = $tails;
	}

	function getLimitQuery()
	{
		return " limit $this->st_limit,$this->PageSize";
	}

	function returnPageUrl($url, $arr) {
		if(is_array($arr))
			while(list($key,$val) = each($arr)) $tails_temp .= "$key=$val&";

		return $url."?".$this->tails.$tails_temp;
	}

	function getPage($start_img="",$next_img="",$before_img="",$end_img="") 
	{
		if( $this->totalRecord <= 0 ) return "";

		echo "
		<style type='text/css'>
			.classPaging{ overflow:hidden; float:right; text-align:center;  }
			.classPaging li{ float:left; padding:0 3px;}
		</style>";

		if( $start_img == "" )
			$start_img_temp ="<font size='3'>◁◁</font>";
		else
			$start_img_temp = "<img src=$start_img border=0 alt='1' />";
		if( $next_img == "" )
			$next_img_temp ="<font size='3'>◁</font>";
		else
			$next_img_temp = "<img src=$next_img border=0 alt='1' />";

		if( $before_img == "" )
			$before_img_temp ="<font size='3'>▷</font>";
		else
			$before_img_temp = "<img src=$before_img border=0 alt='1' />";

		if( $end_img == "" )
			$end_img_temp ="<font size='3'>▷▷</font>";
		else
			$end_img_temp = "<img src=$end_img border=0 alt='1' />";

		$outPrint = "<ol class='classPaging'>";

		if( $this->nowPage != 1){
			$outPrint .= "<li><a href='$_SERVER[PHP_SELF]?nowPage=1&$this->tails'>$start_img_temp</a></li>";	
		} else {
			$outPrint .= "<li>$start_img_temp</li>";
		}

		  if( $this->start_num > 1){ 
				$outPrint .= "<li><a href='$_SERVER[PHP_SELF]?nowPage=$this->before_block&$this->tails'>$next_img_temp</a></li>";
		  } else { 
				$outPrint .= "<li>$next_img_temp</li>";
		  }	

		for($i=$this->start_num; $i<=$this->end_num; $i++) {
			if(ceil($this->totalRecord/$this->PageSize) >= $i) {
				if($this->nowPage == $i) {	 
					$outPrint .= "<li><b>$i</b></li>";
				} else	{	//echo $this->tails;
					$outPrint .= "<li><a href='$_SERVER[PHP_SELF]?nowPage=$i&$this->tails'>$i</a></li>";
		}	}	}	


		if($this->end_num * $this->PageSize < $this->totalRecord) {	
			$outPrint .= "<li><a href='$_SERVER[PHP_SELF]?nowPage=$this->next_block&$this->tails'>$before_img_temp</a></li>";
		} else {	
			$outPrint .= "<li>$before_img_temp</li>";
		}	

		if( $this->end_page > $this->nowPage ){ 
			$outPrint .= "<li><a href='$_SERVER[PHP_SELF]?nowPage=$this->end_page&$this->tails'>$end_img_temp</a></li>";
		} else { 
			$outPrint .= "<li>$end_img_temp</li>";
		}	

		$outPrint .= "</ol>";

		return $outPrint;
	}
}
?>
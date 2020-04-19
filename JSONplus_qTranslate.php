<?php
namespace JSONplus;
require_once('JSONplus.php');
if(!defined("LANGUAGE")){define("LANGUAGE", "en");}

class qTranslate extends \JSONplus {
	public function import($str=NULL, $setting=array()){
		$ar = array();
		$str = preg_replace('#[\[][\#]([a-zA-Z0-9_\-]{1,32})([!\*\?\~]{0,})[\]]#i', '[:][:#\\2]\\1[:]', $str);
		$set = explode('[:', $str);
		$i = 0;
		foreach($set as $j=>$s){
			if($j == 0 && strlen($s)>0 ){
				$ar[$i++] = $s;
			}
			elseif(substr($s, 0, 1) == ']' && strlen($s)>1){
				$i++;
				$ar[$i++] = substr($s, 1);
			}
			elseif(preg_match('#^([a-zA-Z_]{1,6}|[\#])([!\*\?\~]{0,})\]#i', $s, $buffer)){
			if($buffer[1] == '#'){ $ar[$i]['$ref'] = substr($s, strlen($buffer[0])); }
				else{ $ar[$i][$buffer[1]] = substr($s, strlen($buffer[0])); }
				if(isset($buffer[2])){ for($i=strlen($buffer[2]);$i>0;$i--){ switch($buffer[2]{$i-1}){
					case '*': $ar[$i][':default'] = $buffer[1]; break;
					case '!': $ar[$i][':force'] = $buffer[1]; break;
					case '?': $ar[$i][':concept'][] = $buffer[1]; break;
					case '~': $ar[$i][':autotranslated'][] = $buffer[1]; break;
				} } }
			}
		}
		$this->_ = $ar;
		return $ar;
	}
	public function export($setting=array()){
		$str = NULL;
		foreach($this->_ as $i=>$block){
			if(is_array($block)){
				foreach($block as $j=>$l){
					$flag = NULL;
					if(isset($block[':default']) && $block[':default'] == $j){ $flag .= '*'; }
					if(isset($block[':force']) && $block[':force'] == $j){ $flag .= '!'; }
					if(isset($block[':concept']) && is_array($block[':concept']) && in_array($j, $block[':concept']) ){ $flag .= '?'; }
					if(isset($block[':autotranslated']) && is_array($block[':autotranslated']) && in_array($j, $block[':autotranslated']) ){ $flag .= '~'; }
					if($j == '#'){
						$str .= '[:#'.$flag.']'.$l;
					}
					elseif(substr($j, 0, 1) != ':'){
						$str .= '[:'.$j.$flag.']'.$l;
					}
				}
				$str .= '[:]';
			}
			else{
				$str .= $block;
			}
		}
		$str = preg_replace('#([\[][\:][\]])?[\[][\:][\#]([!\*\?\~]{0,})[\]]([^\[]+)([\[][\:][\]])?#i', '[#\\3\\2]', $str);
		return $str;
	}
	public function process($setting=array()){
		$l = (is_string($setting) ? $setting : (isset($setting['language']) ? $setting['language'] : (isset($setting['l']) ? $setting['l'] : LANGUAGE)));
		$in = $this->_;
		/*fix*/ if(is_string($in)){ $in = $this->import($in); }
		$str = NULL;
		foreach($in as $i=>$n){
			if(is_string($n)){ $str .= $n; }
			elseif(is_array($n)){
				if(isset($n[':force']) && isset($n[$n[':force']])){ $str .= $n[$n[':force']]; }
				elseif(isset($n[$l])){ $str .= $n[$l]; }
				elseif(isset($n[':default']) && isset($n[$n[':default']])){ $str .= $n[$n[':default']]; }
				else{ $str .= reset($n); }
			}
		}
		return $str;
	}
}
?>

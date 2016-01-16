<?php

	function tree($array,$parentId = 0,$level = 0,$symbol='-'){
        $data = array();
        foreach($array as $value){
            if($value['parent_id'] == $parentId){
				if($parentId != 0){
					$value['html'] = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;',$level);
					$value['html'] .= '|';
					$value['html'] .= str_repeat($symbol,$level);
				}
				$sub = tree($array,$value['id'],$level+1);
				$data[] = $value;
                $data = array_merge($data,$sub);
            }
        }
        return $data;
    }
	
	function validatorError2Str($messages){
		$arrMessages = array();
		$strMessages = '';
		if(is_object($messages)){
			$arrMessages = (array)$messages;
			$arrMessages = $arrMessages['*messages'];
			foreach($messages as $key=>$value){
				$strMessages .= $key.':'.$value."<br>";
			}
		}
		return $strMessages;
	}

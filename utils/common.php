<?php
function setUrlSearchQuery($queryString, $newParam, $newValue) {
    $arr = explode('&', $queryString);
    if(count($arr) === 0) { // nếu queryString đang trống thì chỉ cần tạo queryString
        $queryString = "$newParam=$newValue";
    } else { // nếu không
        if(stripos($queryString, $newParam) !== false) { // nếu queryString đã chứa newParam
            $queryString = ''; // set queryString rỗng
            foreach ($arr as $keyValue) { // lặp qua các keyValue vd: categoryId=1;
                $keyValue = trim($keyValue);
                $keyValueArr = array_filter(explode('=', $keyValue)); // tách ra key và value
                if(count($keyValueArr) < 2) continue;
                else {
                    $key = $keyValueArr[0];
                    if($key !== $newParam) {
                        if(strlen($queryString) === 0) $queryString .= $keyValue;
                        else $queryString .= "&$keyValue";
                    }
                }
            }
            $queryString .= "&$newParam=$newValue";
        } else { // nếu chưa thì chỉ cần thêm vào
            $queryString .= "&$newParam=$newValue";
        }
    }
    return $queryString;
}

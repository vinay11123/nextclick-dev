<?php

function toPaise($value) {
    $paise = (int) ((float) $value *100); 
	return $paise;
}

function substract($value1, $value2, $returnFormatted= true) {
    $result = $value1 - $value2;
    if($returnFormatted)
	    return number_format((float)$result, 2, '.', '');
    return $result;
}

function add($value1, $value2, $returnFormatted= true) {
    $result = $value1 + $value2;
    if($returnFormatted)
	    return number_format((float)$result, 2, '.', '');
    return $result;
}

function toRupees($value, $returnFormatted= true) {
    $result = (float) $value/100; 
	if($returnFormatted)
	    return number_format((float)$result, 2, '.', '');
    return $result;
}

<?php  
	//$string：加密字符串,$decrypt：加解密类型,$key:密钥
function encryptDecrypt( $string, $decrypt,$key='yadongtextile'){   
    if($decrypt){   
    	//解密
        $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, md5(md5(md5($key)))), "12");   
        return $decrypted;   
    }else{   
    	//加密
        $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5(md5($key)))));   
        return $encrypted;   
    }   
}   
?>  
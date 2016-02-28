<?php
class remotePeon {
	public static function getFile($url,$timeout=5,$postData,$proxy=false) {
		try {
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_TIMEOUT,$timeout); //sekunden
			
			if(!empty($postData) && isset($postData)) {
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
			}

			if ($proxy) {
				curl_setopt($curl, CURLOPT_PROXY, "http://192.168.2.106");
				curl_setopt($curl, CURLOPT_PROXYPORT, "3128");
			} else {
				curl_setopt($curl, CURLOPT_PROXY, "");
				curl_setopt($curl, CURLOPT_PROXYPORT, "");
			}
			$entireFile=curl_exec($curl);
			
			if(curl_errno($curl)) {
				die('remoteException curl_error:' . curl_error($curl) . '(url:'.$url.')');
			}
			curl_close($curl);
			return $entireFile;
		} catch (Exception $e) {
			throw new remoteException($e, ERR_IN_REMOTE_CALL);
			return $e;
		}
	}
}
?>
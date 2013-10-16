<?php 
	class XRI{
		public static function request($url=null,$dados = array()){
			if(!$url || !is_array($dados)){
				return false;
			}
			$cURL = curl_init($url);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);

			$valid = array(
				'nome' => 'SEUNOME',
				'key' => 'SUAKEY'
			);

			curl_setopt($cURL, CURLOPT_POST, true);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, array_merge($valid,$dados));
			// curl_setopt($cURL, CURLOPT_REFERER, 'http://www.meusite.com.br/contato.php');
			$resultado = curl_exec($cURL);
			$resposta = curl_getinfo($cURL, CURLINFO_HTTP_CODE);
			curl_close($cURL);

			if($resposta == '404'){
				return false;
			}
			else{
				return $resultado;
			}

			curl_close($cURL);
		}
	}
?>
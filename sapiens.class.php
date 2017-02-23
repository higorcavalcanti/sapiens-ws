<?php
class Sapiens {

	private $curl;
	private $cookies;
	
	public function __construct() {
		$this->curl = curl_init();
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->curl, CURLOPT_ENCODING, 'UTF-8');
		curl_setopt($this->curl, CURLOPT_FRESH_CONNECT, TRUE);
		curl_setopt($this->curl, CURLOPT_VERBOSE, true);

	}
	
	public function __destruct() {
		$this->sair();
	}
	
	public function login($user, $pass) {
							
		curl_setopt($this->curl, CURLOPT_URL, "https://sapiens.dti.ufv.br/sapiens_crp/CheckLogin.asp");
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($this->curl, CURLOPT_HEADER, true);
		curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);
		
		$exec = curl_exec($this->curl);		
		//======================[ COOKIE ]=================
		$header_size = curl_getinfo($this->curl, CURLINFO_HEADER_SIZE);
		$header      = substr($exec, 0, $header_size);
		preg_match_all("/^Set-cookie: (.*?);/ism", $header, $cookies);
		foreach( $cookies[1] as $cookie ){
			$buffer_explode = strpos($cookie, "=");
			$this->cookies[ substr($cookie,0,$buffer_explode) ] = substr($cookie,$buffer_explode+1);
		}		
		if( count($this->cookies) > 0 ){
			$cookieBuffer = array();
			foreach(  $this->cookies as $k=>$c ) $cookieBuffer[] = "$k=$c";
			curl_setopt($this->curl, CURLOPT_COOKIE, implode("; ",$cookieBuffer) );
		}
		//=================================================
		
		
		$loginData = array(
			'Status' => 'FormSubmetido',
			'Pagina' => '/sapiens_crp/CheckLogin.asp',
			'Usuario' => $user,
			'Senha' => $pass
		);		
		curl_setopt($this->curl, CURLOPT_URL, "https://sapiens.dti.ufv.br/sapiens_crp/CheckLogin.asp");
		curl_setopt($this->curl, CURLOPT_POST, true);
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($loginData));
		curl_setopt($this->curl, CURLOPT_HEADER, false);
		curl_setopt($this->curl, CURLINFO_HEADER_OUT, false);
		
		$exec = curl_exec($this->curl);

		if(curl_error($this->curl)) {
			return array(
				'logado' => false,
				'erro' => 'Erro interno do servidor (não do sapiens)',
				'detalhes' => curl_error($this->curl)
			);
		}
		
		$dom = new DOMDocument();
		@$dom->loadHTML($exec);
		@$xpath = new DOMXpath($dom);
		$erro = $xpath->query('//span[contains(@class, "erro")]'); //instance of DOMNodeList

		if($erro->length) {
			return array(
				'logado' => false,
				'erro' => $erro->item(0)->nodeValue
			);
		}
		
		return array(
			'logado' => true
		);
	}
	
	public function notas() {	
		curl_setopt($this->curl, CURLOPT_URL, 'https://sapiens.dti.ufv.br/sapiens_crp/aluno/avaliacoes.asp');
		curl_setopt($this->curl, CURLOPT_POST, false);
				
		$exec = curl_exec($this->curl);
		
		$notas = new Notas( $exec );
		return $notas->toJson();
	}
	
	
	public function horarios() {	
		curl_setopt($this->curl, CURLOPT_URL, 'https://sapiens.dti.ufv.br/sapiens_crp/aluno/disc_matr.asp');
		curl_setopt($this->curl, CURLOPT_POST, false);
				
		$exec = curl_exec($this->curl);
		
		$horarios = new Horarios( $exec );
		return $horarios->toJson();
	}
	
	public function sair() {
		curl_setopt($this->curl, CURLOPT_URL, 'https://sapiens.dti.ufv.br/sapiens_crp/sair.asp');
		curl_setopt($this->curl, CURLOPT_POST, false);
		
		$exec = curl_exec($this->curl);
		
		curl_close($this->curl);		
		return $exec;
	}
}
?> 
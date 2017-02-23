<?php
class Horarios {
	
	private $dom;
	private $html;
	private $horarios;
	private $disciplinas;
	
	public function __construct($html) {
		
		$this->html = $html;
		
		$this->dom = new DOMDocument();
		@$this->dom->loadHTML($this->html);
		
		$this->parse();
	}	
	
	public function toJson() {
		$arr = array(
			'disciplinas' => $this->disciplinas,
			'horarios' => $this->horarios,
		);
		return json_encode($arr);
	}
	
		
	private function parse() {
		
		$this->parseDisciplinas();
		$this->parseHorarios();
	}
	
	private function parseDisciplinas() {
		$INDEX_TABELA_DISCIPLINAS = 3;
		$table = $this->dom->getElementsByTagName('table')->item($INDEX_TABELA_DISCIPLINAS);
		for($i = 1; $i < $table->getElementsByTagName('tr')->length - 1; $i++) {
			$tr = $table->getElementsByTagName('tr')->item($i);
			
			$codigo = $tr->getElementsByTagName('td')->item(0)->getElementsByTagName('font')->item(0)->nodeValue;
			$nome = $tr->getElementsByTagName('td')->item(0)->getElementsByTagName('font')->item(1)->nodeValue;
			$creditos = $tr->getElementsByTagName('td')->item(2)->nodeValue;
			$pratica = $tr->getElementsByTagName('td')->item(3)->nodeValue;
			$teorica = $tr->getElementsByTagName('td')->item(4)->nodeValue;
			
			$this->disciplinas[] = array(
				'codigo' => $codigo,
				'nome' => $nome,
				'creditos' => $creditos,
				'turma' => array(
					'pratica' => $pratica,
					'teorica' => $teorica
				)
			);
		}
		//var_dump($this->disciplinas);
	}	
	
	private function parseHorarios() {

		$INDEX_TABELA_HORARIOS = 5;
		$PRIMEIRO_TR_HORARIOS = 1;
		
		$INDEX_TD_HORA = 0;
		$INDEX_TD_SEGUNDA = 1;
		$INDEX_TD_TERCA = 2;
		$INDEX_TD_QUARTA = 3;
		$INDEX_TD_QUINTA = 4;
		$INDEX_TD_SEXTA = 5;
		$INDEX_TD_SABADO = 6;
		
		$table = $this->dom->getElementsByTagName('table')->item($INDEX_TABELA_HORARIOS);
		for($i = $PRIMEIRO_TR_HORARIOS; $i < $table->getElementsByTagName('tr')->length; $i++) {
			$tr = $table->getElementsByTagName('tr')->item($i);
			
			$this->horarios[] = array(
				'hora' => $tr->getElementsByTagName('td')->item($INDEX_TD_HORA)->nodeValue,
				'segunda' => array(
					'codigo' => $tr->getElementsByTagName('td')->item($INDEX_TD_SEGUNDA)->getElementsByTagName('font')->item(0)->nodeValue,
					'sala' => $tr->getElementsByTagName('td')->item($INDEX_TD_SEGUNDA)->getElementsByTagName('font')->item(1)->nodeValue,
				),
				'terca' => array(
					'codigo' => $tr->getElementsByTagName('td')->item($INDEX_TD_TERCA)->getElementsByTagName('font')->item(0)->nodeValue,
					'sala' => $tr->getElementsByTagName('td')->item($INDEX_TD_TERCA)->getElementsByTagName('font')->item(1)->nodeValue,
				),
				'quarta' => array(
					'codigo' => $tr->getElementsByTagName('td')->item($INDEX_TD_QUARTA)->getElementsByTagName('font')->item(0)->nodeValue,
					'sala' => $tr->getElementsByTagName('td')->item($INDEX_TD_QUARTA)->getElementsByTagName('font')->item(1)->nodeValue,
				),
				'quinta' => array(
					'codigo' => $tr->getElementsByTagName('td')->item($INDEX_TD_QUINTA)->getElementsByTagName('font')->item(0)->nodeValue,
					'sala' => $tr->getElementsByTagName('td')->item($INDEX_TD_QUINTA)->getElementsByTagName('font')->item(1)->nodeValue,
				),
				'sexta' => array(
					'codigo' => $tr->getElementsByTagName('td')->item($INDEX_TD_SEXTA)->getElementsByTagName('font')->item(0)->nodeValue,
					'sala' => $tr->getElementsByTagName('td')->item($INDEX_TD_SEXTA)->getElementsByTagName('font')->item(1)->nodeValue,
				),
				'sabado' => array(
					'codigo' => $tr->getElementsByTagName('td')->item($INDEX_TD_SABADO)->getElementsByTagName('font')->item(0)->nodeValue,
					'sala' => $tr->getElementsByTagName('td')->item($INDEX_TD_SABADO)->getElementsByTagName('font')->item(1)->nodeValue
				)
			);
		}		
	}	
}
?> 
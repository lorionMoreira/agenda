<?php

namespace App\Support;

class ResultadoPesquisa
{
	
	const DUPLICATA = 'CPF DUPLICATA';

	public $resultado;

	public $cpf;

	public $obs;

	public $pessoa;

	public $assistido;

	public function __construct($resultado, $cpf, $obs = null, $pessoa = null, $assistido = null)
	{
		$this->resultado = $resultado;
		$this->cpf = $cpf;
		$this->obs = $obs;
		$this->pessoa = $pessoa;
		$this->assistido = $assistido;
	}
}
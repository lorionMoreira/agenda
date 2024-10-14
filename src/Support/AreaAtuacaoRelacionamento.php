<?php

namespace App\Support;

use Cake\ORM\Query;
use App\Support\RelacionamentoInterface;

class AreaAtuacaoRelacionamento  implements RelacionamentoInterface
{
	public function contain()
	{
		$relacionamentos = [
			'AcoesRelacionada' => function(Query $q)
				{
					return $q->select(['id', 'nome', 'area_atuacao_id']);
				}
		];		
		
		return $relacionamentos;
	}

}
<?php

namespace IFphp\BD\Expressoes;

trait Filtro {

    protected $where = '';
    protected $dados = [];
    protected $agrupado = false;
    protected $agrupadoTipo;
    protected $operadores = ['=', '!=', '>', '<', '>=', '<=', '<>'];
    protected $tipo = 'AND';
    protected $orderBy = [];
    protected $limit = '';
    protected $offset = '';
    protected $groupby = '';
    protected $having = '';

    public function where() 
    {
        $dados = func_get_args();
        $this->adicionarClausula($dados);
        return $this;
    }

    public function orwhere() 
    {
        $dados = func_get_args();
        $this->adicionarClausula($dados, 'OR');
        return $this;
    }

    protected function adicionarClausula(Array $dados, $tipo = 'AND') 
    {

        if (count($dados) == 2) {

            $coringa = $this->adicionarDados($dados[0], $dados[1]);
            $clausula = $dados[0] . " = " . $coringa;
            $this->montaWhere($clausula, $tipo);
        } elseif (count($dados) == 3) {

            if (in_array($dados[1], $this->operadores)) {
                $coringa = $this->adicionarDados($dados[0], $dados[2]);
                $clausula = $dados[0] . " " . $dados[1] . " " . $coringa;
                $this->montaWhere($clausula, $tipo);
            } else {
                throw new \Exception('Operador Inválido');
            }
        } elseif (count($dados) == 1 AND $dados[0] instanceof \Closure) {

            $this->agrupado = true;
            $this->agrupadoTipo = $tipo;
            call_user_func($dados[0]);
            $this->where .= ")";
        }
    }

    public function between($campo, $valor1, $valor2, $tipo = 'AND', $not = ' ') 
    {
        $coringa1 = $this->adicionarDados($campo, $valor1);
        $coringa2 = $this->adicionarDados($campo, $valor2);

        $clausula = "$campo" . $not . "BETWEEN " . $coringa1 . " AND " . $coringa2;
        $this->montaWhere($clausula, $tipo);

        return $this;
    }

    public function orBetween($campo, $valor1, $valor2) 
    {
        $this->between($campo, $valor1, $valor2, 'OR');
        return $this;
    }

    public function notBetween($campo, $valor1, $valor2) 
    {
        $this->between($campo, $valor1, $valor2, 'AND', ' NOT ');
    }

    public function orNotBetween($campo, $valor1, $valor2) 
    {
        $this->between($campo, $valor1, $valor2, 'OR', ' NOT ');
    }

    public function in($campo, Array $valores, $tipo = 'AND', $not = ' ') 
    {
        $coringas = [];
        foreach ($valores as $v) {
            $coringas[] = $this->adicionarDados($campo, $v);
        }
        $c = \implode(',', $coringas);
        $clausula = $campo . $not . "IN" . " ($c)";

        $this->montaWhere($clausula, $tipo);
        return $this;
    }
    
    
    public function notIn($campo,Array $valores)
    {
        $this->in($campo,$valores,'AND',' NOT ');
        return $this;
    }
    
    public function orIn($campo,Array $valores)
    {
         $this->in($campo,$valores,'OR',' NOT ');
         return $this;
    }
    
    public function like($campo,$valor,$formato='%t%',$tipo='AND')
    {
    
        $coringa = $this->adicionarDados($campo, $valor);
        
        switch($formato){
            case '%t%':
                $coringa = '%'.$coringa.'%';
            break;
            case '%t':
                $coringa = '%'.$coringa;
            break;
            case 't%':
                $coringa = $coringa.'%';
            break;
        }
        
        $clausula = $campo." LIKE ".$coringa;
        $this->montaWhere($clausula,$tipo);
        
        return $this;        
        
    }
    
    public function orLike($campo,$valor,$formato='%t%')
    {
        $this->like($campo, $valor, $formato, 'OR');
        return $this;
    }
    
    
    public function orderBy($campo,$tipoOrdem = 'ASC')
    {
        $tipoOrdem = strtoupper($tipoOrdem);
        if($tipoOrdem != 'DESC'){
          $tipoOrdem = 'ASC';  
        }
        
        $this->orderBy[] = $campo." ".$tipoOrdem; 
        return $this;
        
    }
    
    public function limit($quantidade,$inicio = 0)
    {
        $this->limit = $quantidade;    
        $this->offset = $inicio;
    }
    
    public function groupBy()
    {
        $this->groupby = implode(',', func_get_args());
        return $this;        
    }
    
    public function having($campo,$op,$valor)
    {
        $this->having = $campo." ".$op." ".$valor;
    }
    
    
    protected function montaWhere($clausula, $tipo = 'AND') 
    {
        if (empty($this->where)) {
            if ($this->agrupado) {
                $clausula = "(" . $clausula;
                $this->agrupado = false;
            }
            $this->where .= $clausula;
        } else {
            if ($this->agrupado) {
                $this->where .= " " . $this->agrupadoTipo . " (" . $clausula;
                $this->agrupado = false;
            } else {
                $this->where .= " " . $tipo . " " . $clausula;
            }
        }
    }

    protected function adicionarDados($coluna, $valor) 
    {

        $pattern = "/^(:$coluna)[[:digit:]]*$/";
        $ocorrencias = preg_grep($pattern, array_keys($this->dados));

        if (count($ocorrencias) > 0) {
            $coluna = $coluna . count($ocorrencias);
        }

        $coluna = ":" . $coluna;
        $this->dados[$coluna] = $valor;

        return $coluna;
    }
    
    

    public function getDados() 
    {
        return $this->dados;
    }

    public function getClausula() 
    {
        $clausula = [];
        
        if($this->where){
            $clausula[] = "WHERE " . $this->where;
        }
        if($this->groupby){
            $clausula[] = "GROUP BY ".$this->groupby;
        }
        if(count($this->orderBy)){
            $ordem = implode(',',$this->orderBy);
            $clausula[] = "ORDER BY " . $ordem;
        }
        if($this->limit){
            $clausula[] = "LIMIT " . $this->limit;
        }
        if($this->offset > 0){
           $clausula[] = "OFFSET " . $this->offset; 
        }
        
        
        return implode(' ',$clausula);
    }

}

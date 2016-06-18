<?php
namespace IFphp\BD\Expressoes;

use \IFphp\BD\Interfaces\Isql;

class Select implements ISql{
    
    protected $tabela;
    protected $parametros;
    protected $filtro;
    
    
         
    public function getSql() {
          return "SELECT ".$this->parametros." FROM ".$this->tabela;  
    }

}

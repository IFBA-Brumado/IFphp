<?php

namespace Ifphp\BD\Expressoes;


class InsertTest extends \PHPUnit\Framework\TestCase {
   
    
    public function testRetornoDeExpressaoInsert(){
        
        $expressao = "INSERT INTO `tabela` (campo1,campo2,campo3) VALUES (:campo1,:campo2,:campo3)";
        
        $dados = ['campo1' => 'valor1', 'campo2' => 'valor2', 'campo3' => 'valor3'];
        
        $insert = ( new \IFphp\BD\Expressoes\Insert($dados) )->tabela('tabela');
        
        $this->assertEquals($expressao, $insert->getSql());
        
    }
    
    public function testRetornoDosDadosDaExpressaoInsert(){
        
        $expressao = "INSERT INTO `tabela` (campo1,campo2,campo3) VALUES (:campo1,:campo2,:campo3)";
        $dados = ['campo1' => 'valor1', 'campo2' => 'valor2', 'campo3' => 'valor3'];
        $insert = ( new \IFphp\BD\Expressoes\Insert($dados) )->tabela('tabela');
        
        
        $resultado = [':campo1' => 'valor1', ':campo2' => 'valor2', ':campo3' => 'valor3'];
        
        
        $this->assertEquals($resultado, $insert->getDados());       
        
        
    }
    
}

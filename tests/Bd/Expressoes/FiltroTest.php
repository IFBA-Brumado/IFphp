<?php

namespace Ifphp\BD\Expressoes;

class FiltroTest extends \PHPUnit\Framework\TestCase {

    public function testRetornaWhereSimplescomIgual() {
        $filtro = $this->getMockForTrait('\IFphp\BD\Expressoes\Filtro');

        $filtro->where('id', 1);

        $this->assertEquals('WHERE id = :id', $filtro->getClausula());
        $this->assertEquals([':id' => 1], $filtro->getDados());
    }

    public function testRetornaWhereSimplescomOutroOperador() {
        $filtro = $this->getMockForTrait('\IFphp\BD\Expressoes\Filtro');

        $filtro->where('idade', '>', 15);
        $this->assertEquals('WHERE idade > :idade', $filtro->getClausula());
        $this->assertEquals([':idade' => 15], $filtro->getDados());
    }

    public function testRetornaWhereCompostoComAnd() {
        $filtro = $this->getMockForTrait('\IFphp\BD\Expressoes\Filtro');

        $filtro->where('idade', '>', 15)
                ->where('idade', '<', 50);


        $this->assertEquals('WHERE idade > :idade AND idade < :idade1', $filtro->getClausula());
    }

    public function testRetornaWhereCompostoComOr() {
        $filtro = $this->getMockForTrait('\IFphp\BD\Expressoes\Filtro');

        $filtro->where('idade', '>', 15)
                ->orwhere('idade', '<', 50);

        $this->assertEquals('WHERE idade > :idade OR idade < :idade1', $filtro->getClausula());
    }

    public function testRetornaWhereCompostoComParentese() {
        $filtro = $this->getMockForTrait('\IFphp\BD\Expressoes\Filtro');
        $filtro->where(function() use ($filtro) {
            $filtro->where('idade', '>', 15)->where('idade', '<', 50);
        });

        $this->assertEquals('WHERE (idade > :idade AND idade < :idade1)', $filtro->getClausula());
    }

    public function testRetornaWhereCompostoComParenteseAposClausula() {
        $filtro = $this->getMockForTrait('\IFphp\BD\Expressoes\Filtro');
        $filtro->where('id', 1)->where(function() use ($filtro) {
            $filtro->where('idade', '>', 15)->where('idade', '<', 50);
        });

        $this->assertEquals('WHERE id = :id AND (idade > :idade AND idade < :idade1)', $filtro->getClausula());
    }

    public function testRetornaWhereCompostoComDoisParenteses() {
        $filtro = $this->getMockForTrait('\IFphp\BD\Expressoes\Filtro');
        $filtro->where(function() use($filtro) {
                    $filtro->where('id', 1)
                    ->orwhere('nome', 'teste');
                })
                ->where(function() use ($filtro) {
                    $filtro->where('idade', '>', 15)
                    ->where('idade', '<', 50);
                });

        $this->assertEquals('WHERE (id = :id OR nome = :nome) AND (idade > :idade AND idade < :idade1)', $filtro->getClausula());
    }

    public function testRetornaWhereCompostoComOrEDoisParenteses() {
        $filtro = $this->getMockForTrait('\IFphp\BD\Expressoes\Filtro');
        $filtro->where(function() use($filtro) {
                    $filtro->where('id', 1)
                    ->orwhere('nome', 'teste');
                })
                ->orwhere(function() use ($filtro) {
                    $filtro->where('idade', '>', 15)
                    ->where('idade', '<', 50);
                });

        $this->assertEquals('WHERE (id = :id OR nome = :nome) OR (idade > :idade AND idade < :idade1)', $filtro->getClausula());
    }

    public function testRetornaWhereCompostoComParenteseEUmaExpressao() {

        $filtro = $this->getMockForTrait('\IFphp\BD\Expressoes\Filtro');
        $filtro->where(function() use($filtro) {
            $filtro->where('id', 1);
        });

        $this->assertEquals('WHERE (id = :id)', $filtro->getClausula());
    }

    public function testRetornaWhereCompostoComParenteseEUmaExpressaoBetween() {

        $filtro = $this->getMockForTrait('\IFphp\BD\Expressoes\Filtro');
        $filtro->where(function() use($filtro) {
            $filtro->between('id', 5, 10);
        });

        $this->assertEquals('WHERE (id BETWEEN :id AND :id1)', $filtro->getClausula());
    }

    public function testRetornaWhereComBetween() {
        $filtro = $this->getMockForTrait('\IFphp\BD\Expressoes\Filtro');
        $filtro->between('idade', 10, 15);

        $this->assertEquals('WHERE idade BETWEEN :idade AND :idade1', $filtro->getClausula());
    }

    public function testRetornaWhereCompostoComBetween() {
        $filtro = $this->getMockForTrait('\IFphp\BD\Expressoes\Filtro');
        $filtro->between('idade', 10, 15)->where('id', 1);

        $this->assertEquals('WHERE idade BETWEEN :idade AND :idade1 AND id = :id', $filtro->getClausula());
    }

    public function testRetornaWhereCompostoComBetWeenParentese() {

        $filtro = $this->getMockForTrait('\IFphp\BD\Expressoes\Filtro');
        $filtro->where(function() use($filtro) {
            $filtro->between('idade', 10, 15)
                    ->where('id', 1);
        });

        $this->assertEquals('WHERE (idade BETWEEN :idade AND :idade1 AND id = :id)', $filtro->getClausula());
    }

    public function testRetornaWhereComOrBetween() {

        $filtro = $this->getMockForTrait('\IFphp\BD\Expressoes\Filtro');
        $filtro->where('id', 1)->orbetween('idade', 10, 15);

        $this->assertEquals('WHERE id = :id OR idade BETWEEN :idade AND :idade1', $filtro->getClausula());
    }
    
    
    public function testRetornaWherecomIn(){
        $filtro = $this->getMockForTrait('\IFphp\BD\Expressoes\Filtro');
        $filtro->in('id',[1,2,3]);
        
        $this->assertEquals('WHERE id IN (:id,:id1,:id2)', $filtro->getClausula());
        
    }
    
    public function testRetornaWherecomLike(){
        $filtro = $this->getMockForTrait('\IFphp\BD\Expressoes\Filtro');
        $filtro->like('nome','joao');
        
        
        $this->assertEquals('WHERE nome LIKE %:nome%', $filtro->getClausula());
        
    }
    
     public function testRetornaWherecomOrLike(){
         $filtro = $this->getMockForTrait('\IFphp\BD\Expressoes\Filtro');
         $filtro->in('id',[1,2,3])->orLike('nome','joao');

        
        
        $this->assertEquals('WHERE id IN (:id,:id1,:id2) OR nome LIKE %:nome%', $filtro->getClausula());
        $this->assertEquals([':id' => 1,':id1' => 2,':id2' => 3,':nome' => 'joao'], $filtro->getDados());
    }
    
    public function testRetornaClausulacomOrderBy(){
        
         $filtro = $this->getMockForTrait('\IFphp\BD\Expressoes\Filtro');
         $filtro->orderBy('nome');
     
         $this->assertEquals('ORDER BY nome ASC', $filtro->getClausula());
         
    }
    
    public function testRetornaClausulacomOrderByDESC()
    {
        
         $filtro = $this->getMockForTrait('\IFphp\BD\Expressoes\Filtro');
         $filtro->orderBy('nome','DESC');
     
         $this->assertEquals('ORDER BY nome DESC', $filtro->getClausula());
         
    }
    
    
    public function testRetornaOrderByComMaisCampos()
    {
        
         $filtro = $this->getMockForTrait('\IFphp\BD\Expressoes\Filtro');
         $filtro->orderBy('nome','DESC')->orderBy('idade');
         
         $this->assertEquals('ORDER BY nome DESC,idade ASC', $filtro->getClausula());
        
    }
    
    public function testRetornaWhereComOrderBy()
    {
        
         $filtro = $this->getMockForTrait('\IFphp\BD\Expressoes\Filtro');
         $filtro->where('nome','joao')->orderBy('nome','DESC')->orderBy('idade');
         
         $this->assertEquals('WHERE nome = :nome ORDER BY nome DESC,idade ASC', $filtro->getClausula());
        
    }
    
    public function testRetornaLimit()
    {
        
        $filtro = $this->getMockForTrait('\IFphp\BD\Expressoes\Filtro');
        $filtro->limit(10);
        
        $this->assertEquals('LIMIT 10', $filtro->getClausula());
        
    }
    
    public function testRetornaLimitEOffset()
    {
        $filtro = $this->getMockForTrait('\IFphp\BD\Expressoes\Filtro');
        $filtro->limit(10,11);
        
        $this->assertEquals('LIMIT 10 OFFSET 11', $filtro->getClausula());
    }
    
    public function testRetornarGroupBy()
    {
        $filtro = $this->getMockForTrait('\IFphp\BD\Expressoes\Filtro');
        $filtro->groupBy('nome','idade');
        
        $this->assertEquals('GROUP BY nome,idade', $filtro->getClausula());
    }
    

}

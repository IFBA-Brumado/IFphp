<?php

namespace IFphp\Bd;

class Query {
  
    public function select()
    {
        return new \IFphp\BD\Expressoes\Select();
    }
    
    public function insert()
    {
       return new \IFphp\BD\Expressoes\Insert();
    }
    
    public function update()
    {
      return new \IFphp\BD\Expressoes\Update();
    }
    
    public function delete()
    {
      return new \IFphp\BD\Expressoes\Delete();
    }
    
    
    
    
    
}

<?php

namespace IFphp\Bd;

use PHPUnit\Framework\TestCase;

class QueryTest extends TestCase {

    public function testConstruirObjetoSelect() {

        $select = \IFphp\BD\Query::select();
        $this->assertInstanceOf(\IFphp\BD\Expressoes\Select::class, $select);
    }

    public function testConstruirObjetoInsert() {

        $insert = \IFphp\BD\Query::insert();
        $this->assertInstanceOf(\IFphp\BD\Expressoes\Insert::class, $insert);
    }

    public function testConstruirObjetoUpdate() {
        $update = \IFphp\BD\Query::update();
        $this->assertInstanceOf(\IFphp\BD\Expressoes\Update::class, $update);
    }

    public function testConstruirObjetoDelete() {

        $delete = \IFphp\BD\Query::delete();
        $this->assertInstanceOf(\IFphp\BD\Expressoes\Delete::class, $delete);
    }

}

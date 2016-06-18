<?php

namespace IFphp\BD\Expressoes;

use IFphp\BD\Interfaces\ISql;

class Insert implements ISql {

    protected $tabela;
    protected $dados;
    protected $nomecampos;
    protected $valores;

    public function __construct() {
        $argumentos = func_get_args();

        if (count($argumentos) == 1) {
            $this->dados($argumentos[0]);
        } elseif (count($argumentos) == 2) {
            $this->dados($argumentos[0]);
            $this->tabela($argumentos[1]);
        }
    }

    public function dados(Array $dados) {
        $this->dados = $dados;
        $this->nomecampos = array_keys($this->dados);
        return $this;
    }

    public function tabela($tabela) {
        $this->tabela = $tabela;
        return $this;
    }

    protected function getCampos() {

        return implode(',', $this->nomecampos);
    }

    protected function getCoringas() {
        return ':' . implode(',:', $this->nomecampos);
    }

    public function getDados() {
        $indices = explode(',', $this->getCoringas());
        $valores = array_values($this->dados);

        return array_combine($indices, $valores);
    }

    public function getSql() {
        if (empty($this->tabela) || empty($this->dados)) {
            throw new \Exception('Não foram definidos os parametros de Tabela e/ou Dados');
        } else {

            return "INSERT INTO `" . $this->tabela . "` "
                    . "(" . $this->getCampos() . ") VALUES "
                    . "(" . $this->getCoringas() . ")";
        }
    }

}

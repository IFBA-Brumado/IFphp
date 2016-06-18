<?php

/*
$email = 'mario@gmail.com';
$padrao = "^(.+)@(.+).(.+)$";
if (ereg($padrao,$email))
{
   print 'Seu email passou na validacao';
}
else
{
   print 'Seu email nao passou na validacao';
}
 */

$coluna = 'id';
//$pattern = "\^(:$coluna)[[:digit:]]*\\$";
 $pattern = "/^(:$coluna)[[:digit:]]*$/";

echo $pattern,"<br>";

$dados = [':id' => 'casa',':idade' => 'bola',':idade1' => 'teste',':bolada' => 'teste'];

$teste = preg_grep( $pattern, array_keys( $dados ) );


var_dump($teste);
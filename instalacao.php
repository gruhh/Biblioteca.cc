<?php
/**
 * Informações de instalação do Biblioteca.cc 
 *
 * Este arquivo permite que você determine configurações essenciais para o
 * funcionamento da sua instalação do biblioteca.cc
 */

/**
 * Configurações do Banco de Dados
 *
 * Estas informações são fornecidas pelo serviço de hospedagem do seu site,
 * caso esteja usando localmente, os dados estão relacionados às configurações
 * determinadas na instalação do MySql.
 */
define("BANCO_DADOS_SERVIDOR", "localhost");
define("BANCO_DADOS_USUARIO", "root");
define("BANCO_DADOS_SENHA", "root");
define("BANCO_DADOS_NOME", "bb_testes");

/** 
 * Prefixo de Unidade
 *
 * Caso você esteja utilizando outros códigos em um mesmo banco de dados,
 * recomendamos a modificação do prefixo para evitar a sobreposição.
 * 
 * Prefixo, ainda não implementado
 */
$banco_dados_prefixo	= "bcc001_";
$sufixo_de_seguranca	= "_45ok0887";

/** 
 * Padrões de Localidade
 *
 * Setado como padrão o fuso horário brasileiro
 * e o Charset como UTF-8.
 */
date_default_timezone_set('America/Sao_Paulo');
header('Content-Type: text/html; charset=utf-8');
?>
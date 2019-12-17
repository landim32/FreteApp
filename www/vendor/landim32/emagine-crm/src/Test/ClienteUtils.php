<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 27/12/2018
 * Time: 11:29
 */

namespace Emagine\CRM\Test;

use Exception;
use Emagine\Base\BLL\ValidaCpfCnpj;
use Emagine\Base\Test\TesteUtils;
use Emagine\CRM\Model\ClienteInfo;
use Emagine\Endereco\Test\EnderecoUtils;

class ClienteUtils
{
    /**
     * @throws Exception
     * @return ClienteInfo
     */
    public static function gerarCliente() {
        $nome = TesteUtils::gerarNome();
        $cliente = new ClienteInfo();
        $cliente->setNome($nome);
        $cpfCnpj = new ValidaCpfCnpj();
        $cliente->setCpfCnpj($cpfCnpj->cpfAleatorio());
        $cliente->setRg(TesteUtils::gerarNumeroAleatorio(9));
        $cliente->setEmail(TesteUtils::gerarEmail($nome));
        $cliente->setTelefone1(TesteUtils::gerarNumeroAleatorio(10));
        $cliente->setTelefone2(TesteUtils::gerarNumeroAleatorio(10));
        $endereco = EnderecoUtils::pegarEnderecoAleatorio("SP");
        $cliente->adicionarEndereco($endereco);
        return $cliente;
    }
}
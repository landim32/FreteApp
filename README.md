

# FreteApp para Android & iOS
> Aplicativo completo estilo Uber para fretes e cargas

Aplicativo estilo Uber para fretes e cargas desenvolvido usando Xamarin, C# e com um backend em PHP com MySQL.

## Estrutura do Projeto

* _www_ - API Restful para o aplicativo e administrativo do sistema desenvolvido;
	* _core_ - Contem as configurações;
	* _templates_ - Templates da loja virtual;
	* _test_ - Testes unitários;
	* _src_ - Módulo de gestão dos fretes;
	* _vendor/landim32_ - Módulos personalizados reutilizáveis;
		* _btmenu_ - https://github.com/landim32/btmenu
		* _easydb_ - https://github.com/landim32/easydb
		* _google-directions_ - https://github.com/landim32/google-directions
		* _emagine-base_ - Módulo base da API;
 		* _emagine-log_ - Módulo de log de execução no sistema;
 		* _emagine-login_ - Módulo de autenticação de usuários;
 		* _emagine-endereco_ - Módulo de endereços e busca por CEP;
 		* _emagine-pagamento_ - Módulo de pagamentos online;
 		* _emagine-social_ - Módulo de integração social e com redes sociais;
 * _app_ - Aplicativo de fretes & cargas para Android e iOS (Xamarin)
	 * _Frete_ - Biblioteca com o core do aplicativo;
		 * _Emagine_ - Biblioteca geral com todos os módulos desenvolvidos;
	 * _Frete.Droid_ - Versão para Android;
	 * _Frete.iOS_ - Versão para iOS;
* _material_ - Material visual para o desenvolvimento;
* _sql_ - Dump com várias versões do banco de dados em MySQL;

## Histórico de lançamentos

* 1.0.0b
    * Versão beta

## Meta

Rodrigo Landim – [@Landim32Oficial](https://twitter.com/landim32oficial) – rodrigo@emagine.com.br

Distribuído sob a licença GPLv2. Veja `LICENSE` para mais informações.

[https://github.com/landim32/FreteApp](https://github.com/landim32/FreteApp)

## Contributing

1. Faça o _fork_ do projeto (<https://github.com/landim32/FreteApp/fork>)
2. Crie uma _branch_ para sua modificação (`git checkout -b landim32/FreteApp`)
3. Faça o _commit_ (`git commit -am 'Add some fooBar'`)
4. Push_ (`git push origin landim32/FreteApp`)
5. Crie um novo _Pull Request_

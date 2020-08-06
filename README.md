### Ecommerce API

A aplicação consiste de uma API para gerenciamento de pedidos com endpoints para cadastramento e efetuação dos mesmos. Possui um ambiente administrativo onde é possível visualizar e manipular alguns dados.

## Soluções 

1. Foi criado um model para cada módulo citado respeitando os devidos atributos, constraints e relacionamentos.
2. Para a API foi implementado um sistema de autenticação via JWT, isso ajuda a garantir que apenas pedidos criados por um determinado cliente sejam manipulados por ele, bem como deixa nossa API pronta para ser integrada à qualquer APP.
3. Foi criada uma tabela à parte para persistir os status e foi criada um config em .env para setar o status padrão.
4. As validações dos dados foram feitas com a utilização da classe Validator.
5. Para o ambiente de administrativo foi usada a biblioteca laravel-ui com a implementação do vue. A autenticação via web criou a necessidade de configurar e utilizar dois tipos de guard. Por questões de praticidade e encapsulamento as informações de pedidos, clientes e produtos são montadas dinamicamente para cada model com templates blade.

## Instalação

1. Utilizar o postgresql como banco de dados, configurar as variáveis no arquivo .env
2. Execute o comando *composer install*
3. Executar a migração das tabelas *php artisan migrate*
4. Semear a tabela de status *php artisan db:seed --class=StatusSeeder*
5. Verificar a variável *STATUS_ID_PEDIDO_DEFAULT* com o ID do status default dos pedidos no arquivo .env
6. Executar os comandos *npm install && npm run [dev|prod]*
7. Executar o comando *php artisan jwt:secret* caso não exista nenhum token gerado
8. Para rodar em desenvolvimento *php artisan serve*
9. Para checar as rotas *php artisar route:list*

## Utilização

1. Basta acessar a raiz e clicar em Register e navegar pelos menos do dashboard
2. Principais endpoints
- Cadastro: POST ROOT_URL/api/clientes nome='Fulano' email='fulano@email.com' senha='123456' endereco='Rua qualquer' telefone='89123456789'
- Login: POST ROOT_URL/api/auth/login email=''fulano@email.com senha='123456'
- Pedido: POST ROOT_URL/api/pedidos pedidos='id1|id2|id3' token='nlken2kn3flk3nfl'

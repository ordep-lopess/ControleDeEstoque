## 🗃️ Controle de estoque

Projeto construído seguindo o padrão de arquitetura MVC (Model-View-Controller), garantindo uma organização clara do código e separação de responsabilidades.
Ele permite gerenciar os produtos de maneira eficiente, organizando informações como estoque disponível, entrada e saída de produtos, entre outras funcionalidades essenciais.

### 💡 Funcionalidades: 

- Cadastro de produtos (nome, preço, quantidade em estoque);
- Notificação de baixa de estoque: alerta automático para produtos com menos de 100g em estoque;
- Registro de entrada e saída de produtos no estoque;
- Listagem completa dos produtos cadastrados;
- Interface amigável e responsiva para facilitar o uso.

### 🔗 Estrutura do Projeto

- **Model**: Contém a lógica de negócios e gerencia a interação com o banco de dados.
- **View**: Arquivos responsáveis por exibir os dados para o usuário.
- **Controller**: Recebe as requisições, processa as informações e retorna as respostas adequadas.
- **banco.sql**: Script para criação do banco de dados (instruções a seguir para a criação do banco de dados).

### 📌 Instruções de criação para o banco de dados:

create database BEPV;

use BEPV;

CREATE TABLE `cadastro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(15) NOT NULL,
  `email` varchar(25) NOT NULL,
  `senha_hash` varchar(70) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
);
 
CREATE TABLE `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cadastrado_id` int(11) DEFAULT NULL,
  `nome` varchar(35) NOT NULL,
  `email` varchar(35) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cadastrado_id` (`cadastrado_id`),
  CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`cadastrado_id`) REFERENCES `cadastro` (`id`) ON DELETE CASCADE
);
 
CREATE TABLE `produtos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cadastrado_id` int(11) NOT NULL,
  `nome` varchar(35) NOT NULL,
  `embalagem` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `gramas` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cadastrado_id` (`cadastrado_id`),
  CONSTRAINT `produtos_ibfk_1` FOREIGN KEY (`cadastrado_id`) REFERENCES `cadastro` (`id`)
);
 
CREATE TABLE `saidas_produtos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cadastrado_id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `data` date DEFAULT current_date(),
  PRIMARY KEY (`id`),
  KEY `cadastrado_id` (`cadastrado_id`),
  KEY `cliente_id` (`cliente_id`),
  KEY `produto_id` (`produto_id`),
  CONSTRAINT `saidas_produtos_ibfk_1` FOREIGN KEY (`cadastrado_id`) REFERENCES `cadastro` (`id`),
  CONSTRAINT `saidas_produtos_ibfk_2` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  CONSTRAINT `saidas_produtos_ibfk_3` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`)
);

### 📨 Modo de uso:

1. **Cadastro de Produtos**  
   - Vá até a página de 'Cadastro de Produtos'.
   - Preencha os campos obrigatórios (nome, quantidade, etc.).
   - Clique em Salvar para adicionar o produto ao estoque.

2. **Gerenciamento de Produtos**  
   - Acesse a página de 'Produtos'.
   - Veja a lista completa de produtos cadastrados, incluindo suas quantidades em estoque.
   - Exclua produtos diretamente na interface.

3. **Notificação de Baixa de Estoque**  
   - Sempre que um produto tiver menos de **100g** em estoque, um aviso será exibido automaticamente na página inicial:

4. **Registro de Movimentações**  
   - Registre saídas de produtos no estoque, mantendo o controle de todas as movimentações.
  
5. **Alterar e/ou excluir perfil**  
   - Altere o nome, e-mail, senha ou exclua o perfil.

### Tela Inicial:

<div>
  <img src="https://github.com/ordep-lopess/ControleDeEstoque/blob/main/img/TelaInicial.png?raw=true" width="500px"/>
</div>

### Criar conta:

<div>
  <img src="https://github.com/ordep-lopess/ControleDeEstoque/blob/main/img/CriarConta.png?raw=true" width="500px"/>
</div><br>

### Saída de produtos:

<div>
  <img src="https://github.com/ordep-lopess/ControleDeEstoque/blob/main/img/SaidaProd.png?raw=true" width="500px"/>
</div>

### Lista:

<div>
  <img src="https://github.com/ordep-lopess/ControleDeEstoque/blob/main/img/ListaProduto.png?raw=true" width="500px"/>
</div><br>
<div>
  <img src="https://github.com/ordep-lopess/ControleDeEstoque/blob/main/img/ListaCliente.png?raw=true" width="500px"/>
</div>

### Cadastrar:

<div>
  <img src="https://github.com/ordep-lopess/ControleDeEstoque/blob/main/img/CadastroProd.png?raw=true" width="300px"/>
</div><br>
<div>
  <img src="https://github.com/ordep-lopess/ControleDeEstoque/blob/main/img/CadastroCliente.png?raw=true" width="300px"/>
</div>

### Alterar e/ou excluir:

<div>
  <img src="https://github.com/ordep-lopess/ControleDeEstoque/blob/main/img/AlterarPerfil.png?raw=true" width="500px"/>
</div>

### 🗎 Documentação 
A documentação da API está como Documentação.pdf .


### 🛠 Tecnologias e Ferramentas Utilizadas:

<div style="display: inline_block"><br>
  <img align="center" alt="JS" height="30" width="40" src="https://raw.githubusercontent.com/devicons/devicon/master/icons/javascript/javascript-plain.svg">
  <img align="center" alt="PHP" height="30" width="40" src="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/php/php-original.svg"> 
  <img align="center" alt="HTML" height="30" width="40" src="https://raw.githubusercontent.com/devicons/devicon/master/icons/html5/html5-original.svg">
  <img align="center" alt="CSS" height="30" width="40" src="https://raw.githubusercontent.com/devicons/devicon/master/icons/css3/css3-original.svg">
  <img align="center" alt="MySQL" height="50" width="60" src="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/mysql/mysql-original-wordmark.svg" />
</div>

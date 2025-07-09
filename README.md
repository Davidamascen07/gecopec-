# 🎓 GECOPEC - Sistema de Gestão de Cursos e Planos de Ensino

<div align="center">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white" alt="HTML5">
  <img src="https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white" alt="CSS3">
  <img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black" alt="JavaScript">
</div>

<div align="center">
  <img src="https://img.shields.io/badge/Version-2.0.0-blue?style=flat-square" alt="Version">
  <img src="https://img.shields.io/badge/License-MIT-green?style=flat-square" alt="License">
  <img src="https://img.shields.io/badge/Status-Active-brightgreen?style=flat-square" alt="Status">
</div>

## 📋 Sobre o Projeto

O **GECOPEC** é um sistema completo de gestão acadêmica desenvolvido para facilitar a administração de cursos, disciplinas, planos de ensino e cronogramas. Projetado com foco na usabilidade e eficiência, o sistema oferece uma interface intuitiva para coordenadores, professores e administradores.

### ✨ Principais Funcionalidades

- 🏫 **Gestão de Cursos**: Cadastro e gerenciamento completo de cursos
- 📚 **Controle de Disciplinas**: Organização de disciplinas por curso
- 👨‍🏫 **Gerenciamento de Professores**: Cadastro e vinculação de professores
- 👨‍🎓 **Gestão de Alunos**: Controle de matrículas e dados acadêmicos
- 📝 **Planos de Ensino**: Criação, aprovação e controle de planos de ensino
- 🗓️ **Cronogramas**: Planejamento e acompanhamento de cronogramas
- 📊 **Relatórios**: Geração de relatórios em PDF e Word
- 👥 **Controle de Acesso**: Sistema de autenticação com níveis de permissão
- 📱 **Interface Responsiva**: Design adaptável para diferentes dispositivos

## 🚀 Tecnologias Utilizadas

### Backend
- **PHP** 7.4+ - Linguagem principal
- **MySQL** - Banco de dados relacional
- **PDO** - Camada de abstração de banco de dados
- **Composer** - Gerenciador de dependências

### Frontend
- **HTML5** - Estrutura das páginas
- **CSS3** - Estilização e layout
- **JavaScript** - Interatividade do usuário
- **Bootstrap** (opcional) - Framework CSS

### Bibliotecas e Dependências
- **DomPDF** - Geração de relatórios PDF
- **PHPWord** - Geração de documentos Word
- **Font Awesome** - Ícones
- **Tailwind CSS** - Estilização moderna

## 🏗️ Arquitetura do Sistema

O projeto segue o padrão **MVC (Model-View-Controller)** para uma melhor organização e manutenibilidade:

```
gecopec/
├── config/           # Configurações da aplicação
├── controllers/      # Controladores (lógica de negócio)
├── models/          # Modelos (interação com banco)
├── views/           # Views (interface do usuário)
├── lib/             # Bibliotecas auxiliares
├── public/          # Arquivos públicos (CSS, JS, imagens)
├── sql/             # Scripts de banco de dados
├── vendor/          # Dependências do Composer
└── index.php        # Arquivo principal de roteamento
```

## 📦 Instalação e Configuração

### Pré-requisitos
- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Composer
- Servidor web (Apache/Nginx)

### 1. Clone o Repositório
```bash
git clone https://github.com/Davidamascen07/gecopec-.git
cd gecopec
```

### 2. Instale as Dependências
```bash
composer install
```

### 3. Configure o Banco de Dados
1. Crie um banco de dados MySQL:
```sql
CREATE DATABASE gecopec_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Configure as credenciais em `config/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'seu_usuario');
define('DB_PASS', 'sua_senha');
define('DB_NAME', 'gecopec_db');
```

### 4. Execute os Scripts de Inicialização
```bash
# Acesse via browser ou execute via MySQL
mysql -u seu_usuario -p gecopec_db < sql/init.sql
mysql -u seu_usuario -p gecopec_db < sql/setup.sql
```

### 5. Configure o Servidor Web
Aponte o servidor web para a pasta do projeto ou use o servidor built-in do PHP:
```bash
php -S localhost:8000
```

## 🔐 Acesso ao Sistema

### Credenciais Padrão
- **Email**: admin@gecopec.com
- **Senha**: adm123

⚠️ **Importante**: Altere essas credenciais após o primeiro acesso!

## 🎯 Como Usar

### Dashboard Principal
- Acesse o painel principal com estatísticas e atividades recentes
- Visualize planos pendentes de aprovação
- Acompanhe próximos eventos e cronogramas

### Gerenciamento de Cursos
1. Acesse "Cursos" no menu principal
2. Clique em "Novo Curso" para adicionar
3. Preencha nome, carga horária e ementa
4. Defina o coordenador responsável

### Criação de Planos de Ensino
1. Navegue até "Planos de Ensino"
2. Selecione "Novo Plano"
3. Escolha a disciplina e professor
4. Defina objetivos, metodologia e avaliação
5. Submeta para aprovação

### Geração de Relatórios
- Acesse "Relatórios" no menu
- Escolha entre PDF ou Word
- Selecione os critérios desejados
- Faça o download do arquivo gerado

## 🔧 Funcionalidades Técnicas

### Sistema de Autenticação
- Login seguro com hash de senhas
- Controle de sessões
- Níveis de permissão (Admin, Coordenador, Professor, Aluno)

### Geração de Documentos
- **PDF**: Relatórios e planos de ensino
- **Word**: Documentos editáveis
- Templates personalizáveis

### Validação de Dados
- Validação client-side e server-side
- Sanitização de inputs
- Proteção contra SQL Injection

## 📊 Estrutura do Banco de Dados

### Principais Tabelas
- **usuarios**: Gerenciamento de usuários e permissões
- **cursos**: Informações dos cursos oferecidos
- **disciplinas**: Disciplinas por curso
- **professores**: Dados dos professores
- **alunos**: Informações dos estudantes
- **planos_ensino**: Planos de ensino das disciplinas
- **cronogramas**: Cronogramas de aulas
- **turmas**: Turmas e matrículas

## 🤝 Contribuindo

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/nova-funcionalidade`)
3. Commit suas mudanças (`git commit -am 'Adiciona nova funcionalidade'`)
4. Push para a branch (`git push origin feature/nova-funcionalidade`)
5. Abra um Pull Request

### Padrões de Código
- PSR-4 para autoloading
- PSR-12 para estilo de código
- Comentários em português
- Nomes de variáveis e funções em português

## 🐛 Solução de Problemas

### Problemas Comuns

#### Erro de Conexão com Banco
```php
// Verifique as configurações em config/config.php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'gecopec_db');
```

#### Erro de Permissões
```bash
# Para sistemas Unix/Linux
chmod -R 755 public/
chmod -R 777 storage/ (se existir)
```

#### Problema com Composer
```bash
# Reinstale as dependências
rm -rf vendor/
composer install
```

## 📱 Capturas de Tela

### Dashboard
*Dashboard principal com estatísticas e atividades recentes*

### Gestão de Cursos
*Interface para cadastro e gerenciamento de cursos*

### Planos de Ensino
*Formulário para criação de planos de ensino*

## 🔮 Roadmap

### Versão 2.1
- [ ] API REST para integração
- [ ] Notificações em tempo real
- [ ] Sistema de backup automático
- [ ] Integração com calendário

### Versão 2.2
- [ ] App mobile
- [ ] Relatórios avançados
- [ ] Sistema de workflow
- [ ] Integração com e-mail

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 👨‍💻 Autor

**Desenvolvido por:** [David Damasceno]
- 📧 Email: davidddf.frota@gmail.com
- 🌐 Website: [seu-website.com](https://seu-website.com)
- 💼 LinkedIn: [damascenodf](https://linkedin.com/in/damascenodf/)

## 🙏 Agradecimentos

- Equipe de desenvolvimento
- Comunidade PHP
- Instituições educacionais que inspiraram o projeto

---

<div align="center">
  <p>⭐ Se este projeto te ajudou, considere dar uma estrela!</p>
  <p>📝 Feito com ❤️ para a comunidade educacional</p>
</div>

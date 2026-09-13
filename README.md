<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo"></a></p>

# Blog ISEP

Aplicação de blog construída em Laravel, com autenticação via [Laravel Breeze](https://laravel.com/docs/starter-kits) e gestão de conteúdo através do [Canvas](https://github.com/austintoddj/canvas).

## Requisitos

- PHP >= 8.3 (com as extensões habituais do Laravel: `pdo_sqlite`/`pdo_mysql`, `mbstring`, `xml`, `curl`, etc.)
- [Composer](https://getcomposer.org)
- Node.js e npm
- Uma base de dados (por omissão o projeto usa **SQLite**; MySQL/PostgreSQL também são suportados)

## Instalação

### 1. Clonar o repositório

```bash
git clone <url-do-repositorio>
cd blog-isep
```

### 2. Instalar as dependências PHP

```bash
composer install
```

### 3. Configurar o ambiente

Copiar o ficheiro de exemplo e gerar a chave da aplicação:

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configurar a base de dados

Por omissão o `.env.example` está configurado para **SQLite** (`DB_CONNECTION=sqlite`). Cria o ficheiro da base de dados:

```bash
touch database/database.sqlite
```

Se preferires MySQL/PostgreSQL, edita as variáveis `DB_*` no `.env` em conformidade.

### 5. Correr as migrações

```bash
php artisan migrate
```

### 6. Instalar as dependências JavaScript e compilar os assets

```bash
npm install
npm run build
```

Para desenvolvimento com hot-reload, usar em alternativa:

```bash
npm run dev
```

### 7. Ligar o storage público (opcional, mas recomendado)

```bash
php artisan storage:link
```

## Canvas (gestão de artigos)

O Canvas expõe uma UI própria em `/canvas` para escrever e gerir artigos, e é necessário atribuir permissões a um utilizador para lhe aceder.

1. Cria uma conta normal na aplicação (via `/register`) ou através de um `seeder`.
2. Torna essa conta administradora do Canvas:

   ```bash
   php artisan canvas:make-admin
   ```

3. Acede à interface de administração em `http://localhost:8000/canvas` e à interface pública de leitura em `http://localhost:8000/canvas-ui`.

Outros comandos úteis do Canvas:

```bash
php artisan canvas:roles           # lista os roles disponíveis
php artisan canvas:assign-role     # atribui um role a um utilizador
php artisan canvas:remove-access   # remove o acesso de um utilizador
php artisan canvas:users           # lista utilizadores com acesso ao Canvas
```

## Correr a aplicação

Em desenvolvimento, o script `composer run dev` arranca em simultâneo o servidor Laravel, a fila (queue), os logs (Pail) e o Vite:

```bash
composer run dev
```

Em alternativa, manualmente:

```bash
php artisan serve
npm run dev
```

A aplicação fica disponível em `http://localhost:8000`.

## Testes

O projeto usa PHPUnit. Para correr a suite de testes:

```bash
php artisan test
```

ou

```bash
vendor/bin/phpunit
```

## Instalação rápida (script automático)

O `composer.json` inclui um script `setup` que executa os passos essenciais de instalação de uma só vez (instala dependências PHP, cria o `.env`, gera a chave, corre as migrações e compila os assets):

```bash
composer run setup
```

> Nota: este script não cria o ficheiro `database/database.sqlite`, por isso, se usares SQLite, cria-o antes (`touch database/database.sqlite`) ou garante que a base de dados já existe.

## Stack utilizada

- **Backend:** Laravel 13, PHP 8.3+
- **Autenticação:** Laravel Breeze
- **Blog/CMS:** Canvas
- **Frontend:** Tailwind CSS, Alpine.js, Vite
- **Testes:** PHPUnit
- **Ferramentas de dev:** Laravel Pint (formatação), Laravel Boost, Laravel Pail (logs)

## Licença

Este projeto é open-source, licenciado sob a [licença MIT](https://opensource.org/licenses/MIT).

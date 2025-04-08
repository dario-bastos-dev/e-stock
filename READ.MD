# Funcionalidades Implementadas

## Autenticação e Registro de Usuários
- Registro de novos usuários com validação de dados.
- Login e logout de usuários.
- Confirmação de senha e redefinição de senha.
- Verificação de e-mail e envio de notificações.

## Gerenciamento de Perfil
- Atualização de informações do perfil do usuário.
- Alteração de senha com validação da senha atual.

## Dashboard
- Página inicial para usuários autenticados.

## Configurações de Aparência
- Página para gerenciar configurações de aparência do usuário.

## Gerenciamento de Categorias
- CRUD para categorias, incluindo nome, descrição, imagem e associação com empresas.

## Gerenciamento de Produtos
- CRUD para produtos, incluindo nome, descrição, SKU, código de barras, custo, preço e associação com categorias e empresas.

---

# Organização do Banco de Dados

## Tabelas Criadas

### `users`
- **Campos**: `id`, `name`, `email`, `password`, `email_verified_at`, `remember_token`, `timestamps`.

### `categories`
- **Campos**: `id`, `name`, `description`, `image`, `company_id`, `softDeletes`, `timestamps`.

### `products`
- **Campos**: `id`, `name`, `description`, `sku`, `barcode`, `image`, `cost`, `price`, `company_id`, `category_id`, `softDeletes`, `timestamps`.

### `companies`
- **Campos**: `id`, `name`, `document`, `address`, `city`, `state`, `country`, `zip_code`, `phone`, `logo`, `softDeletes`, `timestamps`.

### `cache`
- **Campos**: `key`, `value`, `expiration`.

### `jobs`
- **Campos**: `id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`.

### `failed_jobs`
- **Campos**: `id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`.

---

# Rotas

## Rotas de Autenticação
- `GET /register` - Exibe a página de registro.
- `POST /register` - Registra um novo usuário.
- `GET /login` - Exibe a página de login.
- `POST /login` - Autentica o usuário.
- `POST /logout` - Faz logout do usuário.
- `GET /forgot-password` - Exibe a página de recuperação de senha.
- `POST /forgot-password` - Envia o link de redefinição de senha.
- `GET /reset-password/{token}` - Exibe a página de redefinição de senha.
- `POST /reset-password` - Redefine a senha do usuário.

## Rotas de Configurações
- `GET /settings/profile` - Exibe a página de edição do perfil.
- `PATCH /settings/profile` - Atualiza as informações do perfil.
- `DELETE /settings/profile` - Exclui o perfil do usuário.
- `GET /settings/password` - Exibe a página de alteração de senha.
- `PUT /settings/password` - Atualiza a senha do usuário.
- `GET /settings/appearance` - Exibe a página de configurações de aparência.

## Rotas de Dashboard
- `GET /dashboard` - Exibe o dashboard para usuários autenticados.

---

# Middlewares

## HandleInertiaRequests
- Gerencia as requisições do Inertia.js.
- Compartilha dados globais como o nome da aplicação e informações do usuário autenticado.

## HandleAppearance
- Gerencia as configurações de aparência do usuário.

## EncryptCookies
- Criptografa cookies, exceto os especificados.

---

# Tecnologias e Dependências
- **Laravel Framework**: Backend e gerenciamento de rotas, middlewares e banco de dados.
- **Inertia.js**: Integração frontend-backend.
- **PestPHP**: Framework de testes.
- **Tightenco/Ziggy**: Gerenciamento de rotas no frontend.
- **FakerPHP**: Geração de dados fictícios para testes.
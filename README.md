# Sistema de Gestão Escolar (SaaS)

Plataforma robusta para administração educacional e controle multi-tenant (SaaS). Desenvolvida com foco em escalabilidade, utilizando **Laravel 11** e **Vue.js 3** sob uma arquitetura modular orientada ao domínio (DDD).

## 💡 Visão Geral

Este projeto é um ERP educacional moderno projetado para atender múltiplas instituições (tenants). O diferencial técnico reside na sua arquitetura segregada em módulos e no seu sistema de controle de acesso (ACL) de alta granularidade.
O sistema utiliza o pacote Spatie Laravel-Permission para implementação do padrão RBAC (Role-Based Access Control), estendido com uma Camada de Sobrescrita de Permissões (Permission Override Layer) que permite a aplicação de Permissões Negativas (Deny-List) granulares por usuário, garantindo precedência de bloqueio sobre as regras hierárquicas dos cargos.

## 🚀 Stack Tecnológico

* **Core Framework:** Laravel 11 (LTS)
* **Frontend Monorepo:** Vue.js 3 + Inertia.js (SPA Experience)
* **Estilização:** Tailwind CSS v3
* **Build Tool:** Vite v5
* **Banco de Dados:** MySQL 8.0 / MariaDB
* **Gerenciamento de Estado:** Composition API

## 🏗️ Arquitetura Modular (DDD)

Para garantir a manutenção a longo prazo, o sistema não segue a estrutura padrão MVC do Laravel (`app/Models`, `app/Controllers`). Em vez disso, utilizamos **Módulos**:

Modules/ ├── AccessControl/ # Contexto Delimitado: Segurança │ ├── Domain/ # Entidades e Regras de Negócio (Ex: User, Permission) │ ├── Infrastructure/ # Implementação Técnica (Controllers, Providers) │ └── Application/ # Casos de Uso e Services ├── Financial/ # (Futuro) Gestão Financeira └── Academic/ # (Futuro) Gestão Pedagógica


## 🔥 Destaque: Sistema Híbrido de Permissões (ACL)

O sistema implementa uma lógica de segurança avançada que permite exceções granulares:

1.  **RBAC (Role-Based Access Control):** O usuário recebe permissões baseadas em seu Cargo (Ex: Coordenador tem acesso a `manage_users`).
2.  **Negative Permissions (Blocklist):** Um administrador pode **bloquear explicitamente** uma permissão específica de um usuário, sobrescrevendo a regra do cargo.
    * *Cenário:* Um "Coordenador" pode gerenciar usuários, mas o "Coordenador Carlos" especificamente foi bloqueado dessa função temporariamente.

## 🛠️ Instalação e Configuração

### Pré-requisitos
* PHP 8.2+
* Node.js & NPM
* Composer

### Passo a Passo

1.  **Clone o repositório**
    ```bash
    git clone [https://github.com/SEU-USUARIO/NOME-DO-REPO.git](https://github.com/SEU-USUARIO/NOME-DO-REPO.git)
    cd nome-do-repo
    ```

2.  **Instale as dependências de Backend e Frontend**
    ```bash
    composer install
    npm install
    ```

3.  **Configuração de Ambiente**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Configure as credenciais do banco de dados no arquivo `.env`.*

4.  **Banco de Dados**
    ```bash
    php artisan migrate --seed
    ```

5.  **Execução (Ambiente de Desenvolvimento)**
    É necessário rodar dois terminais:
    * Terminal 1: `npm run dev` (Vite Server)
    * Terminal 2: `php artisan serve` (Laravel Server)

---
*Este projeto é estritamente para fins educacionais e de portfólio.*
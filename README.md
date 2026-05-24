# 📊 LG Dashboard — Eficiência de Produção

Dashboard de monitoramento de eficiência de produção da **Planta A** da LG Electronics,
desenvolvido com **Laravel 8** + **Material Dashboard** (Creative Tim).

---

## 🚀 Funcionalidades

- ✅ **Visão consolidada** de todas as linhas de produto
- ✅ **Filtro por linha** (Geladeira, Máquina de Lavar, TV, Ar-Condicionado)
- ✅ **Cards de KPI** — Total Produzido, Defeitos, Produtos Conformes e Eficiência Geral
- ✅ **Gráfico de linha** — Eficiência diária ao longo de janeiro/2026
- ✅ **Gráfico de barras empilhado** — Conformes vs Defeitos por linha
- ✅ **Tabela detalhada** com barras de progresso e badges de status
- ✅ **Sidebar** com navegação rápida por linha
- ✅ **Template** Material Dashboard 3 (Creative Tim) — gratuito

---

## 🛠️ Stack Tecnológica

| Camada     | Tecnologia                           |
|------------|--------------------------------------|
| Backend    | Laravel 8 (PHP 8.x)                  |
| Banco      | MySQL 8 / MariaDB                    |
| Frontend   | Blade + Material Dashboard (CDN)     |
| Gráficos   | Chart.js 4                           |
| CSS        | Bootstrap 5 + Material Design        |
| Ícones     | Font Awesome 6 + Material Icons      |

---

## ⚙️ Como rodar o projeto localmente

### Pré-requisitos

- PHP >= 8.0
- Composer
- MySQL 8 ou MariaDB
- Git

### 1. Clonar o repositório

```bash
git clone https://github.com/bruno-herculano/lg-dashboard.git
cd lg-dashboard
```

### 2. Instalar dependências PHP

```bash
composer install
```

### 3. Configurar o ambiente

```bash
cp .env.example .env
php artisan key:generate
```

Edite o `.env` com suas credenciais de banco:

```dotenv
APP_NAME="LG Dashboard"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lg_dashboard
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 4. Criar o banco de dados

```sql
CREATE DATABASE lg_dashboard CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Executar as migrations

```bash
php artisan migrate
```

### 6. Popular o banco com dados simulados (Seeder)

```bash
php artisan db:seed
```

> Isso insere **84 registros** — 4 linhas × 21 dias úteis de janeiro/2026.

### 7. Iniciar o servidor

```bash
php artisan serve
```

Acesse: **http://localhost:8000/dashboard**

---

## 🗃️ Estrutura da Tabela

### `produtividades`

```sql
CREATE TABLE produtividades (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    linha_produto       VARCHAR(255)    NOT NULL,          -- Ex: 'Geladeira'
    planta              VARCHAR(255)    NOT NULL DEFAULT 'Planta A',
    data_producao       DATE            NOT NULL,          -- Data do registro
    quantidade_produzida INT            NOT NULL,          -- Total produzido no dia
    quantidade_defeitos  INT            NOT NULL,          -- Total com defeito
    created_at          TIMESTAMP       NULL,
    updated_at          TIMESTAMP       NULL
);
```

**Fórmula de Eficiência:**
```
Eficiência (%) = (quantidade_produzida - quantidade_defeitos) / quantidade_produzida × 100
```

---

## 📥 INSERT de dados para simular o banco

```sql
-- Inserção mínima de exemplo (para teste manual)
INSERT INTO produtividades (linha_produto, planta, data_producao, quantidade_produzida, quantidade_defeitos, created_at, updated_at) VALUES
('Geladeira',        'Planta A', '2026-01-02', 318, 12, NOW(), NOW()),
('Geladeira',        'Planta A', '2026-01-05', 325,  9, NOW(), NOW()),
('Máquina de Lavar', 'Planta A', '2026-01-02', 282, 18, NOW(), NOW()),
('Máquina de Lavar', 'Planta A', '2026-01-05', 275, 14, NOW(), NOW()),
('TV',               'Planta A', '2026-01-02', 498, 12, NOW(), NOW()),
('TV',               'Planta A', '2026-01-05', 510,  8, NOW(), NOW()),
('Ar-Condicionado',  'Planta A', '2026-01-02', 208, 17, NOW(), NOW()),
('Ar-Condicionado',  'Planta A', '2026-01-05', 215, 22, NOW(), NOW());
```

> **Recomendação:** Use o seeder para dados completos do mês inteiro:
> ```bash
> php artisan db:seed
> ```

---

## 📁 Estrutura do Projeto

```
lg-dashboard/
├── app/
│   ├── Http/Controllers/
│   │   └── DashboardController.php   ← Lógica do dashboard + filtros
│   └── Models/
│       └── Produtividade.php          ← Model com cálculo de eficiência
├── database/
│   ├── migrations/
│   │   └── ..._create_produtividades_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── ProdutividadeSeeder.php    ← 84 registros de jan/2026
├── resources/views/
│   ├── layouts/
│   │   └── app.blade.php              ← Layout com sidebar Material Dashboard
│   └── dashboard/
│       └── index.blade.php            ← View principal com KPIs, gráficos e tabela
└── routes/
    └── web.php                        ← Rotas da aplicação
```

---

## 🎨 Template

Este projeto utiliza o **[Material Dashboard 3](https://www.creative-tim.com/product/material-dashboard)** da **Creative Tim** (versão gratuita), carregado via CDN.

---

## 📊 Linhas de Produto — Planta A

| Linha           | Produção Diária (base) | Taxa de Defeito |
|-----------------|------------------------|-----------------|
| Geladeira       | ~320 unidades          | 2% – 8%         |
| Máquina de Lavar| ~280 unidades          | 3% – 10%        |
| TV              | ~500 unidades          | 1% – 6%         |
| Ar-Condicionado | ~210 unidades          | 4% – 12%        |

---

## 🤝 Autor

<div align="center">

### Bruno Alexandre Herculano

Desenvolvedor Full Stack apaixonado por tecnologia, sistemas e experiência de produto.

🌐 Portfólio  
https://bruno-herculano.dev.br/

💻 GitHub  
https://github.com/bruno-herculano

💼 LinkedIn  
https://www.linkedin.com/in/bruno-alexandre-herculano/

</div>

Desenvolvido como **Desafio Técnico** para vaga na **LG Electronics**.

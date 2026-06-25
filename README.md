# Jornadas Científicas — ISPM

Plataforma web de gestão das Jornadas Científico-Metodológicas do **Instituto Superior Politécnico Maravilha (ISPM)**, Angola. Permite gerir inscrições, submissões de comunicações, certificados, mini-cursos e avaliações de cada edição do evento.

---

## Funcionalidades

### Área pública
- Consulta de informação e programa do evento
- Inscrição de participantes (docentes, estudantes, público geral)
- Submissão de comunicações científicas (com upload de PDF)
- Formulário de avaliação/feedback do evento (escala de Likert)
- Verificação e descarregamento de certificados por código único

### Painel de administração
- Gestão de edições do evento (criar, configurar, activar)
- Gestão de inscrições (aceitar/rejeitar, validar pagamento, check-in)
- Gestão de comunicações submetidas (admitir/rejeitar)
- Emissão e envio de certificados em lote (participantes, oradores, moderadores, organizadores)
- Gestão de mini-cursos por edição
- Estatísticas e relatórios (presenças, receitas, submissões)
- Sistema de verificação por QR code
- Configuração de imagens e textos do site

---

## Stack Tecnológico

| Camada | Tecnologia |
|---|---|
| Backend | Laravel 12 (PHP 8.2+) |
| Base de dados | SQLite (padrão) / MySQL / PostgreSQL |
| Autenticação | SIGAM — sistema institucional ISPM |
| PDF | DOMPDF + barryvdh/laravel-dompdf |
| QR Code | endroid/qr-code |
| Frontend | Blade + Tailwind CSS v4 + Bootstrap 5 |
| Build | Vite 6 |
| Sessões/Queue | Base de dados |

---

## Requisitos

- PHP 8.2+
- Composer
- Node.js 18+
- SQLite **ou** MySQL/PostgreSQL
- Acesso à API SIGAM (`https://sigam.ispm.online/api/`)

---

## Instalação

```bash
# 1. Entrar na pasta do projecto Laravel
cd laravel

# 2. Instalar dependências PHP e JS
composer install
npm install

# 3. Configurar ambiente
cp .env.example .env
php artisan key:generate

# 4. Aplicar migrações (cria a base de dados SQLite automaticamente)
php artisan migrate

# 5. Compilar assets
npm run build
```

### Variáveis de ambiente relevantes (`.env`)

```dotenv
APP_NAME="Jornadas Científicas ISPM"
APP_URL=http://localhost

DB_CONNECTION=sqlite
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=jornadas
# DB_USERNAME=root
# DB_PASSWORD=

SIGAM_VERIFY_URL=https://sigam.ispm.online/api/verify-user
SIGAM_TIMEOUT=25
# SIGAM_CA_BUNDLE=/caminho/para/cacert.pem

MAIL_MAILER=log   # alterar para smtp em produção
```

---

## Execução em Desenvolvimento

```bash
cd laravel

# Inicia servidor, queue, logs e Vite em simultâneo
composer run dev
```

Ou em terminais separados:

```bash
php artisan serve          # http://localhost:8000
php artisan queue:listen   # processamento de jobs em background
npm run dev                # Vite com hot reload
```

---

## Autenticação

O acesso ao painel de administração é feito exclusivamente via **SIGAM** — o sistema de identidade institucional do ISPM. Não existe registo de utilizadores na aplicação. Apenas utilizadores com perfil não-estudante no SIGAM têm acesso ao painel.

---

## Estrutura do Projecto

```
laravel/
├── app/
│   ├── Http/Controllers/        # Controladores públicos
│   │   └── Admin/               # Controladores do painel de administração
│   └── Models/                  # Inscricao, Edicao, Certificado, Submissao,
│                                #   Avaliacao, MiniCurso, User, Configuracao
├── database/
│   └── migrations/              # 20+ migrações
├── resources/
│   ├── views/                   # Blade templates (public + admin + emails)
│   ├── css/                     # Estilos (Tailwind + custom)
│   └── js/                      # JavaScript (Vite entry point)
├── routes/
│   └── web.php                  # Todas as rotas
└── storage/
    └── app/public/              # Uploads (comprovativos, PDFs, imagens)
```

---

## Certificados

Os certificados são gerados em PDF com QR code para verificação pública. Cada certificado tem um código único no formato `XI-YY-XXXXXXXX`. Podem ser gerados individualmente ou em lote e enviados por e-mail directamente pela plataforma.

Tipos disponíveis: **participante**, **prelector de mini-curso**, **prelector de comunicação**, **moderador**, **organizador**.

---

## Notas de Produção

- Configurar um servidor web com reescrita de URL (Apache `.htaccess` ou Nginx para Laravel)
- Assegurar que o directório `storage/` tem permissões de escrita
- Configurar `MAIL_MAILER` para `smtp` e respectivas credenciais
- Recomendado um worker de queue permanente (`php artisan queue:work`) para emissão de certificados em lote e envio de e-mails
- A API SIGAM deve estar acessível com certificado SSL válido (ou configurar `SIGAM_CA_BUNDLE`)

---

## Licença

Uso interno — Instituto Superior Politécnico Maravilha (ISPM), Angola.

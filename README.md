# LGPD4DEVS 🛡️
> Ferramenta prática de conformidade com a LGPD para desenvolvedores de sistemas voltados a crianças e adolescentes.

<p align="center">
  <img src="https://github.com/user-attachments/assets/5e38ea64-64b2-4448-acfd-4ba4e237649a" alt="logo projeto LGPD4DEVS" width="300">
</p>

<p align="center">
  <a href="https://lgpd4devs.onrender.com" target="_blank, width="100">
    🌐 Link para acessar o Sistema em Produção
  </a>
</p>

---

## 📌 Sobre o Projeto

O **LGPD4DEVS** é uma ferramenta prática projetada para traduzir a complexidade jurídica da Lei Geral de Proteção de Dados em ações técnicas executáveis. O foco principal é apoiar desenvolvedores e gestores de TI na proteção de dados de crianças e adolescentes, garantindo que a privacidade seja integrada desde a concepção da aplicação (**Privacy by Design**).

---
## 🌍 Alinhamento ODS (Agenda 2030)

| ODS | Contribuição |
|-----|-------------|
| **ODS 4** — Educação de Qualidade | Disseminação técnica de direitos digitais para desenvolvedores |
| **ODS 9** — Inovação e Infraestrutura | Fomento ao desenvolvimento de software ético e seguro |
| **ODS 16** — Paz e Justiça | Proteção contra exploração de dados de crianças e adolescentes vulneráveis |

---
## 🚀 Funcionalidades

### Para Desenvolvedores
- ✅ **Checklist Interativo** — Diagnóstico de conformidade baseado na LGPD com lógica de pesos por pergunta
- 📊 **Score de Adequação** — Relatório visual com barra de progresso, métricas de conformidade e classificação por nível
- 📖 **Biblioteca de Materiais** — Guias técnicos e artigos sugeridos com base nas falhas detectadas
- 🗂️ **Sistema de Projetos** — Gerencie múltiplos projetos, vincule diagnósticos e acompanhe a evolução de conformidade ao longo do tempo
- 📄 **Relatório PDF Formal** — Exportação de relatório de conformidade, materiais listados por item e sem URL do site
- 📱 **Responsivo** — Interface adaptada para desktop e mobile com navbar que recolhe automaticamente ao rolar

### Para Administradores
- 🛡️ **Painel Administrativo** — Acesso exclusivo via perfil `admin`, com link destacado no menu após login
- 📚 **CRUD de Materiais** — Criação, edição e remoção de materiais da biblioteca, com vínculo às perguntas do checklist
- 👥 **Gestão de Usuários** — Listagem de contas, promoção/revogação de perfil admin e remoção de contas
- 📡 **Dashboard em Tempo Real** — Estatísticas gerais e listas de últimos cadastros/diagnósticos atualizadas a cada 30 segundos via polling, sem recarregar a página

---

## 🏗️ Arquitetura e Tecnologia

O projeto utiliza o padrão **MVC (Model-View-Controller)**, garantindo escalabilidade, segurança e separação de responsabilidades.

| Camada | Tecnologia |
|--------|-----------|
| Linguagem | PHP 8.3+ |
| Arquitetura | MVC com Front Controller (roteamento via `.htaccess`) |
| Banco de Dados | MySQL 8.4 (PDO Singleton) |
| Front-end | CSS3 responsivo, JavaScript puro (sem frameworks) |
| Hospedagem | Render (Docker) + Aiven (MySQL gerenciado) |
| Versionamento | Git + GitHub com deploy contínuo (CD) |

### Estrutura de Pastas

```text
lgpd4devs/
├── app/
│   ├── controllers/
│   │   ├── AdminController.php       ← Painel administrativo
│   │   ├── AuthController.php        ← Login, cadastro e logout
│   │   ├── ChecklistController.php   ← Checklist e processamento
│   │   ├── HistoricoController.php   ← Histórico de diagnósticos
│   │   ├── HomeController.php
│   │   ├── MateriaisController.php
│   │   ├── PaginaController.php      ← Páginas institucionais
│   │   ├── ProjetoController.php     ← CRUD de projetos + APIs
│   │   └── ResultadoController.php
│   ├── models/
│   │   ├── Historico.php
│   │   ├── Material.php
│   │   ├── Pergunta.php
│   │   ├── Projeto.php
│   │   ├── Resposta.php
│   │   └── Usuario.php
│   └── views/
│       ├── admin/                    ← Dashboard, materiais e usuários (admin)
│       ├── auth/                     ← Login e cadastro
│       ├── checklist/
│       ├── historico/
│       ├── home/
│       ├── layouts/                  ← Header e footer compartilhados
│       ├── materiais/
│       ├── paginas/                  ← Sobre, contato, privacidade
│       ├── projetos/
│       └── resultado/
├── config/
│   └── Database.php                  ← Singleton PDO com suporte local e produção
├── database/
│   └── schema.sql                    ← Schema completo para deploy em máquina limpa
├── public/
│   ├── css/
│   │   └── estilo.css
│   ├── img/
│   └── index.php                     ← Front Controller (único ponto de entrada HTTP)
└── Dockerfile
```

---

## 🔒 Segurança Implementada

- **Proteção CSRF** — Tokens de validação em todos os formulários de ação (criar, editar, deletar, salvar resultado)
- **Controle de Acesso por Perfil** — Coluna `perfil` na tabela `usuarios` (`usuario` / `admin`). Rotas `/admin/*` protegidas por verificação de sessão no servidor
- **Segurança de Diretório** — Front Controller único; arquivos de lógica e configuração fora da raiz pública
- **Gestão de Sessão** — Regeneração de ID de sessão no login para prevenir Session Fixation
- **Variáveis de Ambiente** — Credenciais de banco via `.env` (nunca versionadas)
- **Erros em Produção** — `display_errors` desativado automaticamente quando `APP_ENV=production`
- **Proteção de APIs** — Rotas `/api/*` retornam JSON vazio em vez de redirecionar quando não autenticadas, evitando quebra de fetch no cliente

---

## 🗄️ Banco de Dados

### Diagrama de Tabelas

```
usuarios ──────────────────────────────────────────────────────┐
  id, nome, email, senha, perfil, data_cadastro                │
                                                               │
projetos ──────────────────────────────────────────────────────┤
  id, usuario_id(FK), nome, descricao, publico_alvo,           │
  status, data_criacao                                         │
                                                               │
historico_diagnosticos ────────────────────────────────────────┤
  id, usuario_id(FK), projeto_id(FK nullable),                 │
  percentual, total_pontos, pontos_obtidos,                    │
  total_perguntas, perguntas_ok, perguntas_falha, data_salvo   │
                                                               │
historico_respostas                                            │
  id, historico_id(FK), pergunta_id(FK),                       │
  pergunta_texto, pergunta_peso, categoria_nome, resposta      │
                                                               │
categorias      perguntas       materiais                      │
  id, nome        id, categoria_id(FK),   id, titulo,          │
                  texto, peso             categoria, ...        │
                                                               │
respostas                 pergunta_material (N:N)              │
  id, usuario_id(FK),       pergunta_id(FK)                    │
  pergunta_id(FK),          material_id(FK)                    │
  resposta, data_resposta                                      │
```
---

## 👤 Painel Administrativo

O acesso ao painel é feito pelo mesmo login do site. Após autenticação, usuários com `perfil = 'admin'` veem o botão **⚙️ Admin** no menu, que leva ao `/admin`.

**Para promover o primeiro admin** (feito uma única vez via banco):
```sql
UPDATE usuarios SET perfil = 'admin' WHERE email = 'seu@email.com';
```

**Após o primeiro admin criado**, os demais podem ser promovidos diretamente pela tela `/admin/usuarios` sem precisar acessar o banco.

---

## 🛠️ Como Executar (Ambiente Local)

Certifique-se de ter o **Laragon** instalado (Apache + PHP 8.3 + MySQL 8.4).

**1. Clone o repositório**
```bash
git clone https://github.com/mairamichelon/Projeto-Interdisciplinar-I-LGPD4DEVs.git
cd Projeto-Interdisciplinar-I-LGPD4DEVs
```

**2. Configure o ambiente**
```bash
# Renomeie o arquivo de exemplo e insira suas credenciais locais
cp .env.example .env
```

**3. Crie o banco de dados**
```bash
# Via MySQL CLI ou HeidiSQL, execute:
mysql -u root -p < database/schema.sql
```

**4. Configure o servidor**

Aponte o **DocumentRoot** do Apache para a pasta `/public`.

No Laragon, crie um Virtual Host apontando para `{pasta-do-projeto}/public`.

**5. Acesse o sistema**
```
http://lgpd4devs.test  (ou localhost conforme sua configuração)
```

**6. Crie o primeiro administrador**
```sql
UPDATE usuarios SET perfil = 'admin' WHERE email = 'seu@email.com';
```

---

## 🐳 Deploy com Docker (Produção)

```bash
# Build da imagem
docker build -t lgpd4devs .

# Executar container
docker run -p 80:80 \
  -e APP_ENV=production \
  -e DB_HOST=seu-host \
  -e DB_PORT=3306 \
  -e DB_NAME=db_lgpd4devs \
  -e DB_USER=seu-usuario \
  -e DB_PASS=sua-senha \
  lgpd4devs
```

O Render executa o build automaticamente a cada push na branch `main` via integração com o GitHub.

---

## 📋 Changelog

| Versão | Data | Descrição |
|--------|------|-----------|
| 1.0 | 26/03/2026 | MVP: Checklist, Score de Conformidade, Biblioteca de Materiais |
| 1.1 | 10/04/2026 | Autenticação, Segurança CSRF, Normalização N:N, Interface de Resultados, Relatório PDF |
| 1.2 | 20/05/2026 | Sistema de Projetos, Painel Admin, Correções de Segurança, Responsividade Mobile, Tempo Real |

---

## 👥 Autores

| Nome | LinkedIn | GitHub |
|------|----------|--------|
| **Leonardo Henrique Máximo** | [LinkedIn](https://br.linkedin.com/in/leonardo-henrique-maximo-297370287) | [GitHub](https://github.com/leonardo23hm) |
| **Maira Aparecida Michelon** | [LinkedIn](https://br.linkedin.com/in/maira-michelon-a9b78a31) | [GitHub](https://github.com/mairamichelon) |

**Projeto Interdisciplinar II — Gestão da Tecnologia da Informação — IFPR**
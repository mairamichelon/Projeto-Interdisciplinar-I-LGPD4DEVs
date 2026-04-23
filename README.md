# LGPD4DEVS 🛡️
> Ecossistema técnico para implementação de Privacy by Design e conformidade com a LGPD.

<p align="center">
  <img src="https://github.com/user-attachments/assets/5e38ea64-64b2-4448-acfd-4ba4e237649a" alt="logo projeto LGPD4DEVS" width="300">
</p>

## 📌 Sobre o Projeto
O **LGPD4DEVS** é uma ferramenta prática projetada para traduzir a complexidade jurídica da Lei Geral de Proteção de Dados em ações técnicas executáveis. O foco principal é apoiar desenvolvedores e gestores de TI na proteção de dados de crianças e adolescentes, garantindo que a privacidade seja integrada desde a concepção da aplicação (**Privacy by Design**).

## 🚀 Funcionalidades Principais
* ✅ **Checklist Interativo:** Diagnóstico de conformidade em tempo real com lógica de pesos.
* 📊 **Score de Adequação:** Relatório visual de conformidade com barra de progresso dinâmica.
* 📖 **Biblioteca de Materiais:** Acesso a guias técnicos e artigos baseados nas falhas detectadas.
* ☁️ **Integração GitHub RAW:** Visualização de documentos técnicos diretamente do repositório.
* 📄 **Relatório Exportável:** Geração de relatório de conformidade otimizado para impressão (PDF).

## 🏗️ Arquitetura e Tecnologia
O projeto utiliza o padrão **MVC (Model-View-Controller)**, garantindo escalabilidade, segurança e separação de responsabilidades.

* **Linguagem:** PHP 8.3+
* **Arquitetura:** MVC com Front Controller (Roteamento via `.htaccess`)
* **Banco de Dados:** MySQL 8.4 (Utilizando PDO Singleton para conexões eficientes)
* **Design:** CSS3 Moderno (Responsivo e com suporte a Modais/Toasts)
* **Deploy:** Dockerizado para ambiente Render / Banco de Dados Gerenciado (Aiven)

### Estrutura de Pastas
```text
lgpd4devs/
├── app/                # Lógica da Aplicação (MVC)
├── config/             # Configurações globais e Database
├── database/           # Scripts de Schema SQL
├── public/             # DocumentRoot (Única pasta exposta via HTTP)
│   ├── css/            # Estilos Customizados
│   ├── img/            # Ativos Visuais (Logos/Ícones)
│   └── index.php       # Front Controller
├── materiais/          # Documentos técnicos e guias
└── Dockerfile          # Configuração de Infraestrutura
```

## 🔒 Segurança Implementada
Proteção CSRF: Tokens de validação em todos os formulários de ação.

Segurança de Diretório: Acesso restrito via Front Controller; arquivos de lógica e config ficam fora da raiz pública.

Gestão de Sessão: Regeneração de ID de sessão no login para prevenir Session Fixation.

Variáveis de Ambiente: Uso de arquivos .env para proteção de credenciais de banco de dados.

## 🌍 Alinhamento ODS (Agenda 2030)
Este projeto contribui diretamente para:

ODS 4 (Educação de Qualidade): Disseminação técnica de direitos digitais.

ODS 9 (Inovação e Infraestrutura): Fomento ao desenvolvimento de software ético.

ODS 16 (Paz e Justiça): Proteção contra exploração de dados de vulneráveis.

## 🛠️ Como Executar (Ambiente Local)
Certifique-se de ter o Laragon ou Docker instalado.

Clone o repositório: git clone https://github.com/mairamichelon/Projeto-Interdisciplinar-I-LGPD4DEVs.git

Renomeie o arquivo .env.example para .env e insira suas credenciais locais.

Importe o script em database/schema.sql para o seu MySQL.

Configure o servidor para apontar o DocumentRoot para a pasta /public.

## 👥 Autores

Leonardo Henrique Máximo - LinkedIn

Maira Aparecida Michelon - LinkedIn

Projeto Interdisciplinar - Gestão da Tecnologia da Informação
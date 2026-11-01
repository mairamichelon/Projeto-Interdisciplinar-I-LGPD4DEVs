-- ============================================================
-- LGPD4DEVS — Carga Completa de Dados (Seed Data)
-- Recuperado a partir do relatório oficial de produção
-- Total: 21 Perguntas (61 pontos), 22 Materiais e Vínculos N:N
-- Links dos materiais apontando para raw.githubusercontent.com
-- ============================================================

USE db_lgpd4devs;

-- ------------------------------------------------------------
-- 1. CATEGORIAS
-- ------------------------------------------------------------
INSERT INTO categorias (id, nome) VALUES
(1, 'Geral'),
(2, 'Infantojuvenil'),
(3, 'Segurança'),
(4, 'Boas Práticas')
ON DUPLICATE KEY UPDATE nome = VALUES(nome);

-- ------------------------------------------------------------
-- 2. MATERIAIS DE APOIO / BIBLIOTECA (22 Guias e Normativas Oficiais)
-- ------------------------------------------------------------
INSERT INTO materiais (id, titulo, categoria, descricao_curta, conteudo_detalhado, url_referencia) VALUES
(1, 'ECA Digital: Perguntas e Respostas', 'ECA',
    'FAQ focado no cumprimento do Artigo 14 da LGPD.',
    'Perguntas e respostas sobre as exigências legais e práticas do ECA Digital para plataformas voltadas a crianças e adolescentes.',
    'https://raw.githubusercontent.com/mairamichelon/Projeto-Interdisciplinar-I-LGPD4DEVs/main/materiais/18032026_perguntas_respostas_eca-digital.pdf'),

(2, 'Decreto 12.880 - ECA Digital', 'ECA',
    'Leitura essencial para entender a base jurídica do projeto.',
    'Decreto regulamentador que estabelece diretrizes e salvaguardas para proteção de crianças e adolescentes em plataformas digitais.',
    'https://raw.githubusercontent.com/mairamichelon/Projeto-Interdisciplinar-I-LGPD4DEVs/main/materiais/D12880%20Eca%20Digital.pdf'),

(3, 'Guia Orientativo: Cookies', 'ANPD',
    'Explica o conceito de consentimento livre e informado.',
    'Orientações da ANPD sobre cookies, rastreamento online e requisitos para obtenção de consentimento de usuários.',
    'https://raw.githubusercontent.com/mairamichelon/Projeto-Interdisciplinar-I-LGPD4DEVs/main/materiais/guia-orientativo-cookies-e-protecao-de-dados-pessoais%20%281%29.pdf'),

(4, 'Fascículo: Proteção de Dados', 'GUIA',
    'Visão panorâmica sobre direitos dos titulares e deveres dos agentes.',
    'Fascículo com fundamentos de proteção de dados, privacidade e responsabilidades legais de controladores e operadores.',
    'https://raw.githubusercontent.com/mairamichelon/Projeto-Interdisciplinar-I-LGPD4DEVs/main/materiais/fasciculo-protecao-de-dados.pdf'),

(5, 'Agentes de Tratamento e Encarregado', 'ANPD',
    'Esclarece as responsabilidades jurídicas de cada ator.',
    'Guia oficial da ANPD definindo atribuições e obrigações do Controlador, Operador e Encarregado pelo tratamento de dados pessoais (DPO).',
    'https://raw.githubusercontent.com/mairamichelon/Projeto-Interdisciplinar-I-LGPD4DEVs/main/materiais/guia_agentes_de_tratamento_e_encarregado___de%20dados.pdf'),

(6, 'Mecanismos de Aferição de Idade', 'TECNOLOGIA',
    'Fundamental para barrar acesso de menores mantendo a privacidade.',
    'Estudo da ANPD e diretrizes técnicas para implementação de métodos seguros e confiáveis de checagem etária, vedando a simples autodeclaração.',
    'https://raw.githubusercontent.com/mairamichelon/Projeto-Interdisciplinar-I-LGPD4DEVs/main/materiais/Mecanismos%20confi%C3%A1veis%20de%20aferi%C3%A7%C3%A3o%20de%20idade.pdf'),

(7, 'Radar: IA Generativa', 'TECNOLOGIA',
    'Riscos e boas práticas no desenvolvimento de sistemas de IA.',
    'Análise técnica da ANPD sobre os impactos da inteligência artificial generativa na privacidade e proteção de dados de crianças e adolescentes.',
    'https://raw.githubusercontent.com/mairamichelon/Projeto-Interdisciplinar-I-LGPD4DEVs/main/materiais/Radar_Tecnologico_IA_Generativa_ANPD.pdf'),

(8, 'Radar: Biometria e Privacidade', 'TECNOLOGIA',
    'Orientações da ANPD sobre implementação segura de biometria.',
    'Diretrizes sobre o tratamento de dados biométricos sensíveis, proporcionalidade e salvaguardas técnicas.',
    'https://raw.githubusercontent.com/mairamichelon/Projeto-Interdisciplinar-I-LGPD4DEVs/main/materiais/Radar%20Tecnol%C3%B3gico%20-%20Biometria%20-%20ANPD.pdf'),

(9, 'Estudo de Casos: Anonimização', 'TÉCNICO',
    'Diferença técnica entre pseudoanonimização e anonimização real.',
    'Exemplos práticos e técnicas criptográficas para desvinculação de dados e mitigação do risco de reidentificação.',
    'https://raw.githubusercontent.com/mairamichelon/Projeto-Interdisciplinar-I-LGPD4DEVs/main/materiais/estudo_de_casos_sobre_anonimizacao_de_dados_na_lgpd_.pdf'),

(10, 'Radar: Neurotecnologias', 'TECNOLOGIA',
    'Explora interfaces cérebro-computador sob a ótica da LGPD.',
    'Publicação da ANPD sobre os desafios éticos, técnicos e de privacidade envolvidos no uso de neurotecnologias e dados neurais.',
    'https://raw.githubusercontent.com/mairamichelon/Projeto-Interdisciplinar-I-LGPD4DEVs/main/materiais/Radar%20Tecnologico%204%20-%20Neurotecnologias.pdf'),

(11, 'Cartilha Oficial do ECA Digital', 'ECA',
    'Guia ilustrado de direitos e proteção infantojuvenil em meios digitais.',
    'Material oficial que consolida as regras de proteção integral de menores de idade no ambiente online.',
    'https://raw.githubusercontent.com/mairamichelon/Projeto-Interdisciplinar-I-LGPD4DEVs/main/materiais/Cartilha-Eca-Digital.pdf'),

(12, 'Fascículo: Controle Parental', 'GUIA',
    'Boas práticas e ferramentas para implementação de supervisão parental efetiva.',
    'Orientações para desenvolvedores criarem recursos de controle parental, limites de tempo de uso e visibilidade para os pais.',
    'https://raw.githubusercontent.com/mairamichelon/Projeto-Interdisciplinar-I-LGPD4DEVs/main/materiais/fasciculo_controle_parental.pdf'),

(13, 'Nota Técnica: Regulação de Loot Boxes', 'ANPD',
    'Diretrizes sobre proibição de caixas de recompensa pagas com itens aleatórios para menores.',
    'Nota técnica que veda a exploração de mecânicas de apostas e caixas de recompensas aleatórias pagas em jogos direcionados a crianças e adolescentes.',
    'https://raw.githubusercontent.com/mairamichelon/Projeto-Interdisciplinar-I-LGPD4DEVs/main/materiais/nt_loot_boxes_-aprovada.pdf'),

(14, 'Guia: Privacidade desde a Concepção', 'TÉCNICO',
    'Metodologia Privacy by Design e Privacy by Default aplicada a software.',
    'Como incorporar privacidade e proteção de dados em cada etapa da arquitetura e ciclo de vida do desenvolvimento.',
    'https://raw.githubusercontent.com/mairamichelon/Projeto-Interdisciplinar-I-LGPD4DEVs/main/materiais/guia_privacidade_concepcao-1.pdf'),

(15, 'Guia: Gerenciamento de Vulnerabilidades', 'TÉCNICO',
    'Processos de detecção, correção e fluxo de SLA para incidentes de segurança.',
    'Padrões técnicos de segurança para resposta rápida a falhas, canal de denúncias e correção de brechas em sistemas.',
    'https://raw.githubusercontent.com/mairamichelon/Projeto-Interdisciplinar-I-LGPD4DEVs/main/materiais/guia_gerenciamento_vulnerabilidades-1.pdf'),

(16, 'Guia de Requisitos Mínimos para APIs', 'TÉCNICO',
    'Padrões de autenticação, criptografia e proteção contra vulnerabilidades em endpoints.',
    'Boas práticas recomendadas para segurança de APIs, controle de acesso e proteção de payloads transmitidos via rede.',
    'https://raw.githubusercontent.com/mairamichelon/Projeto-Interdisciplinar-I-LGPD4DEVs/main/materiais/guia_requisitos_minimos_apis-1.pdf'),

(17, 'Guia de Requisitos Mínimos para Apps', 'TÉCNICO',
    'Segurança no armazenamento local, permissões do SO e comunicação externa em apps.',
    'Requisitos técnicos de segurança para desenvolvimento de aplicações móveis (iOS/Android) que tratam dados de usuários.',
    'https://raw.githubusercontent.com/mairamichelon/Projeto-Interdisciplinar-I-LGPD4DEVs/main/materiais/guia_requisitos_minimos_apps-1.pdf'),

(18, 'Guia de Requisitos Mínimos para Web', 'TÉCNICO',
    'Prevenção a ataques CSRF, XSS, injeção de SQL e gestão segura de sessões.',
    'Checklist técnico para segurança e integridade de aplicações web, blindagem contra vulnerabilidades OWASP.',
    'https://raw.githubusercontent.com/mairamichelon/Projeto-Interdisciplinar-I-LGPD4DEVs/main/materiais/guia_requisitos_minimos_web-1.pdf'),

(19, 'Guia de Requisitos e Obrigações Legais', 'GUIA',
    'Resumo prático das obrigações do desenvolvedor perante a LGPD e a ANPD.',
    'Mapeamento das principais exigências regulatórias, transparência com os usuários e mitigação de riscos de sanções.',
    'https://raw.githubusercontent.com/mairamichelon/Projeto-Interdisciplinar-I-LGPD4DEVs/main/materiais/guia_requisitos_obrigacoes-1.pdf'),

(20, 'Guia de Uso Responsável de IA (CGU)', 'TECNOLOGIA',
    'Auditoria de modelos algorítmicos, explicabilidade e mitigação de riscos.',
    'Orientações da CGU sobre governança de IA, avaliação de riscos psicossociais e segurança de algoritmos que impactam pessoas.',
    'https://raw.githubusercontent.com/mairamichelon/Projeto-Interdisciplinar-I-LGPD4DEVs/main/materiais/Guia_de_uso_respons%C3%A1vel_de_IA_v5_publicacao%20CGU.pdf'),

(21, 'Lei Geral de Proteção de Dados (Lei nº 13.709/2018)', 'LEGISLAÇÃO',
    'Texto integral da LGPD com ênfase no Artigo 14 (crianças e adolescentes).',
    'Legislação federal com os princípios fundamentais da privacidade, direitos dos titulares e regras de tratamento no Brasil.',
    'https://raw.githubusercontent.com/mairamichelon/Projeto-Interdisciplinar-I-LGPD4DEVs/main/materiais/L13709.pdf'),

(22, 'ECA Digital (Lei nº 15.211/2025)', 'ECA',
    'Texto oficial da lei que amplia a proteção a crianças e adolescentes em ambientes digitais.',
    'Normas e vedações legais a perfilamento publicitário, obrigações de canais de denúncia e salvaguardas para o público infantojuvenil.',
    'https://raw.githubusercontent.com/mairamichelon/Projeto-Interdisciplinar-I-LGPD4DEVs/main/materiais/L15211.pdf')
ON DUPLICATE KEY UPDATE 
    titulo = VALUES(titulo), 
    categoria = VALUES(categoria), 
    descricao_curta = VALUES(descricao_curta), 
    conteudo_detalhado = VALUES(conteudo_detalhado), 
    url_referencia = VALUES(url_referencia);

-- ------------------------------------------------------------
-- 3. PERGUNTAS DO CHECKLIST (21 Itens - 61 Pontos)
-- ------------------------------------------------------------
INSERT INTO perguntas (id, categoria_id, texto, peso) VALUES
(1, 1, 'Existe consentimento específico do responsável legal e a conta do menor está tecnicamente vinculada a ele para fins de controle e monitoramento?', 5),
(2, 1, 'Existe uma Política de Privacidade clara e escrita em linguagem simples?', 4),
(3, 1, 'A plataforma opera por padrão com o grau mais elevado de proteção da privacidade (Privacy by Default), sem exigir que o usuário altere configurações para estar seguro?', 3),
(4, 1, 'São utilizados mecanismos confiáveis de verificação de idade, vedando a simples autodeclaração para acesso a conteúdos impróprios ou restritos?', 3),
(5, 1, 'O sistema impede a criação de perfis comportamentais de menores para fins de direcionamento de publicidade comercial?', 3),
(6, 1, 'A linguagem visual e a UX são adequadas e compreensíveis para o estágio de desenvolvimento da faixa etária específica que acessa a aplicação?', 2),
(7, 1, 'O projeto garante a proteção prioritária e o melhor interesse da criança/adolescente desde a sua concepção técnica (Privacy by Design)?', 3),
(8, 1, 'A plataforma oferece ferramentas fáceis para que os pais limitem o tempo de uso e monitorem as interações sociais do menor?', 2),
(9, 1, 'Existem mecanismos técnicos para restringir ou exigir autorização prévia para compras dentro do app e microtransações?', 2),
(10, 1, 'Funcionalidades de chat ou troca de mídia são restritas por padrão a usuários autorizados e dependem de consentimento parental para ativação?', 3),
(11, 1, 'O aplicativo disponibiliza um botão de denúncia visível e de fácil acesso para reportar assédio ou violações de direitos?', 2),
(12, 1, 'O sistema bloqueia a oferta de loot boxes (recompensas aleatórias pagas) em funcionalidades acessíveis a crianças e adolescentes?', 2),
(13, 1, 'Há um fluxo definido para registro de incidentes e um SLA estabelecido para a remoção de conteúdos ilícitos reportados?', 2),
(14, 1, 'Caso utilize inteligência artificial, o sistema passa por revisões regulares para garantir que os algoritmos não tragam riscos psicossociais aos menores?', 3),
(15, 1, 'O aplicativo coleta dados de crianças ou adolescentes?', 3),
(16, 1, 'Os dados coletados são estritamente necessários para a atividade do app? (Minimização)', 3),
(17, 1, 'O sistema utiliza criptografia/hash para armazenar senhas e dados sensíveis?', 5),
(18, 1, 'O design da interface evita recursos que estimulem o uso excessivo ou compulsivo, como reprodução automática (autoplay) ou recompensas por tempo de permanência?', 2),
(19, 1, 'O rastreamento de localização está desativado por padrão e exige aviso prévio e claro aos responsáveis antes de qualquer ativação?', 3),
(20, 1, 'Existem processos (automatizados ou humanos) para identificar e remover rapidamente conteúdos de violência, abuso ou automutilação?', 3),
(21, 1, 'Existe um mecanismo simplificado para que o menor ou seu responsável solicite a exclusão definitiva dos dados coletados?', 3)
ON DUPLICATE KEY UPDATE categoria_id = VALUES(categoria_id), texto = VALUES(texto), peso = VALUES(peso);

-- ------------------------------------------------------------
-- 4. RELAÇÃO PERGUNTA x MATERIAL (N:N)
-- ------------------------------------------------------------
DELETE FROM pergunta_material;

INSERT INTO pergunta_material (pergunta_id, material_id) VALUES
-- Pergunta 1
(1, 1),
(1, 2),
(1, 3),

-- Pergunta 2
(2, 4),
(2, 5),

-- Pergunta 3
(3, 14),
(3, 19),

-- Pergunta 4
(4, 6),
(4, 22),

-- Pergunta 5
(5, 11),
(5, 22),

-- Pergunta 6
(6, 11),
(6, 21),

-- Pergunta 7
(7, 14),
(7, 21),

-- Pergunta 8
(8, 12),
(8, 1),

-- Pergunta 9
(9, 1),
(9, 17),

-- Pergunta 10
(10, 12),
(10, 18),

-- Pergunta 11
(11, 1),
(11, 11),

-- Pergunta 12
(12, 13),
(12, 22),

-- Pergunta 13
(13, 15),
(13, 1),

-- Pergunta 14
(14, 20),
(14, 7),

-- Pergunta 15
(15, 1),
(15, 2),
(15, 6),
(15, 7),

-- Pergunta 16
(16, 8),
(16, 9),

-- Pergunta 17
(17, 10),
(17, 9),
(17, 16),

-- Pergunta 18
(18, 1),
(18, 11),

-- Pergunta 19
(19, 17),
(19, 21),

-- Pergunta 20
(20, 1),
(20, 15),

-- Pergunta 21
(21, 21),
(21, 4);

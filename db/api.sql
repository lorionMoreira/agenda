CREATE TABLE agenda.area_atuacao
(
  id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  ordem int(11),
  titulo varchar(100),
  descricao text,
  cor varchar(45),
  created_at timestamp,
  updated_at timestamp NULL,
  imagem text
);
INSERT INTO agenda.area_atuacao (id, ordem, titulo, descricao, cor, created_at, updated_at, imagem) VALUES (1, 1, 'Criminal', 'Em matéria criminal, a DPE/BA presta orientação jurídica e promove a defesa de todos os cidadãos que estejam sendo acusados da prática de algum ilícito penal ou que desejam a revisão de sua condenação. A atuação se dá em todos os graus da Justiça Estadual, assim como nos processos que tramitam junto à Justiça Militar Estadual e aos Tribunais Superiores, em Brasília. Entre as suas atribuições, encontra-se, ainda, a de acompanhar os flagrantes.', '#f44336', '2018-11-27 18:55:23', '2018-11-27 18:55:23', null);
INSERT INTO agenda.area_atuacao (id, ordem, titulo, descricao, cor, created_at, updated_at, imagem) VALUES (5, null, 'Juizados Especiais', 'Essa especializada corresponde à atuação dos defensores públicos em qualquer feito que tramite perante aos Juizados Especiais Criminais, garantindo ao cidadão assistência jurídica gratuita, desde o atendimento inicial até a conclusão do processo criminal.', '#ff9800', null, null, null);

CREATE TABLE agenda.documentos
(
  id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  area_atuacao_id int(11),
  nome text,
  CONSTRAINT fk_area_atuacao FOREIGN KEY (area_atuacao_id) REFERENCES agenda.area_atuacao (id)
);
CREATE INDEX area_atuacao_id_idx ON agenda.documentos (area_atuacao_id);
INSERT INTO agenda.documentos (id, area_atuacao_id, nome) VALUES (1, 1, 'Nome completo do preso ou acusado;');
INSERT INTO agenda.documentos (id, area_atuacao_id, nome) VALUES (2, 1, 'Número do processo;');
INSERT INTO agenda.documentos (id, area_atuacao_id, nome) VALUES (3, 1, 'Cópia do RG ou outro documento pessoal do acusado (se houver);');
INSERT INTO agenda.documentos (id, area_atuacao_id, nome) VALUES (4, 1, 'Comprovante de residência;');
INSERT INTO agenda.documentos (id, area_atuacao_id, nome) VALUES (5, 1, 'Comprovante de trabalho, de preferência cópia de Carteira de Trabalho, ou outro que ateste atividade lícita;');
INSERT INTO agenda.documentos (id, area_atuacao_id, nome) VALUES (6, 1, 'Todos os demais documentos que envolvam o problema levado ao conhecimento da Defensoria.');

CREATE TABLE agenda.duvida
(
  id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  pergunta text,
  resposta text,
  area_atuacao_id int(11)
);
INSERT INTO agenda.duvida (id, pergunta, resposta, area_atuacao_id) VALUES (1, 'Quem pode ser atendido pela Defensoria Pública em processos criminais?', 'Qualquer pessoa acusada da prática de crime (ou contravenção penal) pode ser defendida pela Defensoria Pública, independentemente da sua renda.', 1);
INSERT INTO agenda.duvida (id, pergunta, resposta, area_atuacao_id) VALUES (2, 'Um parente ou conhecido foi preso. O que fazer?', 'Procurar e/ou solicitar atendimento de Defensor Público do município ou Comarca da prisão, a fim de buscar orientação sobre o motivo da prisão e a defesa a ser realizada no caso.', 1);
INSERT INTO agenda.duvida (id, pergunta, resposta, area_atuacao_id) VALUES (3, 'Como realizar visita a um parente que se encontra preso?', 'Em primeiro lugar, procurar a administração do estabelecimento prisional para solicitar a confecção de carteira de visitante. Caso não seja possível, procurar a Defensoria Pública do município mais próximo para realizar pedido de autorização para o juiz responsável. Nesse caso, será necessário comprovação de parentesco entre solicitante e preso, bem como documento que comprove que o pedido de visita foi negado pela administração da unidade penal.', 1);
INSERT INTO agenda.duvida (id, pergunta, resposta, area_atuacao_id) VALUES (4, 'O que fazer quando receber documento do Oficial de Justiça informando que estou sendo processado criminalmente?', 'Procurar urgentemente a Defensoria Pública do município mais próximo em que reside, levando documentação para agilizar atendimento, a fim de que o Defensor Público possa prestar orientação jurídica e realizar defesa efetiva no processo.', 1);

CREATE TABLE agenda.status_solicitacoes
(
  id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  nome varchar(100) NOT NULL,
  created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
  updated_at datetime
);
INSERT INTO agenda.status_solicitacoes (id, nome, created_at, updated_at) VALUES (1, 'Aberta', '2018-12-04 10:02:20', null);
INSERT INTO agenda.status_solicitacoes (id, nome, created_at, updated_at) VALUES (2, 'Agendado', '2018-12-04 10:02:20', null);
INSERT INTO agenda.status_solicitacoes (id, nome, created_at, updated_at) VALUES (3, 'Orientado', '2018-12-04 10:02:20', null);
INSERT INTO agenda.status_solicitacoes (id, nome, created_at, updated_at) VALUES (4, 'Cancelado pelo usuário', '2018-12-13 19:44:32', null);
INSERT INTO agenda.status_solicitacoes (id, nome, created_at, updated_at) VALUES (5, 'Cancelado pela DPE', '2018-12-13 19:44:32', null);

CREATE TABLE agenda.location
(
  id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  lat varchar(50),
  `long` varchar(50),
  nome varchar(256),
  endereco varchar(256),
  telefones varchar(256),
  atendimento text,
  observacoes text,
  principal int(11)
);
INSERT INTO agenda.location (id, lat, `long`, nome, endereco, telefones, atendimento, observacoes, principal) VALUES (1, '-12.940931', '-38.429981', 'Sede Administrativa – CAB', 'Avenida Ulisses Guimarães, nº 3.386, Edf. MultiCab Empresarial. CAB. 41219-400, Salvador-BA', '(071) 3117-9118, (071) 3117-9119', 'Atendimento: de segunda a sexta-feira das 8h às 17h.', 'Triagem: 7h30 às 11h30.', 1);

create table if not exists area_atuacao_location
(
  id              int auto_increment
    primary key,
  area_atuacao_id int not null,
  localization_id int not null,
  constraint fk_area_atuacao_1
  foreign key (area_atuacao_id) references area_atuacao (id),
  constraint fk_location_1
  foreign key (localization_id) references location (id)
);

create index fk_area_atuacao_idx
  on area_atuacao_location (area_atuacao_id);

create index fk_area_atuacao_idx_1
  on area_atuacao_location (area_atuacao_id);

create index fk_location_1_idx
  on area_atuacao_location (localization_id);

create table if not exists area_atuacao_documentos
(
  id              int auto_increment
    primary key,
  documentos_id   int not null,
  area_atuacao_id int not null,
  constraint fk_area_atuacao_2
  foreign key (area_atuacao_id) references area_atuacao (id),
  constraint fk_documentos_2
  foreign key (documentos_id) references documentos (id)
);

create index fk_area_atuacao_idx_2
  on area_atuacao_documentos (area_atuacao_id);

create index fk_documentos_idx_2
  on area_atuacao_documentos (documentos_id);

create table if not exists acoes_relacionada
(
  id              int auto_increment
    primary key,
  area_atuacao_id int  not null,
  nome            text null,
  constraint fk_area_atuacao_acoes_relacionadas
  foreign key (area_atuacao_id) references area_atuacao (id)
);

create index fk_area_atuacao_idx_acoes_relacionadas
  on acoes_relacionada (area_atuacao_id);


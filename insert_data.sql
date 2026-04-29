-- ============================================================
-- DONNÉES EXTRAITES DU PDF - Matière.pdf
-- ============================================================

-- ------------------------------------------------------------
-- 1. PARCOURS
-- ------------------------------------------------------------
INSERT INTO parcours (nom, responsable) VALUES
('Développement',               'Razafinjoelina Tahina'),
('Bases de Données et Réseaux', 'Rakotomalala Vahatriniaina'),
('Web et Design',               'Rabenanahary Rojo');


-- ------------------------------------------------------------
-- 2. COURS
-- ------------------------------------------------------------
-- Semestre 3 (commun aux 3 parcours)
INSERT INTO cours (code_ue, intitule, credits, semestre) VALUES
('INF201', 'Programmation orientée objet',                          6,  3),
('INF202', 'Bases de données objets',                              6,  3),
('INF203', 'Programmation système',                                4,  3),
('INF208', 'Réseaux informatiques',                                6,  3),
('MTH201', 'Méthodes numériques',                                  4,  3),
('ORG201', 'Bases de gestion',                                     4,  3),

-- Semestre 4
('INF204', 'Système d\'information géographique',                  6,  4),
('INF205', 'Système d\'information',                               6,  4),
('INF206', 'Interface Homme/Machine',                              6,  4),
('INF207', 'Eléments d\'algorithmique',                            6,  4),
('INF209', 'Web dynamique',                                        6,  4),
('INF210', 'Mini-projet de développement',                         10, 4),
('INF211', 'Mini-projet de bases de données et/ou de réseaux',    10, 4),
('INF212', 'Mini-projet de Web et design',                         10, 4),
('MTH202', 'Analyse des données',                                  4,  4),
('MTH203', 'MAO',                                                  4,  4),
('MTH204', 'Géométrie',                                            4,  4),
('MTH205', 'Equations différentielles',                            4,  4),
('MTH206', 'Optimisation',                                         4,  4);


-- ------------------------------------------------------------
-- 3. PARCOURS_COURS
-- Semestre 3 : commun aux 3 parcours (obligatoire)
-- IDs cours attendus : INF201=1, INF202=2, INF203=3, INF208=4, MTH201=5, ORG201=6
-- ------------------------------------------------------------

-- Parcours 1 - Développement : S3
INSERT INTO parcours_cours (parcours_id, cours_id, est_optionnel, groupe_option) VALUES
(1, 1,  FALSE, NULL),
(1, 2,  FALSE, NULL),
(1, 3,  FALSE, NULL),
(1, 4,  FALSE, NULL),
(1, 5,  FALSE, NULL),
(1, 6,  FALSE, NULL);

-- Parcours 2 - Bases de Données et Réseaux : S3
INSERT INTO parcours_cours (parcours_id, cours_id, est_optionnel, groupe_option) VALUES
(2, 1,  FALSE, NULL),
(2, 2,  FALSE, NULL),
(2, 3,  FALSE, NULL),
(2, 4,  FALSE, NULL),
(2, 5,  FALSE, NULL),
(2, 6,  FALSE, NULL);

-- Parcours 3 - Web et Design : S3
INSERT INTO parcours_cours (parcours_id, cours_id, est_optionnel, groupe_option) VALUES
(3, 1,  FALSE, NULL),
(3, 2,  FALSE, NULL),
(3, 3,  FALSE, NULL),
(3, 4,  FALSE, NULL),
(3, 5,  FALSE, NULL),
(3, 6,  FALSE, NULL);

-- ------------------------------------------------------------
-- Semestre 4 : Parcours 1 - DÉVELOPPEMENT
-- INF204=7, INF205=8, INF206=9, INF207=10, INF210=12
-- MTH203=16, MTH204=17, MTH205=18, MTH206=19
-- ------------------------------------------------------------
INSERT INTO parcours_cours (parcours_id, cours_id, est_optionnel, groupe_option) VALUES
(1, 7,  TRUE,  'S4_DEV_OPT1'),  -- INF204
(1, 8,  TRUE,  'S4_DEV_OPT1'),  -- INF205
(1, 9,  TRUE,  'S4_DEV_OPT1'),  -- INF206
(1, 10, FALSE, NULL),            -- INF207 obligatoire
(1, 12, FALSE, NULL),            -- INF210 mini-projet dev
(1, 17, TRUE,  'S4_DEV_OPT2'),  -- MTH204
(1, 18, TRUE,  'S4_DEV_OPT2'),  -- MTH205
(1, 19, TRUE,  'S4_DEV_OPT2'),  -- MTH206
(1, 16, FALSE, NULL);            -- MTH203 obligatoire

-- ------------------------------------------------------------
-- Semestre 4 : Parcours 2 - BASES DE DONNÉES ET RÉSEAUX
-- INF205=8, INF204=7, INF206=9, INF207=10, INF211=13
-- MTH202=15, MTH205=18, MTH206=19, MTH203=16
-- ------------------------------------------------------------
INSERT INTO parcours_cours (parcours_id, cours_id, est_optionnel, groupe_option) VALUES
(2, 8,  FALSE, NULL),            -- INF205 obligatoire
(2, 7,  TRUE,  'S4_BDR_OPT1'),  -- INF204
(2, 9,  TRUE,  'S4_BDR_OPT1'),  -- INF206
(2, 10, TRUE,  'S4_BDR_OPT1'),  -- INF207
(2, 13, FALSE, NULL),            -- INF211 mini-projet BDR
(2, 15, TRUE,  'S4_BDR_OPT2'),  -- MTH202
(2, 18, TRUE,  'S4_BDR_OPT2'),  -- MTH205
(2, 19, TRUE,  'S4_BDR_OPT2'),  -- MTH206
(2, 16, FALSE, NULL);            -- MTH203 obligatoire

-- ------------------------------------------------------------
-- Semestre 4 : Parcours 3 - WEB ET DESIGN
-- INF204=7, INF205=8, INF206=9, INF209=11, INF212=14
-- MTH202=15, MTH204=17, MTH206=19, MTH203=16
-- ------------------------------------------------------------
INSERT INTO parcours_cours (parcours_id, cours_id, est_optionnel, groupe_option) VALUES
(3, 7,  TRUE,  'S4_WEB_OPT1'),  -- INF204
(3, 8,  TRUE,  'S4_WEB_OPT1'),  -- INF205
(3, 9,  TRUE,  'S4_WEB_OPT1'),  -- INF206
(3, 11, FALSE, NULL),            -- INF209 Web dynamique
(3, 14, FALSE, NULL),            -- INF212 mini-projet Web
(3, 15, TRUE,  'S4_WEB_OPT2'),  -- MTH202
(3, 17, TRUE,  'S4_WEB_OPT2'),  -- MTH204
(3, 19, TRUE,  'S4_WEB_OPT2'),  -- MTH206
(3, 16, FALSE, NULL);            -- MTH203 obligatoire


-- ------------------------------------------------------------
-- 4. ÉTUDIANTS (exemples fictifs)
-- ------------------------------------------------------------
INSERT INTO etudiants (nom, prenoms, date_naissance, lieu_naissance) VALUES
('RAKOTO',      'Jean Pierre',       '2003-05-14', 'Antananarivo'),
('RASOA',       'Marie Hélène',      '2003-08-22', 'Fianarantsoa'),
('RANDRIA',     'Luc Fanantenana',   '2002-11-30', 'Toamasina'),
('RABEMANANA',  'Christelle Nadia',  '2003-03-17', 'Mahajanga'),
('ANDRIANTSOA', 'Paul Erick',        '2002-07-09', 'Antsiranana');


-- ------------------------------------------------------------
-- 5. INSCRIPTIONS (L2, année 2024-2025)
-- ------------------------------------------------------------
INSERT INTO inscriptions (etudiant_id, parcours_id, niveau, annee_universitaire, matricule) VALUES
(1, 1, 'L2', '2024-2025', 'ETU004040'),
(2, 2, 'L2', '2024-2025', 'ETU004041'),
(3, 3, 'L2', '2024-2025', 'ETU004042'),
(4, 1, 'L2', '2024-2025', 'ETU004043'),
(5, 2, 'L2', '2024-2025', 'ETU004044');


-- ------------------------------------------------------------
-- 6. NOTES (S3 - exemples fictifs)
-- ------------------------------------------------------------
INSERT INTO notes (inscription_id, cours_id, note) VALUES
-- Etudiant 1 (DEV)
(1, 1, 14.50), (1, 2, 13.00), (1, 3, 11.75),
(1, 4, 15.00), (1, 5, 12.50), (1, 6, 10.00),
-- Etudiant 2 (BDR)
(2, 1, 16.00), (2, 2, 17.50), (2, 3, 14.00),
(2, 4, 13.25), (2, 5, 11.00), (2, 6, 12.00),
-- Etudiant 3 (WEB)
(3, 1, 12.00), (3, 2, 14.00), (3, 3, 10.50),
(3, 4, 11.00), (3, 5, 13.75), (3, 6,  9.50),
-- Etudiant 4 (DEV)
(4, 1, 18.00), (4, 2, 15.50), (4, 3, 16.00),
(4, 4, 17.00), (4, 5, 14.50), (4, 6, 13.00),
-- Etudiant 5 (BDR)
(5, 1, 11.00), (5, 2, 12.50), (5, 3,  9.75),
(5, 4, 10.00), (5, 5, 13.00), (5, 6, 11.50);
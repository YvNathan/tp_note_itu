-- ============================================================
-- DONNÉES EXTRAITES DU PDF - Matière.pdf
-- ============================================================

-- ------------------------------------------------------------
-- 1. PARCOURS
-- ------------------------------------------------------------
INSERT INTO parcours (id, nom, responsable) VALUES
(1, 'Développement',              'Razafinjoelina Tahina'),
(2, 'Bases de Données et Réseaux','Rakotomalala Vahatriniaina'),
(3, 'Web et Design',              'Rabenanahary Rojo');


-- ------------------------------------------------------------
-- 2. COURS
-- ------------------------------------------------------------
-- Semestre 3 (commun aux 3 parcours)
INSERT INTO cours (id, code_ue, intitule, credits, semestre) VALUES
(1,  'INF201', 'Programmation orientée objet',       6,  3),
(2,  'INF202', 'Bases de données objets',            6,  3),
(3,  'INF203', 'Programmation système',              4,  3),
(4,  'INF208', 'Réseaux informatiques',              6,  3),
(5,  'MTH201', 'Méthodes numériques',                4,  3),
(6,  'ORG201', 'Bases de gestion',                   4,  3),

-- Semestre 4 - UE partagées entre parcours
(7,  'INF204', 'Système d\'information géographique',6,  4),
(8,  'INF205', 'Système d\'information',             6,  4),
(9,  'INF206', 'Interface Homme/Machine',            6,  4),
(10, 'INF207', 'Eléments d\'algorithmique',          6,  4),
(11, 'INF209', 'Web dynamique',                      6,  4),
(12, 'INF210', 'Mini-projet de développement',       10, 4),
(13, 'INF211', 'Mini-projet de bases de données et/ou de réseaux', 10, 4),
(14, 'INF212', 'Mini-projet de Web et design',       10, 4),
(15, 'MTH202', 'Analyse des données',                4,  4),
(16, 'MTH203', 'MAO',                                4,  4),
(17, 'MTH204', 'Géométrie',                          4,  4),
(18, 'MTH205', 'Equations différentielles',          4,  4),
(19, 'MTH206', 'Optimisation',                       4,  4);


-- ------------------------------------------------------------
-- 3. PARCOURS_COURS
-- Semestre 3 : commun aux 3 parcours (obligatoire)
-- ------------------------------------------------------------

-- Parcours 1 - Développement : S3
INSERT INTO parcours_cours (parcours_id, cours_id, est_optionnel, groupe_option) VALUES
(1, 1,  FALSE, NULL),  -- INF201
(1, 2,  FALSE, NULL),  -- INF202
(1, 3,  FALSE, NULL),  -- INF203
(1, 4,  FALSE, NULL),  -- INF208
(1, 5,  FALSE, NULL),  -- MTH201
(1, 6,  FALSE, NULL);  -- ORG201

-- Parcours 2 - BDR : S3
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
-- ------------------------------------------------------------
-- 1 UE parmi INF204 / INF205 / INF206 (groupe optionnel)
INSERT INTO parcours_cours (parcours_id, cours_id, est_optionnel, groupe_option) VALUES
(1, 7,  TRUE,  'S4_DEV_OPT1'),  -- INF204
(1, 8,  TRUE,  'S4_DEV_OPT1'),  -- INF205
(1, 9,  TRUE,  'S4_DEV_OPT1'),  -- INF206
-- INF207 obligatoire
(1, 10, FALSE, NULL),            -- INF207
-- INF210 obligatoire
(1, 12, FALSE, NULL),            -- INF210 mini-projet dev
-- 1 UE parmi MTH204 / MTH205 / MTH206
(1, 17, TRUE,  'S4_DEV_OPT2'),  -- MTH204
(1, 18, TRUE,  'S4_DEV_OPT2'),  -- MTH205
(1, 19, TRUE,  'S4_DEV_OPT2'),  -- MTH206
-- MTH203 obligatoire
(1, 16, FALSE, NULL);            -- MTH203


-- ------------------------------------------------------------
-- Semestre 4 : Parcours 2 - BASES DE DONNÉES ET RÉSEAUX
-- ------------------------------------------------------------
-- INF205 obligatoire
INSERT INTO parcours_cours (parcours_id, cours_id, est_optionnel, groupe_option) VALUES
(2, 8,  FALSE, NULL),            -- INF205
-- 1 UE parmi INF204 / INF206 / INF207
(2, 7,  TRUE,  'S4_BDR_OPT1'),  -- INF204
(2, 9,  TRUE,  'S4_BDR_OPT1'),  -- INF206
(2, 10, TRUE,  'S4_BDR_OPT1'),  -- INF207
-- INF211 obligatoire
(2, 13, FALSE, NULL),            -- INF211 mini-projet BDR
-- 1 UE parmi MTH202 / MTH205 / MTH206
(2, 15, TRUE,  'S4_BDR_OPT2'),  -- MTH202
(2, 18, TRUE,  'S4_BDR_OPT2'),  -- MTH205
(2, 19, TRUE,  'S4_BDR_OPT2'),  -- MTH206
-- MTH203 obligatoire
(2, 16, FALSE, NULL);            -- MTH203


-- ------------------------------------------------------------
-- Semestre 4 : Parcours 3 - WEB ET DESIGN
-- ------------------------------------------------------------
-- 1 UE parmi INF204 / INF205 / INF206
INSERT INTO parcours_cours (parcours_id, cours_id, est_optionnel, groupe_option) VALUES
(3, 7,  TRUE,  'S4_WEB_OPT1'),  -- INF204
(3, 8,  TRUE,  'S4_WEB_OPT1'),  -- INF205
(3, 9,  TRUE,  'S4_WEB_OPT1'),  -- INF206
-- INF209 obligatoire
(3, 11, FALSE, NULL),            -- INF209 Web dynamique
-- INF212 obligatoire
(3, 14, FALSE, NULL),            -- INF212 mini-projet Web
-- 1 UE parmi MTH202 / MTH204 / MTH206
(3, 15, TRUE,  'S4_WEB_OPT2'),  -- MTH202
(3, 17, TRUE,  'S4_WEB_OPT2'),  -- MTH204
(3, 19, TRUE,  'S4_WEB_OPT2'),  -- MTH206
-- MTH203 obligatoire
(3, 16, FALSE, NULL);            -- MTH203


-- ------------------------------------------------------------
-- 4. ÉTUDIANTS (exemples fictifs)
-- ------------------------------------------------------------
INSERT INTO etudiants (id, nom, prenoms, date_naissance, lieu_naissance) VALUES
(1, 'RAKOTO',     'Jean Pierre',        '2003-05-14', 'Antananarivo'),
(2, 'RASOA',      'Marie Hélène',       '2003-08-22', 'Fianarantsoa'),
(3, 'RANDRIA',    'Luc Fanantenana',    '2002-11-30', 'Toamasina'),
(4, 'RABEMANANA', 'Christelle Nadia',   '2003-03-17', 'Mahajanga'),
(5, 'ANDRIANTSOA','Paul Erick',         '2002-07-09', 'Antsiranana');


-- ------------------------------------------------------------
-- 5. INSCRIPTIONS (L2, année 2024-2025)
-- ------------------------------------------------------------
INSERT INTO inscriptions (id, etudiant_id, parcours_id, niveau, annee_universitaire, matricule) VALUES
(1, 1, 1, 'L2', '2024-2025', 'INF-DEV-2024-001'),
(2, 2, 2, 'L2', '2024-2025', 'INF-BDR-2024-002'),
(3, 3, 3, 'L2', '2024-2025', 'INF-WEB-2024-003'),
(4, 4, 1, 'L2', '2024-2025', 'INF-DEV-2024-004'),
(5, 5, 2, 'L2', '2024-2025', 'INF-BDR-2024-005');


-- ------------------------------------------------------------
-- 6. NOTES (exemples pour le S3)
-- ------------------------------------------------------------
INSERT INTO notes (inscription_id, cours_id, note) VALUES
-- Etudiant 1 (DEV) - S3
(1, 1, 14.50), (1, 2, 13.00), (1, 3, 11.75),
(1, 4, 15.00), (1, 5, 12.50), (1, 6, 10.00),
-- Etudiant 2 (BDR) - S3
(2, 1, 16.00), (2, 2, 17.50), (2, 3, 14.00),
(2, 4, 13.25), (2, 5, 11.00), (2, 6, 12.00),
-- Etudiant 3 (WEB) - S3
(3, 1, 12.00), (3, 2, 14.00), (3, 3, 10.50),
(3, 4, 11.00), (3, 5, 13.75), (3, 6,  9.50),
-- Etudiant 4 (DEV) - S3
(4, 1, 18.00), (4, 2, 15.50), (4, 3, 16.00),
(4, 4, 17.00), (4, 5, 14.50), (4, 6, 13.00),
-- Etudiant 5 (BDR) - S3
(5, 1, 11.00), (5, 2, 12.50), (5, 3,  9.75),
(5, 4, 10.00), (5, 5, 13.00), (5, 6, 11.50);

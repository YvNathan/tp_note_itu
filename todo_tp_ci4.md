# ✅ TODO — TP CodeIgniter 4 (4h) — 29 avril 2026

---

## 🔐 Auth
- [ ] Contrôleur `AuthController` avec méthodes `login()` / `logout()`
- [ ] Vue login avec formulaire (valeurs par défaut `admin` / `admin123` pré-remplies)
- [ ] Filtre de session pour protéger toutes les routes
- [ ] Route `GET /login`, `POST /login`, `GET /logout`

---

## 👨‍🎓 Liste étudiants
- [ ] Contrôleur `EtudiantController@index` → liste tous les étudiants
- [ ] Vue liste avec nom, prénom, parcours, niveau
- [ ] Chaque étudiant est cliquable → redirige vers ses notes

---

## 📝 Formulaire saisie de note
- [ ] Contrôleur `NoteController@create` / `store`
- [ ] Sélectionner : étudiant, cours, note
- [ ] Peut être soumis plusieurs fois (pas de vérification d'unicité)
- [ ] Vue formulaire avec dropdowns dynamiques (depuis la BDD)

---

##  Affichage des notes d'un étudiant
Quand on clique sur un étudiant, afficher ses notes avec les onglets suivants :

- [ ] Onglet **S3** — notes du semestre 3
- [ ] Onglet **S4 option Dev** — notes S4 parcours Développement
- [ ] Onglet **S4 option BDRés** — notes S4 parcours Bases de Données et Réseaux
- [ ] Onglet **S4 option Web** — notes S4 parcours Web et Design
- [ ] Onglet **L2 option Dev** — notes S3 + S4 dev + moyenne globale
- [ ] Onglet **L2 option BDRés** — notes S3 + S4 bdr + moyenne globale
- [ ] Onglet **L2 option Web** — notes S3 + S4 web + moyenne globale

---

## 📐 Règles de gestion
- [ ] **Par matière** → prendre la **note maximale** (si saisie plusieurs fois)
- [ ] **Matières optionnelles** → prendre la matière du groupe qui a la **meilleure note**
- [ ] **Lien S3 / S4** → afficher les notes par semestre séparément
- [ ] **Lien L2** → afficher les notes des 2 semestres + **moyenne = (moyenne S3 + moyenne S4) / 2**

---

## 🎨 Design
- [ ] Intégrer le fichier **SCSS fourni** comme base du thème
- [ ] Personnaliser les couleurs, typographie, composants avec le SCSS
- [ ] Compiler le SCSS en CSS (`sass` ou via `npm run dev`)
- [ ] Appliquer le thème sur toutes les vues (login, liste, notes)

---

## 🗄️ Modèles
- [ ] `EtudiantModel` — requête liste + détail
- [ ] `NoteModel` — insert + requête notes par étudiant/semestre/parcours avec `MAX(note)` groupé par `cours_id`
- [ ] `CoursModel` — liste des cours pour le formulaire
- [ ] Gestion des optionnels : `GROUP BY groupe_option`, `MAX(note)` par groupe

---

## 🔀 Routes (`app/Config/Routes.php`)

```php
$routes->get('/',                       'AuthController::login');
$routes->post('/login',                 'AuthController::loginPost');
$routes->get('/logout',                 'AuthController::logout');
$routes->get('/etudiants',              'EtudiantController::index');
$routes->get('/etudiants/(:num)/notes', 'NoteController::show/$1');
$routes->get('/notes/ajouter',          'NoteController::create');
$routes->post('/notes/ajouter',         'NoteController::store');
```

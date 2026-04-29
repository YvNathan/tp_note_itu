# ✅ TODO — TP CodeIgniter 4 (Gestion des notes)

---

## 🔧 1. Setup du projet
- [ ] Créer le projet CodeIgniter 4
- [ ] Lancer le serveur (`php spark serve`)
- [ ] Configurer `.env` (base de données)

---

## 🗄️ 2. Base de données (MySQL)
- [ ] Créer la base `tp_notes`
- [ ] Créer table `etudiant`
- [ ] Créer table `matiere`
- [ ] Créer table `note`
- [ ] Insérer des données de test :
  - [ ] étudiants
  - [ ] matières (S3 / S4 + option)
  - [ ] notes

---

## 🔐 3. Login
- [ ] Créer controller `Login`
- [ ] Créer vue login
- [ ] Ajouter valeurs par défaut (admin / 1234)
- [ ] Vérifier login et rediriger vers `/etudiants`
- [ ] Ajouter session (`logged`)

---

## ➕ 4. Ajouter une note
- [ ] Créer controller `Note`
- [ ] Créer formulaire ajout note :
  - [ ] etudiant_id
  - [ ] matiere_id
  - [ ] valeur
- [ ] Implémenter insertion en base
- [ ] Permettre plusieurs ajouts (pas de restriction)

---

## 👨‍🎓 5. Liste des étudiants
- [ ] Créer controller `Etudiant`
- [ ] Récupérer tous les étudiants
- [ ] Créer vue liste
- [ ] Ajouter lien cliquable vers détails :
  - `/etudiant/notes/{id}`

---

## 📊 6. Détail d’un étudiant
- [ ] Créer méthode `notes($id)`
- [ ] Récupérer notes + matières (JOIN)
- [ ] Afficher :
  - [ ] nom matière
  - [ ] note

---

## 🎯 7. Règles de gestion (IMPORTANT)

### ✔ 7.1 Max par matière
- [ ] Regrouper par matière
- [ ] Prendre `MAX(note)`

---

### ✔ 7.2 Gestion S3
- [ ] Filtrer matières semestre = S3
- [ ] Afficher bloc S3

---

### ✔ 7.3 Gestion S4
- [ ] Filtrer matières semestre = S4
- [ ] Séparer par option :
  - [ ] dev
  - [ ] bddres
  - [ ] web

---

### ✔ 7.4 Matières optionnelles
- [ ] Comparer les options
- [ ] Garder la meilleure note uniquement

---

### ✔ 7.5 L2 (global)
- [ ] Combiner S3 + S4
- [ ] Calculer moyenne générale

---

## 🧠 8. Affichage final attendu

- [ ] Bloc S3
- [ ] Bloc S4 DEV
- [ ] Bloc S4 BDDRES
- [ ] Bloc S4 WEB
- [ ] Bloc L2 :
  - [ ] moyenne

---

## 🎨 9. Design (SCSS)
- [ ] Copier fichier SCSS fourni
- [ ] Compiler en CSS (`sass`)
- [ ] Lier CSS dans les vues
- [ ] Améliorer affichage :
  - [ ] tableaux
  - [ ] boutons
  - [ ] couleurs

---

## 🧪 10. Vérification finale
- [ ] Login fonctionne
- [ ] Ajout de notes OK
- [ ] Liste étudiants OK
- [ ] Détail étudiant OK
- [ ] Règles respectées :
  - [ ] max par matière
  - [ ] meilleure option
  - [ ] séparation S3 / S4
  - [ ] moyenne L2
- [ ] Design appliqué

---

## 🚀 BONUS (si temps)
- [ ] Ajouter dropdown au lieu d’ID
- [ ] Sécuriser login
- [ ] Ajouter validation formulaire
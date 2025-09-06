Library Management Project GitHub Repository: <https://github.com/Ailar01/Library_Project>



Descriere
Această aplicație web gestionează utilizatori și cărți pentru o bibliotecă online.  
Include funcționalități de login/register, CRUD pentru cărți, și administrarea împrumuturilor.

Scopul proiectului este de a simula modul real de funcționare al unei biblioteci digitale, folosind tehnologii moderne.

---

Tehnologii folosite
- PHP 8.0
- MySQL (bază de date)
- XAMPP (Apache server + phpMyAdmin)
- GitHub (versionare)
-VS Code (editor cod)

---

Cum rulezi proiectul
1. Clonează repository-ul:




2. Mută folderul proiectului în `htdocs` din XAMPP.
3. Pornește XAMPP și activează Apache+ MySQL.
4. În browser, accesează:


5. Importă baza de date în phpMyAdmin (folosind fișierul din folderul `database`).

---
UML Diagram
![UML Diagram](docs/uml.png)

User Stories
[Vezi User Stories](docs/user_stories.md)

Screenshots
Login Page
![Login](docs/login.png)

Books Page
![Books](docs/books.png)

Database in phpMyAdmin
![Database](docs/phpmyadmin.png)

Prompt Engineering
În timpul dezvoltării proiectului, am folosit ChatGPT pentru:
- Generarea exemplelor de cod PHP.
- Structurarea interogărilor SQL.
- Optimizarea designului aplicației.
- Sugestii de implementare a funcționalităților.

Această experiență m-a ajutat să obțin un proces de dezvoltare mai eficient și să învăț să formulez prompturi clare și utile.

---

Demo Video

[Watch the demo](https://youtu.be/WfUrOGYVr94)


> Notă: Video-ul arată funcționalitățile principale ale aplicației într-o prezentare rapidă.

---
## Automated Tests

These are simple CLI tests (no PHPUnit).

How to run:
```bash
php tests/validation_test.php
php tests/db_smoke.php

Expected output (examples):

OK  - Empty title should error
OK  - Non-numeric year should error
OK  - Future year should error
OK  - Valid data should have no errors
Passed: 4, Failed: 0

DB OK

tests/validation_test.php

tests/db_smoke.php

## Coding Standards

Block 1 — Coding Standards
I follow a light PSR-12 style:

- Order: read input → validate early → only then do DB work (early return on errors)
- SQL safety:prepared statements everywhere; no string concatenation for queries
- Output safety:escape user-provided values with `htmlspecialchars` when rendering
- Naming:clear descriptive names (e.g., `published_year`)
- Structure:small focused files; feature-based folders (`books/`, `users/`, `login-register/`)
- Docs:short comments / PHPDoc where helpful (e.g., `lib/validation.php`)
- Cleanliness: no dead code; no debug prints in production; error display disabled in prod

Block 2 — Design Pattern

## Design Pattern

I applied a simple "Singleton" for the database connection:

- File: `lib/DB.php` exposes `DB::conn(): PDO`
- Internally reuses the existing `db()` function and **caches** the PDO so one connection instance is shared.
- Example usage (in `books/create_book.php`): `$pdo = DB::conn();`

This complements my feature-based structure (a simple MVC-style separation by domain).


Structura proiectului
Library_Project/
│
├── books/ # Module legate de cărți (CRUD)
├── users/ # Module legate de utilizatori
├── login-register/ # Autentificare și înregistrare
├── docs/ # Documentație (UML, User Stories)
│ ├── uml.png
│ └── user_stories.md
├── vendor/ # Dependențe (composer)
├── database.php # Conexiunea la baza de date
├── test.php # Test pentru conexiune
└── README.md


---
Contribuții
Proiect realizat de Aylar Yazmyradova, grupa 233.

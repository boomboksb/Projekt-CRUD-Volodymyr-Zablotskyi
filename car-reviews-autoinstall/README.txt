Instrukcja AwardSpace (PL)
1) Panel → MySQL Databases → Create database/user. Zapisz host/user/hasło/nazwę.
2) phpMyAdmin → Import → wybierz `schema.sql` → Go.
3) Edytuj `db.php` i wstaw dane z punktu 1.
4) Wgraj wszystkie pliki do `public_html/car-opinie-site/` (FTP lub File Manager).
5) Wejdź: https://twojadomena/car-opinie-site/
Test API: /load_reviews.php, POST /save_review.php, POST /delete_review.php.

SZYBKA NAPRAWA (AwardSpace)
1) W panelu AwardSpace UTWÓRZ bazę (DB) i użytkownika. Zapisz host/user/hasło/nazwę.
2) phpMyAdmin: wybierz bazę po lewej → Import → wgraj ten plik: schema.sql.
   (Ten plik NIE zawiera CREATE DATABASE/USE — działa na hostingach z ograniczeniami).
3) Edytuj db.php — wstaw swoje dane dostępu z panelu.
4) Wgraj pliki do public_html/car-opinie-site/ i wejdź na /load_reviews.php — powinno zwrócić JSON.
5) Front (index.html + script.js) już używa endpointów PHP w tym samym katalogu.

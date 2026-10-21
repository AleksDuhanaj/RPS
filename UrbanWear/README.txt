URBANWEAR – NAVODILA ZA NAMESTITEV (XAMPP)

1. Namestitev
- Razširi mapo UrbanWear v C:\xampp\htdocs\UrbanWear
- Odpri XAMPP Control Panel in zaženi Apache ter MySQL.
- V brskalniku odpri http://localhost/phpmyadmin
- Izberi Import (Uvozi), izberi datoteko database.sql iz mape UrbanWear in klikni Go/Izvedi.
  SQL ustvari bazo urbanwear, tabeli users in products ter vstavi začetne izdelke.
- Odpri http://localhost/UrbanWear/

2. Administrator
Zaradi varnosti se administrator ustvari z ločenim enkratnim namestitvenim skriptom.
Najprej odpri http://localhost/UrbanWear/create-admin.php in vnesi uporabniško ime,
e-pošto ter močno geslo (vsaj 12 znakov). Po uspehu TAKOJ IZBRIŠI datoteko
create-admin.php iz mape UrbanWear. Nato se prijavi prek login.php.
Navadna registracija vedno ustvari vlogo user; obiskovalec si ne more sam določiti
administratorske vloge.

3. Funkcionalnosti
- Registracija: preverjanje uporabniškega imena/e-pošte, strežniška validacija,
  password_hash() in unikatni stolpci v MySQL.
- Prijava: password_verify(), PHP session, regeneracija ID seje.
- Odjava: POST obrazec, CSRF token, uničenje seje.
- Admin: preverjanje vloge na vsaki administratorski strani.
- CRUD: dodajanje, pregled, urejanje in brisanje izdelkov.
- Trgovina: seznam izdelkov iz MySQL, iskanje, filtriranje, podrobnosti, akcijske cene.
- Košarica: seja, količine, preverjanje zaloge in izračun vsote.
- JavaScript: odzivna navigacija, preverjanje ujemanja gesel in potrditev brisanja.
- CSS: odziven prikaz na računalniku, tablici in telefonu.

4. Opombe
- Slike se nalagajo iz zunanjih URL-jev, zato je za prikaz slik potrebna internetna povezava.
- Plačilo, naročila, pošiljanje e-pošte in dejanska dostava niso implementirani.
- To je učni projekt za lokalno okolje XAMPP, ne produkcijska trgovina.
- Če si v config.php nastavil geslo za MySQL root, ga vnesi v $dbpass.
- Pred oddajo preveri, da se vsi URL-ji slik odprejo. Po potrebi jih zamenjaj v phpMyAdmin.

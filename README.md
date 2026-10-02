# SnapMarket

Een tweedehands marktplaats in PHP + MySQL (PDO). Dit is de versie **t/m Hoofdstuk 3** van Blok 5.

## Starten

1. Clone deze repository en open de map in VS Code.
2. Start de omgeving: `docker compose up -d`
3. Website: [http://localhost](http://localhost)
4. phpMyAdmin: [http://localhost:8000](http://localhost:8000)

Bij de eerste start wordt `sql/snapmarket.sql` automatisch geïmporteerd in de database `snapmarket`.
Werkt dat niet (of wil je de database resetten)? Importeer `sql/snapmarket.sql` dan zelf via phpMyAdmin.

> Zijn poort 80, 8000 of 3307 al bezet door een ander project? Stop dat project (`docker compose down`) of zet in `.env` bijvoorbeeld `WEB_PORT=8080`.

## Testaccounts

| E-mail | Wachtwoord | Rol |
|---|---|---|
| admin@snapmarket.nl | admin123 | admin |
| lars@snapmarket.nl | welkom123 | user |
| sanne@snapmarket.nl | welkom123 | user |
| mehmet@snapmarket.nl | welkom123 | user |

## Opdracht: bugs oplossen

SnapMarket is bijna klaar t/m Hoofdstuk 3, maar er zitten **10 bugs** in. Sommige zie je meteen, andere vind je alleen als je bewust gaat testen of de code goed leest. Een paar zijn **beveiligingsproblemen** - die zijn het belangrijkst.

Dit hoort te werken (t/m Hoofdstuk 3):

- Alle queries via **PDO en prepared statements** (H1)
- Tekst met accenten en speciale tekens wordt goed opgeslagen en getoond (H1)
- Alle data van gebruikers wordt veilig getoond met `htmlspecialchars()` (H2)
- Formulieren worden gevalideerd, met duidelijke foutmeldingen (H2)
- Wachtwoorden worden gehasht opgeslagen; registreren en daarna inloggen werkt (H2)
- Een e-mailadres kan maar één keer gebruikt worden, met een nette melding (H2)
- Een verkoper kan **alleen zijn eigen** advertenties wijzigen; het formulier is correct ingevuld (H3)
- Prijzen worden in de database in centen opgeslagen en overal goed getoond (H3)

**Werkwijze**

1. Test elke pagina en elk formulier. Probeer ook dingen die een gebruiker *niet* zou moeten kunnen.
2. Gebruik de testaccounts hieronder en test ook met twee verschillende accounts.
3. Houd per bug bij: wat het probleem is, in welk bestand het zat en hoe je het hebt opgelost.
4. Maak per opgeloste bug een aparte commit met een duidelijke commit message.

**Tips:** kijk in phpMyAdmin wat er echt in de database staat, gebruik `var_dump()`, en probeer bij de opdrachten uit Hoofdstuk 1 en 2 (SQL-injectie, `<h1>` als titel) dezelfde aanvallen op SnapMarket.

## Nog te bouwen (Hoofdstuk 4 en verder)

- Soft delete en herstellen van advertenties (US-08, US-09) - kolom `deleted_at` bestaat al
- Watchlist met AJAX (US-11) - tabel `watchlist` bestaat al
- Filteren op categorie/conditie en zoeken (US-12, US-13)
- Rol-checks voor beheerders, custom error pages

## Bestandsstructuur

```
snapmarket/
├── docker-compose.yaml
├── sql/snapmarket.sql
└── www/
    ├── database.php              # PDO-connectie
    ├── functions.php             # prijs-functies, validatie, foutmeldingen
    ├── header.php / footer.php
    ├── index.php                 # home: categorieën + nieuwste items
    ├── items_index.php           # aanbod (?category_id=… voor één categorie)
    ├── item_card.php             # kaartje van één item (include)
    ├── item_detail.php
    ├── item_create.php / item_create_process.php
    ├── item_update.php / item_update_process.php
    ├── my_items.php
    ├── categories_index.php
    ├── register.php / register_process.php
    ├── login.php / login_process.php / logout.php
    └── css/style.css
```

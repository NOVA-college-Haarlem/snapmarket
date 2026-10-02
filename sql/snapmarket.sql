-- SnapMarket - database
-- Importeer dit bestand in de database `snapmarket` (via phpMyAdmin → Importeren).
-- Bij `docker compose up` wordt dit bestand automatisch geïmporteerd als de database nog leeg is.
--
-- Testaccounts (wachtwoord tussen haakjes):
--   admin@snapmarket.nl   (admin123)  - rol admin
--   lars@snapmarket.nl    (welkom123) - rol user
--   sanne@snapmarket.nl   (welkom123) - rol user
--   mehmet@snapmarket.nl  (welkom123) - rol user

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS watchlist;
DROP TABLE IF EXISTS items;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS seller_profiles;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------------------------
-- users
-- ---------------------------------------------------------------
CREATE TABLE users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    naam VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    wachtwoord VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------
-- seller_profiles (1-op-1 met users: user_id is UNIQUE)
-- ---------------------------------------------------------------
CREATE TABLE seller_profiles (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    bio TEXT NULL,
    telefoonnummer VARCHAR(20) NOT NULL,
    beoordelingsscore DECIMAL(2,1) NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_seller_profiles_user (user_id),
    CONSTRAINT fk_seller_profiles_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------
-- categories (1-op-veel naar items)
-- ---------------------------------------------------------------
CREATE TABLE categories (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    naam VARCHAR(50) NOT NULL,
    omschrijving VARCHAR(255) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_categories_naam (naam)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------
-- items
-- prijs wordt opgeslagen in CENTEN (1499 = € 14,99), zie Hoofdstuk 3
-- ---------------------------------------------------------------
CREATE TABLE items (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    titel VARCHAR(100) NOT NULL,
    omschrijving TEXT NOT NULL,
    prijs INT UNSIGNED NOT NULL,
    conditie ENUM('nieuw', 'goed', 'redelijk', 'slecht') NOT NULL,
    status ENUM('beschikbaar', 'verkocht') NOT NULL DEFAULT 'beschikbaar',
    foto_url VARCHAR(255) NULL,
    user_id INT UNSIGNED NOT NULL,
    category_id INT UNSIGNED NOT NULL,
    deleted_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    CONSTRAINT fk_items_user FOREIGN KEY (user_id) REFERENCES users (id),
    CONSTRAINT fk_items_category FOREIGN KEY (category_id) REFERENCES categories (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------
-- watchlist (wordt gebruikt vanaf Hoofdstuk 4 - AJAX)
-- ---------------------------------------------------------------
CREATE TABLE watchlist (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    item_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_watchlist_user_item (user_id, item_id),
    CONSTRAINT fk_watchlist_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    CONSTRAINT fk_watchlist_item FOREIGN KEY (item_id) REFERENCES items (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------
-- Testdata
-- ---------------------------------------------------------------
INSERT INTO users (id, naam, email, wachtwoord, rol) VALUES
(1, 'Beheerder', 'admin@snapmarket.nl', '$2y$12$BrlxBlQWJOVNNdjgnq1Vy.gQyLQzZmx9KJqDOfY0YDMdJTtejr5CW', 'admin'),
(2, 'Lars de Vries', 'lars@snapmarket.nl', '$2y$12$A/7crY3YhpNSRd5y5zWKfOnSWOdtaKbqUPKxF1Z2nkq/4MJDwadti', 'user'),
(3, 'Sanne Bakker', 'sanne@snapmarket.nl', '$2y$12$A/7crY3YhpNSRd5y5zWKfOnSWOdtaKbqUPKxF1Z2nkq/4MJDwadti', 'user'),
(4, 'Mehmet Yılmaz', 'mehmet@snapmarket.nl', '$2y$12$A/7crY3YhpNSRd5y5zWKfOnSWOdtaKbqUPKxF1Z2nkq/4MJDwadti', 'user');

INSERT INTO seller_profiles (user_id, bio, telefoonnummer, beoordelingsscore) VALUES
(1, 'Beheerder van SnapMarket.', '0201234567', NULL),
(2, 'Oprichter van SnapMarket. Verkoop vooral fietsen en sportspullen uit de schuur.', '0612345678', 4.8),
(3, 'Ruim regelmatig mijn kledingkast en boekenplank op. Ophalen in Haarlem-Noord.', '0687654321', 4.5),
(4, 'Tech-liefhebber. Alles getest en werkend, tenzij anders vermeld.', '0611223344', 3.9);

INSERT INTO categories (id, naam, omschrijving) VALUES
(1, 'Kleding', 'Jassen, truien, schoenen en accessoires.'),
(2, 'Elektronica', 'Telefoons, laptops, audio en gadgets.'),
(3, 'Boeken', 'Romans, studieboeken, strips en kookboeken.'),
(4, 'Sport', 'Fietsen, fitness, balsporten en buitenspullen.'),
(5, 'Wonen', 'Meubels, lampen, keukenspullen en decoratie.');

INSERT INTO items (titel, omschrijving, prijs, conditie, status, foto_url, user_id, category_id, deleted_at, created_at) VALUES
('Gazelle stadsfiets', 'Degelijke damesfiets met 3 versnellingen, nieuwe banden en een werkend slot. Kleine roestplekjes op het spatbord.', 12500, 'goed', 'beschikbaar', 'https://picsum.photos/seed/snap-fiets/600/400', 2, 4, NULL, '2026-09-20 10:15:00'),
('Hardloopschoenen maat 43', 'Twee keer gedragen, bleken een halve maat te klein. Doos is er nog.', 4500, 'nieuw', 'beschikbaar', 'https://picsum.photos/seed/snap-schoenen/600/400', 2, 4, NULL, '2026-09-21 18:40:00'),
('Set dumbbells 2x10 kg', 'Verstelbare dumbbells met schroefsluiting. Zwaar, dus alleen ophalen.', 3000, 'redelijk', 'beschikbaar', NULL, 2, 4, NULL, '2026-09-22 09:05:00'),
('Spijkerjas Levi''s', 'Klassieke blauwe spijkerjas, maat M. Licht verwassen.', 3500, 'goed', 'beschikbaar', 'https://picsum.photos/seed/snap-jas/600/400', 3, 1, NULL, '2026-09-23 12:00:00'),
('Wollen winterjas', 'Donkergrijze jas, maat L. Eén knoop vervangen.', 2500, 'redelijk', 'beschikbaar', 'https://picsum.photos/seed/snap-winterjas/600/400', 3, 1, NULL, '2026-09-24 14:30:00'),
('Harry Potter boxset (NL)', 'Alle zeven delen, paperback. Ruggen licht gebroken, verder prima.', 4000, 'goed', 'beschikbaar', 'https://picsum.photos/seed/snap-boeken/600/400', 3, 3, NULL, '2026-09-25 16:20:00'),
('Kookboek "Simpel"', 'Populair kookboek met snelle recepten. Paar vlekjes op de bladzijden.', 800, 'redelijk', 'verkocht', NULL, 3, 3, NULL, '2026-09-10 11:00:00'),
('PHP & MySQL studieboek', 'Gebruikt tijdens de opleiding, met wat potloodaantekeningen.', 1500, 'goed', 'beschikbaar', 'https://picsum.photos/seed/snap-studieboek/600/400', 3, 3, NULL, '2026-09-26 08:45:00'),
('iPhone 12 - 64 GB', 'Werkt perfect, batterijconditie 84%. Kras op de achterkant. Inclusief hoesje.', 22500, 'goed', 'beschikbaar', 'https://picsum.photos/seed/snap-iphone/600/400', 4, 2, NULL, '2026-09-26 19:10:00'),
('Bluetooth speaker JBL Flip 5', 'Nieuw in doos, dubbel gekregen.', 7500, 'nieuw', 'beschikbaar', 'https://picsum.photos/seed/snap-speaker/600/400', 4, 2, NULL, '2026-09-27 13:25:00'),
('Laptop HP 15" (2019)', 'i5, 8 GB RAM, 256 GB SSD. Accu houdt nog ca. 2 uur. Toetsenbord compleet.', 18000, 'redelijk', 'beschikbaar', NULL, 4, 2, NULL, '2026-09-28 10:00:00'),
('Oude Nintendo DS', 'Scherm heeft een barst, start wel op. Voor onderdelen of liefhebbers.', 1000, 'slecht', 'beschikbaar', 'https://picsum.photos/seed/snap-ds/600/400', 4, 2, NULL, '2026-09-28 21:15:00'),
('Draadloze oordopjes', 'Werkt niet meer goed, rechter oordopje valt weg.', 500, 'slecht', 'verkocht', NULL, 4, 2, NULL, '2026-09-05 10:00:00'),
('IKEA Billy boekenkast', 'Wit, 80x202 cm. Al gedemonteerd, alle schroeven aanwezig.', 2000, 'goed', 'beschikbaar', 'https://picsum.photos/seed/snap-kast/600/400', 2, 5, NULL, '2026-09-29 17:00:00'),
('Vintage bureaulamp', 'Messing bureaulamp uit de jaren 70, nieuwe snoer.', 3250, 'goed', 'beschikbaar', 'https://picsum.photos/seed/snap-lamp/600/400', 3, 5, NULL, '2026-09-30 15:45:00'),
('Kapotte stoel', 'Advertentie per ongeluk geplaatst.', 100, 'slecht', 'beschikbaar', NULL, 2, 5, '2026-09-30 16:00:00', '2026-09-30 15:50:00');

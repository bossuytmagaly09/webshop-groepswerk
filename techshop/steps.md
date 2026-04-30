# Project Plan: Techshop E-commerce & CMS

Dit document bevat de chronologische opbouw voor de ontwikkeling van de Techshop applicatie (Frontend) en het bijbehorende CMS (Backend Dashboard). Dit plan volgt de best practices van een senior developer.

Elke stap is voorzien van een checkbox (`[ ]`), zodat deze kan worden afgevinkt naarmate de ontwikkeling vordert. Bij het ontwikkelen moet er altijd rekening gehouden worden met de afspraken en richtlijnen in het `rules.md` bestand.

---

## Fase 1: Project & Architectuur Setup
Doel: Het opzetten van de ontwikkelomgeving en het in kaart brengen van de datastructuur.

- [x] Controleren en inladen van de actuele `rules.md` (Zorg dat deze opgeslagen is in je IDE)
- [x] Database ontwerp uittekenen (ERD): Producten, Categorieën, Gebruikers (Rollen), Bestellingen, Bestelregels, Reviews.
- [x] Maken en configureren van de Laravel Modellen & Relaties (Eloquent) `Product`, `Category`, `Order`, `OrderItem`, `User`.
- [x] Backed Enums aanmaken (`OrderStatus`, `UserRole`).
- [x] Aanmaken van de bijbehorende database Migrations.
- [x] Aanmaken van Factories en Seeders (dummy data) voor testdoeleinden.
- [x] Repository / Service Layer architectuur opzetten (indien we logica willen scheiden van controllers/Livewire componenten).

---

## Fase 2: Backend Development (CMS/Dashboard)
Doel: Het verder uitbouwen van het bestaande Livewire/Fortify dashboard om te dienen als het CMS van de techshop.

- [x] **Configuratie & Rechten**
  - [x] [Auth] Social Login Integreren (Google & GitHub) met accountkoppeling.
  - [x] Role-based Access Control (RBAC) invoeren: Onderscheid tussen Admin en normale Klanten.
  - [x] Dashboard toegang beveiligen met middleware via Livewire/Fortify.
- [x] **Categorie Beheer (CRUD)**
  - [x] Categorieën overzicht pagina (Lijst met actieve webshop categorieën).
  - [x] Livewire component voor het toevoegen, bewerken en verwijderen (soft deletes) van categorieën.
- [x] **Product Beheer (CRUD)**
  - [x] Producten overzicht met zoek- en de filterfunctionaliteit.
  - [x] Formulieren voor nieuwe/bestaande producten (Titel, Beschrijving, Prijs, Voorraad, Afbeelding upload, Categorie toewijzing).
- [x] **Order/Bestellingen Beheer**
  - [x] Overzicht van alle bestellingen en hun statussen (Wachtend, Verzonden, Geannuleerd).
  - [x] Order detailpagina implementeren voor de webshop admin.
- [x] **Gebruikers & Klanten Beheer**
  - [x] Overzicht van alle geregistreerde klanten.
  - [x] Bewerkingsmogelijkheden voor admin accounts (binnen de vastgestelde security rules).
  - [x] Mijn Orders: Overzicht voor de ingelogde klant op de frontend.

---

## Fase 3: Frontend Development (Techshop Applicatie)
Doel: Het opbouwen van de voorkant van de webshop die naadloos communiceert met de backend en database.

- [x] **Basis Layout & Design System**
  - [x] Opzetten van de master layout via TailwindCSS.
  - [x] Integratie van het navigatie menu (inclusief dynamische categorie weergave) en de footer.
  - [x] Integratie van Dark Mode (Toggle en CSS instellingen) in het design systeem.
- [x] **Home & Catalogus**
  - [x] Homepage opzetten (Uitgelichte producten, Banners, Recente toevoegingen).
  - [x] Product overzicht (Shop/Catalogus) pagina maken met Livewire (dynamisch inladen zonder page reloads).
  - [x] Filters en zoekbalk implementeren (bijv. zoeken op naam of categorie).
- [x] **Product Detail Pagina**
  - [x] Uitgebreide detailpagina (Titel, afbeeldingsgalerij, specificaties, review weergave).
  - [x] 'Toevoegen aan winkelwagen' knop (dynamisch via Livewire/Sessie of Database cart).
- [x] **Winkelwagen (Shopping Cart)**
  - [x] Cart overzicht pagina of 'slide-over'/modal (weergave toegevoegde items).
  - [x] Aanpassen van hoeveelheden en verwijderen van items.
  - [x] Prijs/Totaal calculaties (inclusief en exclusief de eventuele BTW).

---

## Fase 4: Checkout & Betaling (Integratie)
Doel: De afhandeling van betalingen en het omzetten van winkelwagen-items naar daadwerkelijke orders in het CMS.

- [x] **Afrekenen (Checkout flow)**
  - [x] Backend logica: Action bouwen voor het omzetten van winkelmand naar definitieve Order (inclusief snapshots).
  - [x] Verzamelen van klant- en verzendgegevens.
  - [x] Order samenvatting tonen voordat definitief betaald wordt.
- [x] **Betalingssysteem**
  - [x] Integratie Mollie / Stripe of een test/dummy betaalprovider.
  - [x] Order bevestigingspagina ("Bedankt voor uw bestelling").
  - [x] Statussen correct laten wegschrijven naar het dashboard voor de Admin.
- [x] **E-mails & Notificaties**
  - [x] Orderbevestiging e-mail sturen naar de klant (Laravel Mail).
  - [x] (Optioneel) Notificatie naar de site beheerder voor de gemaakte order.

---

## Fase 5: QA, Optimalisatie & Lancering
Doel: Kwaliteitswaarborging voor de Techshop op het gebied van performance en beveiliging.

- [x] Nakijken van security policies en form validations (zoals vastgelegd in `rules.md`).
- [x] Afbeeldingen optimaliseren (compressie en responsive inladen).
- [x] Het runnen van Laravel Tests (Unit test en eventuele Feature tests voor de checkout).
- [x] Foutafhandeling controleren (Gaan de 404 en 500 errors goed afgevangen worden?).
- [ ] Klaarmaken voor definitieve deployment (Caching, config optimizers via artisan). 
- [ ] Oplevering (Launch).

---

## Bonus: QR-code Login
Doel: Een veilige, snelle login-optie toevoegen via een scanbare QR-code op de loginpagina.

- [x] Installatie of hergebruik van de benodigde package (`bacon/bacon-qr-code`).
- [x] Migratie en Model aanmaken voor `QrLoginToken`.
- [x] `QrLogin` Livewire component (Desktop weergave op inlogpagina met polling).
- [x] `QrLoginConfirm` Livewire component (Smartphone weergave).
- [x] Route en middleware configuratie.

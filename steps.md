# Project Plan: Techshop E-commerce & CMS

Dit document bevat de chronologische opbouw voor de ontwikkeling van de Techshop applicatie (Frontend) en het bijbehorende CMS (Backend Dashboard). Dit plan volgt de best practices van een senior developer.

Elke stap is voorzien van een checkbox (`[ ]`), zodat deze kan worden afgevinkt naarmate de ontwikkeling vordert. Bij het ontwikkelen moet er altijd rekening gehouden worden met de afspraken en richtlijnen in het `rules.md` bestand.

---

## Fase 1: Project & Architectuur Setup
Doel: Het opzetten van de ontwikkelomgeving en het in kaart brengen van de datastructuur.

- [ ] Controleren en inladen van de actuele `rules.md` (Zorg dat deze opgeslagen is in je IDE)
- [ ] Database ontwerp uittekenen (ERD): Producten, Categorieën, Gebruikers (Rollen), Bestellingen, Bestelregels, Reviews.
- [ ] Maken en configureren van de Laravel Modellen & Relaties (Eloquent) `Product`, `Category`, `Order`, `OrderItem`, `User`.
- [x] Aanmaken van de bijbehorende database Migrations.
- [ ] Aanmaken van Factories en Seeders (dummy data) voor testdoeleinden.
- [ ] Repository / Service Layer architectuur opzetten (indien we logica willen scheiden van controllers/Livewire componenten).

---

## Fase 2: Backend Development (CMS/Dashboard)
Doel: Het verder uitbouwen van het bestaande Livewire/Fortify dashboard om te dienen als het CMS van de techshop.

- [ ] **Configuratie & Rechten**
  - [ ] Role-based Access Control (RBAC) invoeren: Onderscheid tussen Admin en normale Klanten.
  - [ ] Dashboard toegang beveiligen met middleware via Livewire/Fortify.
- [ ] **Categorie Beheer (CRUD)**
  - [ ] Categorieën overzicht pagina (Lijst met actieve webshop categorieën).
  - [ ] Livewire component voor het toevoegen, bewerken en verwijderen (soft deletes) van categorieën.
- [ ] **Product Beheer (CRUD)**
  - [ ] Producten overzicht met zoek- en de filterfunctionaliteit.
  - [ ] Formulieren voor nieuwe/bestaande producten (Titel, Beschrijving, Prijs, Voorraad, Afbeelding upload, Categorie toewijzing).
- [ ] **Order/Bestellingen Beheer**
  - [ ] Overzicht van alle bestellingen en hun statussen (Wachtend, Verzonden, Geannuleerd).
  - [ ] Order detailpagina implementeren voor de webshop admin.
- [ ] **Gebruikers & Klanten Beheer**
  - [ ] Overzicht van alle geregistreerde klanten.
  - [ ] Bewerkingsmogelijkheden voor admin accounts (binnen de vastgestelde security rules).

---

## Fase 3: Frontend Development (Techshop Applicatie)
Doel: Het opbouwen van de voorkant van de webshop die naadloos communiceert met de backend en database.

- [ ] **Basis Layout & Design System**
  - [ ] Opzetten van de master layout via TailwindCSS.
  - [ ] Integratie van het navigatie menu (inclusief dynamische categorie weergave) en de footer.
- [ ] **Home & Catalogus**
  - [ ] Homepage opzetten (Uitgelichte producten, Banners, Recente toevoegingen).
  - [ ] Product overzicht (Shop/Catalogus) pagina maken met Livewire (dynamisch inladen zonder page reloads).
  - [ ] Filters en zoekbalk implementeren (bijv. zoeken op naam of categorie).
- [ ] **Product Detail Pagina**
  - [ ] Uitgebreide detailpagina (Titel, afbeeldingsgalerij, specificaties, review weergave).
  - [ ] 'Toevoegen aan winkelwagen' knop (dynamisch via Livewire/Sessie of Database cart).
- [ ] **Winkelwagen (Shopping Cart)**
  - [ ] Cart overzicht pagina of 'slide-over'/modal (weergave toegevoegde items).
  - [ ] Aanpassen van hoeveelheden en verwijderen van items.
  - [ ] Prijs/Totaal calculaties (inclusief en exclusief de eventuele BTW).

---

## Fase 4: Checkout & Betaling (Integratie)
Doel: De afhandeling van betalingen en het omzetten van winkelwagen-items naar daadwerkelijke orders in het CMS.

- [ ] **Afrekenen (Checkout flow)**
  - [ ] Verzamelen van klant- en verzendgegevens.
  - [ ] Order samenvatting tonen voordat definitief betaald wordt.
- [ ] **Betalingssysteem**
  - [ ] Integratie Mollie / Stripe of een test/dummy betaalprovider.
  - [ ] Order bevestigingspagina ("Bedankt voor uw bestelling").
  - [ ] Statussen correct laten wegschrijven naar het dashboard voor de Admin.
- [ ] **E-mails & Notificaties**
  - [ ] Orderbevestiging e-mail sturen naar de klant (Laravel Mail).
  - [ ] (Optioneel) Notificatie naar de site beheerder voor de gemaakte order.

---

## Fase 5: QA, Optimalisatie & Lancering
Doel: Kwaliteitswaarborging voor de Techshop op het gebied van performance en beveiliging.

- [ ] Nakijken van security policies en form validations (zoals vastgelegd in `rules.md`).
- [ ] Afbeeldingen optimaliseren (compressie en responsive inladen).
- [ ] Het runnen van Laravel Tests (Unit test en eventuele Feature tests voor de checkout).
- [ ] Foutafhandeling controleren (Gaan de 404 en 500 errors goed afgevangen worden?).
- [ ] Klaarmaken voor definitieve deployment (Caching, config optimizers via artisan). 
- [ ] Oplevering (Launch).

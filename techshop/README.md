# TechShop — Premium E-commerce & CMS

Welkom bij **TechShop**, een state-of-the-art e-commerce platform dat de kracht van de **TALL-stack** (Tailwind, Alpine.js, Laravel, Livewire) combineert met een high-end gebruikerservaring. Dit project is ontwikkeld als een professioneel groepswerk, waarbij de focus lag op schaalbaarheid, veiligheid en een premium esthetiek.

---

## 👥 Project Team & Bijdragen

Dit project is het resultaat van een nauwe samenwerking tussen twee developers, elk met hun eigen specialisatie.

### **Magaly Bossuyt (@bossuytmagaly09)**
*   **Focus**: UI/UX Design System, Frontend Architectuur & Administrative CMS.
*   **Uitgebreide Bijdragen**:
    *   **Premium Design System**: Ontwikkeling van een op maat gemaakt UI-framework met TailwindCSS en Flux UI. De focus lag op een "Apple-achtige" esthetiek met subtiele gradiënten, glassmorphism-effecten en vloeiende micro-animaties.
    *   **Dark Mode Expert**: Implementatie van een geavanceerde dark mode die niet alleen de systeemvoorkeuren volgt, maar ook persistent wordt opgeslagen in de browser-omgeving. Inclusief het oplossen van complexe UI-glitches zoals "white flashes" tijdens pagina-overgangen.
    *   **Social Auth Ecosysteem**: Volledige configuratie en integratie van **Laravel Socialite** voor Google en GitHub authenticatie. Dit omvat automatische account-linking en beveiligde profielsynchronisatie.
    *   **Full-stack CMS**: Bouwen van de volledige administratieve backend voor het beheren van **Producten, Categorieën en Gebruikers**. Inclusief geavanceerde functies zoals **soft-deletes**, real-time zoekfilters en rolgebaseerde toegangscontrole (RBAC).
    *   **Media Architectuur**: Ontwerp van een robuust opslagsysteem voor product- en categorie-afbeeldingen, gebruikmakend van Laravel's Storage-disks voor efficiënte verwerking en weergave.
*   **Aanpak**: Magaly hanteerde een **"UX-first"** benadering. Elk element in de interface is ontworpen om de gebruiker te leiden, met real-time feedback via notificaties en geoptimaliseerde laad-states.

### **Nikita (@Nikita)**
*   **Focus**: Core Backend, Betaal-infrastructuur & Data Integriteit.
*   **Uitgebreide Bijdragen**:
    *   **Stripe Betaal-engine**: Ontwikkeling van een end-to-end betaalpijplijn via Stripe Checkout. Implementatie van de `VerifyPaymentAction` voor server-side validatie van transacties, wat fraude voorkomt en data-integriteit garandeert.
    *   **Dynamisch Winkelmand-systeem**: Engineering van een Livewire-gestuurde winkelwagen die naadloos synchroniseert tussen gast-sessies en de database. Bij het inloggen worden items automatisch gemerged zonder dataverlies.
    *   **Data Snapshot Technologie**: Ontwerp van een systeem dat de staat van een product (naam, prijs, specificaties) "bevriest" op het moment van aankoop. Dit zorgt ervoor dat historische orders accuraat blijven, ongeacht toekomstige wijzigingen in het CMS.
    *   **QR-Code Authenticatie**: Innovatieve implementatie van passwordless login via scanbare QR-codes. Gebruikmakend van `bacon/bacon-qr-code` en polling-mechanismen voor een snelle cross-device ervaring.
    *   **Transactioneel Dashboard**: Ontwikkeling van diepe Stripe-integratie in zowel het klanten- als admin-overzicht, inclusief weergave van Session IDs en Payment Intent IDs voor volledige transparantie.
*   **Aanpak**: Nikita focuste op **"Reliability & Scale"**. Door het consequent toepassen van het **Action Pattern** is de business logica volledig losgekoppeld van de UI, wat resulteert in code die extreem goed testbaar is met de geïmplementeerde **Pest Unit Tests**.

---

## 🚀 Kernfunctionaliteiten

*   **🛒 Geavanceerde Checkout**: Volledig geïntegreerd met Stripe voor veilige transacties.
*   **🔍 Dynamische Catalogus**: Real-time filtering op categorieën, prijzen en voorraad zonder pagina-reloads.
*   **⚡ Bliksemsnelle Navigatie**: Gebruik van `wire:navigate` voor een Single Page Application (SPA) gevoel binnen een traditionele Laravel structuur.
*   **🔐 Multi-Auth**: Traditionele login, Social Login en QR-code Login opties.
*   **📦 Order Tracking**: Volledig overzicht van orderstatussen (`Pending`, `Paid`, `Shipped`, `Cancelled`).
*   **🎨 Responsive Design**: Een interface die perfect schaalt van smartphone tot ultra-wide monitor.

---

## 🛠️ Technische Stack & Methodologie

### **Stack**
*   **Backend**: Laravel 11.x
*   **Frontend**: Livewire 3.x, Alpine.js, TailwindCSS
*   **Database**: MySQL / MariaDB
*   **Betalingen**: Stripe PHP SDK
*   **Testing**: Pest Framework (Unit & Feature Testing)

### **Methodologie**
1.  **Agile Feature Branching**: Geen enkele code gaat direct naar `dev`. Elke functie begint als een `feature/*` branch.
2.  **Separation of Concerns**: Business logica leeft in `app/Actions`, Services leven in `app/Services`, en UI-logica in Livewire componenten.
3.  **Security Overal**: CSRF-bescherming, SQL-injection preventie (Eloquent), XSS-filtering en strikte Form Validation regels.
4.  **Performance**: Caching van configuraties en optimalisatie van database-queries (Eager Loading) om N+1 problemen te voorkomen.

---

## 💳 Stripe Betalingen Lokaal Testen

### 1. Stripe Test Keys Instellen

Maak een gratis account aan op [Stripe Dashboard](https://dashboard.stripe.com/) en voeg je keys toe aan je `.env`:

```env
STRIPE_PUBLISHABLE_KEY=pk_test_...
STRIPE_SECRET_KEY=sk_test_...
```

### 2. Test Cards

| Scenario        | Kaartnummer            | Vervaldatum | CVC |
|-----------------|------------------------|-------------|-----|
| ✅ Succes       | `4242 4242 4242 4242`  | Toekomst    | 123 |
| ❌ Geweigerd    | `4000 0000 0000 0002`  | Toekomst    | 123 |

---

## 📧 Bevestigingsmail Lokaal Testen

Mails worden standaard naar het logbestand geschreven (`storage/logs/laravel.log`). Voor een visuele interface kun je **Mailpit** of een vergelijkbare tool gebruiken door de `MAIL_HOST` aan te passen in je `.env`.

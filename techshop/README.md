# TechShop — Premium E-commerce & CMS

**TechShop** is een high-end e-commerce platform en content management systeem (CMS), ontworpen voor een naadloze en premium winkelervaring. Het platform biedt een volledige checkout-flow, dynamisch productbeheer en innovatieve login-methoden.

---

## 👥 Team & Taakverdeling

Dit project is een samenwerking tussen twee cursisten, waarbij de focus lag op een duidelijke scheiding tussen design/UX en core backend functionaliteit.

### **Magaly Bossuyt (@bossuytmagaly09)**
*   **Verantwoordelijkheid**: UI/UX Design System, Frontend Architectuur & CMS Beheer.
*   **Taken**:
    *   Ontwikkeling van het overkoepelende **Design System** (Tailwind & Flux UI).
    *   Implementatie van de **Dark Mode** en verfijning van visuele transities.
    *   Configuratie van **Social Logins** (Google & GitHub).
    *   Ontwikkeling van de volledige **CRUD-interfaces** voor Producten, Categorieën en Gebruikers in de backend.
    *   Ontwerp van de interactieve **Contactpagina** en mediabeheer.

### **Nikita (@Nikita)**
*   **Verantwoordelijkheid**: Core Backend, Betaalsystemen & Systeemintegriteit.
*   **Taken**:
    *   Integratie van de **Stripe Betaalflow** (Checkout & Verification).
    *   Ontwikkeling van de **Winkelwagen-logica** en sessie-synchronisatie.
    *   Architectuur van het **Order Management** en data-snapshots.
    *   Implementatie van de **QR-code Login** functionaliteit.
    *   Opzetten van de **Pest Unit & Feature Tests** voor kritieke bedrijfsprocessen.

---

## 🛠️ Gebruikte Technologieën & Versies

*   **Framework**: Laravel 13.x (PHP 8.4+)
*   **Frontend**: Livewire 4.x, Alpine.js, TailwindCSS 4.x
*   **Build Tool**: Vite 8.x
*   **UI Components**: Flux UI 2.x
*   **Betalingen**: Stripe SDK 20.x
*   **Authenticatie**: Laravel Fortify & Socialite
*   **Testing**: Pest Framework 4.x
*   **QR-Codes**: BaconQrCode

---

## 📥 Installatie-instructies (Stap-voor-stap)

Volg deze stappen om het project lokaal op te zetten:

1.  **Clone de repository**:
    ```bash
    git clone https://github.com/bossuytmagaly09/webshop-groepswerk.git
    cd webshop-groepswerk
    ```
2.  **Installeer PHP dependencies**:
    ```bash
    composer install
    ```
3.  **Installeer Frontend dependencies**:
    ```bash
    npm install
    ```
4.  **Omgevingsvariabelen instellen**:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
5.  **Database configureren**:
    Maak een database aan (bijv. `techshop`) en pas de `DB_*` variabelen aan in je `.env`.
6.  **Migraties & Seeding**:
    ```bash
    php artisan migrate --seed
    ```
7.  **Server starten**:
    ```bash
    php artisan serve
    npm run dev
    ```



## Tests Uitvoeren

De applicatie is voorzien van geautomatiseerde Pest tests (unit en feature) om de stabiliteit van de codebase te garanderen.

### Volledige Testsuite

Om de volledige testsuite (inclusief unit en feature tests) uit te voeren, gebruik je de volgende opdracht in je terminal:

```bash
php artisan test
```

Alle tests in de applicatie zijn ontworpen om robuust en idempotent te zijn. We maken gebruik van de `RefreshDatabase` trait, wat betekent dat de database na elke test netjes wordt gereset en opgeschoond. Externe afhankelijkheden zoals de `StripeService` worden via Mocks overgeslagen zodat er geen onnodige API calls naar Stripe worden gemaakt tijdens het runnen van de tests.

---

## 💳 Stripe Lokaal Testen

1.  **Keys**: Voeg je Stripe Test Keys toe aan de `.env`:
    ```env
    STRIPE_PUBLISHABLE_KEY=pk_test_...
    STRIPE_SECRET_KEY=sk_test_...
    ```
2.  **Testkaarten**:
    *   **Succes**: `4242 4242 4242 4242` (elke datum in de toekomst, CVC 123)
    *   **Geweigerd**: `4000 0000 0000 0002`

---

## 🔑 Social Logins & QR Login

### **Social Logins**
Configuratie via `SERVICES_GOOGLE_*` en `SERVICES_GITHUB_*` in de `.env`.
*   *Let op*: De benodigde Client ID's en Secrets moeten worden aangevraagd via de respectievelijke developer consoles (Google Cloud / GitHub Settings).

### **QR Login Testen**
1.  Open de loginpagina op je desktop.
2.  Er verschijnt een QR-code (polling start automatisch).
3.  Scan de code met een ingelogde smartphone (of open de gegenereerde link in een browser waar je al bent ingelogd).
4.  Bevestig de login op je smartphone; je desktop zal automatisch inloggen.

---

## 🔐 Demo Credentials

| Rol | Email | Wachtwoord |
| :--- | :--- | :--- |
| **Admin** | `admin@techshop.local` | `password` |
| **Klant** | `klant1@techshop.local` | `password` |

---

## ⚠️ Bekende Bugs & Niet-afgewerkte delen

*   **Multi-currency**: Momenteel wordt alleen de Euro (€) ondersteund in de checkout.
*   **Facturatie**: Het genereren van PDF-facturen voor klanten is nog niet geïmplementeerd.
*   **Stock Notificaties**: Er is nog geen automatisch systeem om de admin te mailen wanneer de voorraad onder een bepaalde grens zakt.
*   **Social Auth Redirect**: Op sommige lokale server-omgevingen (zonder HTTPS) kan de redirect van Google soms een 403-fout geven als de callback URL niet exact overeenkomt.

---

## 🛡️ Senior Decision: Account Linking & Edge Cases

### **Scenario: E-mail/Wachtwoord registratie gevolgd door Social Login**
Wat gebeurt er als een gebruiker zich eerst registreert met `user@example.com` en later inlogt via Google met datzelfde e-mailadres?

**Onze Keuze: Automatische Accountkoppeling**
In de `SocialLoginController` hebben we bewust gekozen voor **Automatic Account Linking**. Wanneer een social user wordt teruggegeven, zoeken we eerst in de database op e-mailadres. Als er een match is, updaten we de bestaande gebruiker met de `provider_id` (bijv. `google_id`).

**Motivatie:**
1.  **Optimale UX**: De gebruiker wordt niet geconfronteerd met foutmeldingen zoals "E-mailadres is al in gebruik". Ze kunnen naadloos overschakelen tussen inlogmethoden.
2.  **Data Consolidatie**: We voorkomen dat een gebruiker twee aparte accounts krijgt (één voor e-mail/wachtwoord en één voor Google), wat zou leiden tot gefragmenteerde order-historie.
3.  **Trust-based Security**: We vertrouwen erop dat Google en GitHub het e-mailadres reeds hebben geverifieerd. Hierdoor is het veilig om de identiteit van de social user te koppelen aan het bestaande account.

---

## 🏛️ Motivatie Architectuurkeuzes

1.  **Action Pattern**: We hebben gekozen voor Laravel **Actions** (`app/Actions`) om business logica (zoals `CreateOrderAction`) los te koppelen van de Livewire componenten. Dit maakt de code herbruikbaar en eenvoudiger te testen.
2.  **Data Snapshots**: In plaats van alleen een link naar een product-ID op te slaan in een bestelling, maken we een snapshot van de naam en prijs op het moment van aankoop. Dit is essentieel voor historische correctheid bij prijsverhogingen of productverwijderingen.
3.  **Flux UI**: We hebben gekozen voor Flux UI vanwege de hoogwaardige, toegankelijke componenten die perfect aansluiten bij de moderne uitstraling die we wilden bereiken.
4.  **Pest Framework**: Voor het testen hebben we Pest gekozen vanwege de elegante syntax, wat ons hielp om snel een hoge test-coverage te bereiken op de meest kritieke onderdelen (Stripe & Order flow).


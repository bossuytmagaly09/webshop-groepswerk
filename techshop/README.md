# TechShop

## Stripe Betalingen Lokaal Testen

### 1. Stripe Test Keys Instellen

Maak een gratis account aan op [Stripe Dashboard](https://dashboard.stripe.com/) en kopieer je **test** API keys (te vinden onder Developers → API keys):

```env
# .env
STRIPE_PUBLISHABLE_KEY=pk_test_...
STRIPE_SECRET_KEY=sk_test_...
```

> **Let op:** Gebruik alleen test keys (`pk_test_` / `sk_test_`). Live keys (`pk_live_` / `sk_live_`) mogen **nooit** in development worden gebruikt.

### 2. Test Cards

| Scenario        | Kaartnummer            | Vervaldatum         | CVC               |
|-----------------|------------------------|---------------------|--------------------|
| ✅ Succes       | `4242 4242 4242 4242`  | Elke datum in de toekomst | 3 willekeurige cijfers |
| ❌ Geweigerd    | `4000 0000 0000 0002`  | Elke datum in de toekomst | 3 willekeurige cijfers |

### 3. Betaalflow

1. Vul het checkout formulier in en klik op **Bestelling plaatsen**
2. Je wordt doorgestuurd naar de Stripe Checkout pagina
3. Gebruik een test card om te betalen
4. Na betaling kom je terug op de bevestigingspagina
5. De order status is nu `paid` — dit is pas na server-side verificatie bij Stripe

### 4. Tests Uitvoeren

```bash
php artisan test --compact tests/Feature/StripeCheckoutTest.php
```

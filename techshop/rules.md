# rules.md

## Doel van dit document

Dit document definieert de vaste architectuur-, code-, CMS- en schrijfrichtlijnen voor dit project in VS Code.

Alles wat gegenereerd, aangepast of voorgesteld wordt, moet compatibel zijn met:

* Laravel 13
* Livewire 4
* de reeds geïnstalleerde Laravel Livewire starter kit
* Flux UI free tier
* MySQL of MariaDB
* Pest
* Vite
* Tailwind CSS

Dit project is geen generieke demo-app.

Dit project is een **premium tech content platform + hardware showcase CMS**, gebouwd op basis van de frontend uit `test.zip` / `techsop.zip`.

De frontend toont een moderne techsite gericht op:

* premium developer gear
* hardware showcases
* editorial content
* product discovery
* categoriepagina’s
* technische productdetails
* marketingblokken
* featured collections

De frontend uit de zip is het visuele referentiepunt.

De backend, CMS-structuur, domeinarchitectuur, contentbeheer en admin flows moeten logisch aansluiten op deze nieuwe techsite.

De admin backend wordt opgebouwd via:

```text
/dashboard
```

---

# Projectcontext

## Startpunt

* Het Laravel project bestaat reeds.
* Laravel 13 + Livewire 4 zijn reeds geïnstalleerd.
* We bouwen verder op de bestaande starter kit.
* `techsop.zip` bevat de frontendreferentie.
* De frontend moet volledig geanalyseerd worden.
* HTML mockups worden vertaald naar Blade + Livewire.
* De backend wordt een volledig CMS.

---

## Hoofddoel

Bouw een schaalbare premium techsite met:

* publieke contentgedreven homepage
* product showcase systeem
* categorieën
* detailpagina’s
* featured collections
* hardware catalogus
* content publishing
* admin backend op `/dashboard`
* CMS voor pagina’s
* CMS voor homepage-secties
* CMS voor producten
* CMS voor media
* CMS voor contentblokken
* SEO-ondersteuning
* auteursbeheer
* analytics-ready structuur
* schaalbare Laravel architectuur

---

# Analyse van techsop.zip

## Algemene stijl

De frontend toont een moderne tech-lifestyle site met:

* minimalistische premium UI
* lichte achtergrond
* subtiele groene accentkleur
* focus op whitespace
* editorial storytelling
* hardware showcase cards
* product grids
* detailpagina’s
* categorieblokken
* hero marketing sections
* futuristische developer branding

---

## Pagina-analyse

### index.html

De homepage bevat:

* vaste transparante navbar
* hero banner
* CTA buttons
* categoriekaarten
* premium marketing layout
* featured product showcases
* storytelling blokken
* visuele categorieën

### products.html

De listingpagina bevat:

* categorie filters
* horizontale filterchips
* product grid
* premium card layout
* prijsweergave
* technische samenvatting
* product previews

### detail.html

De detailpagina bevat:

* hoofdafbeelding
* galerij
* badge/status
* productomschrijving
* technische specificaties
* aankoop-CTA
* wishlist knop

---

# Niet-onderhandelbare versie- en syntaxregels

## Gebruik uitsluitend

* Laravel 13 conventies
* Livewire 4 syntax
* moderne Laravel service architectuur
* typed PHP
* dependency injection
* policies
* actions
* services
* enums
* factories
* seeders

---

## Verboden

Gebruik nooit:

* Livewire 2 syntax
* Livewire 3 verouderde patronen
* oude Volt routing
* Filament tenzij expliciet later gekozen
* business logic in Blade
* queries in views
* inline DB queries in render methods
* float voor prijzen
* hardcoded IDs
* admin routes zonder middleware
* CRUD rechtstreeks in views
* page logic in routes
* JS hacks voor server-side state
* volledige frontend HTML laten bestaan als eindoplossing

---

# Laravel 13 architectuurregels

## Separation of concerns

### Routes

Routes doen alleen:

* endpoint mapping
* middleware koppelen
* Livewire pages registreren
* controller koppelen

Routes doen niet:

* queries
* business logic
* auth checks inline

---

### Controllers

Controllers blijven dun.

Controllers mogen:

* requests ontvangen
* acties aanroepen
* responses teruggeven
* autorisatie uitvoeren

Controllers mogen niet:

* domeinlogica bevatten
* CMS persistence volledig afhandelen

---

### Livewire

Livewire fungeert als interactieve UI-laag.

Livewire componenten mogen:

* forms beheren
* state beheren
* validatie uitvoeren
* acties triggeren

Livewire componenten mogen niet:

* grote queryketens bevatten
* business rules bepalen
* database workflows orkestreren

---

### Actions

Actions behandelen use-cases.

Voorbeelden:

```text
CreateArticleAction
UpdateArticleAction
CreateProductAction
PublishArticleAction
UpdateHomepageSectionAction
UploadMediaAssetAction
CreateTechCategoryAction
AssignFeaturedContentAction
```

Regels:

* één Action = één use case
* publieke `handle()` methode
* geen views
* geen redirects

---

### Services

Services bevatten grotere technische logica.

Voorbeelden:

```text
SeoService
MediaService
ImageOptimizationService
HomepageComposerService
AnalyticsService
SlugService
SearchService
```

---

# Domeinstructuur

Gebruik deze domeinen:

```text
- Content
- Products
- Categories
- Homepage
- Media
- Authors
- Tags
- SEO
- Dashboard
- Navigation
- Search
- Analytics
```

---

## Voorkeursstructuur

```text
app/
├── Actions/
│   ├── Content/
│   ├── Products/
│   ├── Homepage/
│   ├── Media/
│   ├── SEO/
│   └── Dashboard/
├── Enums/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
├── Livewire/
│   ├── Pages/
│   │   ├── Public/
│   │   ├── Dashboard/
│   │   ├── Products/
│   │   ├── Content/
│   │   └── Settings/
│   └── Forms/
├── Models/
├── Policies/
├── Services/
├── Events/
├── Listeners/
├── Support/
└── ViewModels/
```

---

# CMS structuur

## Dashboard endpoint

Admin backend draait via:

```text
/dashboard
```

### Dashboard moet bevatten

* analytics overview
* content performance
* latest posts
* recent products
* media overzicht
* SEO warnings
* drafts
* scheduled posts
* category usage
* quick actions

---

# CMS entiteiten

## 1. ProductCategory

Velden:

```text
id
name
slug
description
icon
cover_image
seo_title
seo_description
sort_order
is_featured
is_active
timestamps
soft deletes
```

---

## 2. Product

Velden:

```text
id
category_id
name
slug
short_description
full_description
price nullable
hero_image
thumbnail
badge
stock_status
specifications_json
is_featured
is_active
is_highlighted
published_at
seo_title
seo_description
timestamps
soft deletes
```

---

## 3. Article

Techsite vereist een contentlaag.

Velden:

```text
id
author_id
category_id
title
slug
excerpt
content
hero_image
reading_time
status
published_at
seo_title
seo_description
featured
is_trending
view_count
timestamps
soft deletes
```

---

## 4. Author

Velden:

```text
id
user_id
name
slug
avatar
bio
social_links_json
is_editor
is_active
timestamps
```

---

## 5. Tag

Velden:

```text
id
name
slug
color
is_active
timestamps
```

---

## 6. HomepageSection

Voor dynamische homepage.

Velden:

```text
id
section_key
title
subtitle
content_json
layout_type
position
is_active
timestamps
```

---

## 7. NavigationItem

Velden:

```text
id
label
url
parent_id nullable
position
open_in_new_tab
is_active
```

---

## 8. MediaAsset

Velden:

```text
id
file_name
file_path
mime_type
alt_text
width
height
size
folder
uploaded_by
is_optimized
timestamps
```

---

## 9. SEOPage

Velden:

```text
id
route_key
meta_title
meta_description
og_image
schema_json
indexable
timestamps
```

---

# Dashboard modules

Dashboard bevat minstens:

## Content

```text
/dashboard/content
```

Functies:

* artikels beheren
* publiceren
* drafts
* rich editor
* categorie koppeling
* tags
* SEO

---

## Producten

```text
/dashboard/products
```

Functies:

* product CRUD
* technische specs
* featured producten
* category linking
* media uploads

---

## Categories

```text
/dashboard/categories
```

Functies:

* categories beheren
* sortering
* homepage feature

---

## Homepage builder

```text
/dashboard/homepage
```

Functies:

* hero content
* CTA blokken
* featured products
* category highlights
* marketing secties
* volgorde aanpassen

---

## Media library

```text
/dashboard/media
```

Functies:

* uploads
* alt texts
* crop metadata
* responsive images
* folderbeheer

---

## SEO manager

```text
/dashboard/seo
```

Functies:

* metadata beheer
* schema markup
* previews

---

## Navigation manager

```text
/dashboard/navigation
```

Functies:

* navbar beheer
* footer links
* nested menu items

---

# Frontend mapping

## Homepage

Wordt Livewire pagina.

Functies:

* hero
* CTA
* category cards
* featured hardware
* latest content
* recommended gear
* marketing blocks
* curated sections

---

## Product listing

Functies:

* filters
* categorieën
* sortering
* zoekfunctie
* featured badges
* lazy pagination

---

## Product detail

Functies:

* image gallery
* specs
* CTA
* related products
* badge system
* category navigation

---

## Article detail

Functies:

* rich content
* related articles
* author block
* tags
* reading time
* share options

---

# Routing structuur

```text
/
/products
/products/{slug}
/categories/{slug}
/articles/{slug}
/search
/about
/contact
/dashboard
/dashboard/products
/dashboard/categories
/dashboard/content
/dashboard/media
/dashboard/homepage
/dashboard/settings
```

---

# Livewire regels

## Gebruik standaard

* single-file components
* computed properties
* form objects
* pagination
* file uploads
* debounce search
* eager loading

---

# Database regels

## Gebruik

* slugs
* indexes
* soft deletes
* foreign keys
* decimal prijzen
* timestamps
* json kolommen waar logisch

---

## Geld

Gebruik altijd:

```text
decimal(10,2)
```

Nooit:

```text
float
```

---

# Enums

Gebruik enums voor:

```text
ContentStatus
UserRole
ProductBadge
StockStatus
HomepageSectionType
SeoIndexState
```

---

# Policies

Voorzie minstens:

```text
ProductPolicy
ArticlePolicy
CategoryPolicy
HomepagePolicy
MediaPolicy
NavigationPolicy
```

---

# Middleware

Gebruik:

```text
EnsureDashboardAccess
EnsureEditorRole
EnsureAdminRole
```

---

# SEO architectuur

Elke contentpagina ondersteunt:

* meta title
* meta description
* og image
* canonical
* robots
* schema markup
* JSON-LD

---

# Zoekfunctionaliteit

Zoekmachine ondersteunt:

* producten
* artikels
* categorieën
* tags

Gebruik:

```text
SearchService
```

---

# Homepage builder structuur

Homepage secties moeten dynamisch zijn.

Voorbeelden:

```text
HeroBannerSection
FeaturedProductsSection
CategoryGridSection
LatestArticlesSection
CTASection
TechnologyHighlightSection
QuoteSection
NewsletterSection
```

---

# Media regels

Gebruik:

* responsive image variants
* alt text verplicht
* lazy loading
* webp support
* optimization queue

---

# Testing regels

Gebruik Pest.

Voorzie tests voor:

* dashboard authorization
* article CRUD
* product CRUD
* homepage rendering
* slug generation
* SEO persistence
* media upload
* policy checks

---

# Frontend-integratie regels

## De zip is een design reference

Niet:

* HTML los bewaren
* mock data behouden
* inline scripts gebruiken als finale oplossing

Wel:

* Blade layouts
* Livewire pagina’s
* database driven rendering
* Vite asset pipeline

---

# AI regels voor VS Code

1. Gebruik enkel Laravel 13 + Livewire 4 syntax.
2. Gebruik geen verouderde tutorials.
3. Respecteer domeinstructuur.
4. Gebruik Actions voor business logic.
5. Gebruik Services voor technische flows.
6. Gebruik `/dashboard` als admin backend.
7. Gebruik policies voor authorisatie.
8. Houd Livewire componenten klein.
9. Bouw CMS-first.
10. Gebruik typed PHP.
11. Gebruik dependency injection.
12. Respecteer premium techsite architectuur.
13. Gebruik echte database-data.
14. Bouw SEO-ready.
15. Houd frontend clean.
16. Gebruik enums.
17. Gebruik eager loading.
18. Respecteer schaalbaarheid.
19. Geen business logic in Blade.
20. Geen snelle hacks.

---

# Waarschijnlijk aan te maken bestanden

## Models

```text
app/Models/Product.php
app/Models/ProductCategory.php
app/Models/Article.php
app/Models/Author.php
app/Models/Tag.php
app/Models/HomepageSection.php
app/Models/NavigationItem.php
app/Models/MediaAsset.php
app/Models/SeoPage.php
```

---

## Actions

```text
app/Actions/Products/
app/Actions/Content/
app/Actions/Homepage/
app/Actions/Media/
app/Actions/SEO/
```

---

## Services

```text
app/Services/SearchService.php
app/Services/MediaService.php
app/Services/SeoService.php
app/Services/HomepageComposerService.php
```

---

## Dashboard pages

```text
Dashboard Overview
Dashboard Products
Dashboard Categories
Dashboard Articles
Dashboard Homepage
Dashboard Media
Dashboard SEO
Dashboard Navigation
Dashboard Settings
```

---

# Concrete ontwikkelfases

## Fase 1

* database structuur
* models
* enums
* seeders
* dashboard auth

## Fase 2

* homepage
* categorieën
* product listing
* detailpagina

## Fase 3

* content publishing
* article systeem
* authors
* tags

## Fase 4

* dashboard CMS
* media
* homepage builder

## Fase 5

* SEO
* structured data
* analytics

## Fase 6

* testing
* cleanup
* optimization

---

# Anti-patronen

Weiger voorstellen die:

* business logic in Blade plaatsen
* admin via frontend hiding beveiligen
* mock data behouden
* gigantische Livewire components bouwen
* oude Laravel syntax gebruiken
* HTML bestanden los laten bestaan
* policies overslaan
* dashboard routes onbeveiligd maken
* CMS logica rechtstreeks in views stoppen

---

# Definitie van klaar

Een feature is pas klaar als:

* Laravel 13 compatibel
* Livewire 4 compatibel
* dashboard geïntegreerd
* policies aanwezig
* database correct
* CMS bruikbaar
* frontend gekoppeld aan echte data
* SEO ondersteund
* tests aanwezig

---

# Samenvattende hoofdregel

Bouw dit project alsof een senior Laravel developer een premium tech editorial platform + hardware showcase CMS bouwt op basis van een high-end minimalistische tech frontend, met volledige CMS-functionaliteit, schaalbare architectuur, `/dashboard` admin backend en strikte Laravel 13 + Livewire 4 conventies.

# ITAF Badging — Guide d'installation

## Prérequis

- PHP 8.1+ avec extensions : pdo_mysql, gd, mbstring, openssl, tokenizer, xml, ctype, json
- MySQL 8.0+
- Composer 2.x
- Accès cPanel (NindoHost) ou serveur avec SSH

---

## Installation en production (cPanel/NindoHost)

### 1. Créer la base de données

Dans **cPanel > MySQL Databases** :
1. Créer une base : `itaf_badging`
2. Créer un utilisateur MySQL
3. Accorder tous les privilèges à cet utilisateur sur la base
4. Dans **phpMyAdmin**, importer le fichier `setup.sql` fourni

### 2. Uploader les fichiers

Uploader tout le contenu du projet dans le dossier `public_html/badging/` (ou la racine de votre domaine).

**Important** : Le dossier `public/` doit être la racine web du domaine.
Si votre hébergeur impose `public_html/` comme racine :
- Copier le contenu de `public/` directement dans `public_html/`
- Modifier `public_html/index.php` ligne 1 : `require __DIR__.'/../badging/bootstrap/app.php'`

### 3. Configurer le fichier .env

Copier `.env.example` en `.env` et renseigner :

```
APP_URL=https://votre-domaine.ma
APP_ENV=production
APP_DEBUG=false
APP_KEY=                    # Générer avec : php artisan key:generate

DB_HOST=localhost
DB_DATABASE=votre_user_itaf_badging
DB_USERNAME=votre_user_mysql
DB_PASSWORD=votre_mot_de_passe

MAIL_MAILER=smtp
MAIL_HOST=mail.votre-domaine.ma
MAIL_PORT=465
MAIL_USERNAME=badging@votre-domaine.ma
MAIL_PASSWORD=votre_mot_de_passe_email
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=badging@votre-domaine.ma

EVENT_NOM="ITAF 2026"
EVENT_DATE="15 Juin 2026"
EVENT_LIEU="Centre de Conférences, Casablanca"
EVENT_SLUG=itaf-2026
```

### 4. Installer les dépendances Composer

Via SSH ou le terminal cPanel :

```bash
cd /home/votre_user/public_html/badging
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 5. Permissions des dossiers

```bash
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/app/public
```

### 6. Sans SSH (cPanel FileManager)

Si pas d'accès SSH :
1. Uploader le dossier `vendor/` pré-compilé (faire `composer install` localement d'abord)
2. Configurer `.env` via l'éditeur de fichiers cPanel
3. Créer le lien symbolique manuellement :
   - `public/storage` → `../storage/app/public`

---

## Démarrage rapide (local)

```bash
# Cloner / copier le projet
cd ITAF

# Installer les dépendances
composer install

# Configurer l'environnement
cp .env.example .env
php artisan key:generate

# Lancer la base de données
# (importer setup.sql dans phpMyAdmin ou MySQL Workbench)

# Lancer les migrations (alternative au setup.sql)
php artisan migrate --seed

# Créer le lien de stockage public
php artisan storage:link

# Lancer le serveur local
php artisan serve
```

Ouvrir : `http://localhost:8000`

---

## URLs du système

| Page | URL |
|------|-----|
| Formulaire d'inscription | `/inscription/itaf-2026` |
| Confirmation (après inscription) | `/confirmation/{token}` |
| Kiosque de scan | `/kiosque` |
| Tableau de bord admin | `/admin` |
| Liste des visiteurs | `/admin/visiteurs` |
| Journal des scans | `/admin/scans` |
| Export CSV | `/admin/visiteurs/exporter/csv` |

---

## Configuration de l'imprimante thermique

### Via USB (Linux)

```
IMPRIMANTE_TYPE=file
IMPRIMANTE_CIBLE=/dev/usb/lp0
```

### Via réseau (IP:Port)

```
IMPRIMANTE_TYPE=network
IMPRIMANTE_CIBLE=192.168.1.100:9100
```

### Via Windows (nom de l'imprimante)

```
IMPRIMANTE_TYPE=windows
IMPRIMANTE_CIBLE="POS Thermal Printer"
```

---

## Dépendances clés

| Package | Usage |
|---------|-------|
| `simplesoftwareio/simple-qrcode` | Génération QR code PNG |
| `mike42/escpos-php` | Impression badge thermique ESC/POS |
| `barryvdh/laravel-dompdf` | PDF (optionnel) |
| `jsQR` (CDN) | Décodage QR code via caméra |
| `Chart.js` (CDN) | Graphiques dashboard |
| `Bootstrap 5` (CDN) | Interface utilisateur |

---

## Support

Pour toute question : a.moujib@smart-events.ma

# AI Secure Data — XAMPP Project

A PHP + MySQL version of the AI Secure Data project, converted from Django/PostgreSQL.

## Stack
- **PHP 7.4+** (replaces Python/Django)
- **MySQL** via phpMyAdmin (replaces PostgreSQL)
- **AES-256-CBC** via PHP OpenSSL (replaces Python Fernet)
- **XAMPP** (Apache + MySQL)

## Quick Start

1. Start **Apache** and **MySQL** in XAMPP Control Panel
2. Copy `ai_secure_data/` → `C:\xampp\htdocs\`
3. Open phpMyAdmin → run `database.sql`
4. Visit `http://localhost/ai_secure_data/generate_key.php` → copy key
5. Paste key into `includes/config.php` as `ENCRYPT_KEY`
6. Delete `generate_key.php`
7. Open `http://localhost/ai_secure_data/`

## Pages
| URL | Description |
|-----|-------------|
| `/` | Encrypt & Decrypt text |
| `/history.php` | View & delete stored records |
| `/setup.php` | Live diagnostics + setup guide |
| `/generate_key.php` | One-time key generator (delete after use) |

## Files
```
ai_secure_data/
├── index.php           # Main encrypt/decrypt page
├── history.php         # View stored records
├── setup.php           # Setup guide + live diagnostics
├── generate_key.php    # One-time key generator (DELETE after use)
├── database.sql        # DB + table creation SQL
├── includes/
│   ├── config.php      # DB credentials + encryption key
│   └── encryption.php  # AES-256-CBC encrypt/decrypt functions
└── assets/
    └── css/style.css   # Stylesheet
```

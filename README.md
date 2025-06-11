# API Loan - Documentation

## Installation sans Docker

### Prérequis
- PHP 8.1 ou supérieur
- Composer
- Serveur web (Apache/Nginx) avec support PHP
- Base de données MySQL/MariaDB

### Installation

1. Cloner le repository :
```bash
git clone 
cd test-api-loan
```

2. Installer les dépendances :
```bash
composer install
```
3. Démarrer le serveur de développement (optionnel) :
```bash
php -S localhost:8000 -t public
```

L'application sera accessible sur `http://localhost:8000`

## Exemples de requêtes CURL

### 1. Recherche d'offres de prêt

```bash
curl -X POST http://localhost:8000/api/loan-offers/search \
-H "Content-Type: application/json" \
-d '{
    "amount": 50000,
    "duration": 15,
    "name": "Jean Dupont",
    "email": "jean.dupont@example.com",
    "phone": "0612345678"
}'
```

### Paramètres valides :
- Montant : 50000, 100000, 200000, 500000
- Durée : 15, 20, 25 mois

### Exemple de réponse réussie :
```json
{
    "amount": 50000,
    "duration": 15,
    "name": "Jean Dupont",
    "email": "jean.dupont@example.com",
    "phone": "0612345678",
    "offers": [
        {
            "bank": "Banque A",
            "monthly_payment": 4500.00,
            "total_amount": 67500.00,
            "interest_rate": 6.00
        },
        {
            "bank": "Banque B",
            "monthly_payment": 4400.00,
            "total_amount": 66000.00,
            "interest_rate": 5.50
        }
    ]
}
```

### Exemple de requête avec erreur de validation :
```bash
curl -X POST http://localhost:8000/api/loan-offers/search \
-H "Content-Type: application/json" \
-d '{
    "amount": 30000,  # Montant invalide
    "duration": 15,
    "name": "",
    "email": "invalid-email",
    "phone": "123"
}'
```

### Exemple de réponse d'erreur :
```json
{
    "errors": {
        "amount": ["Cette valeur n'est pas valide."],
        "name": ["Cette valeur ne doit pas être vide."],
        "email": ["Cette valeur n'est pas une adresse email valide."],
        "phone": ["Cette valeur ne doit pas être vide."]
    }
}
```

## Codes de statut HTTP
- 200 : Requête réussie
- 400 : Données invalides
- 500 : Erreur interne du serveur

## Sécurité
- Toutes les routes nécessitent une requête POST
- Validation des données d'entrée
- Protection contre les injections SQL
- Sanitisation des données de sortie

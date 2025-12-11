## PHPStan (Analyse statique)

Ce projet utilise PHPStan pour l'analyse statique du code. PHPStan aide à détecter les erreurs de types, les appels de méthode sur des objets non-typés, et d'autres soucis avant l'exécution.

### Comment l'utiliser localement

1. Installer les dépendances :
```powershell
composer install
```

2. Lancer PHPStan (configuration dans `phpstan.neon.dist`) :
```powershell
vendor/bin/phpstan analyse -c phpstan.neon.dist
```

3. Si l'analyse signale beaucoup d'erreurs initialement, tu peux générer un baseline :
```powershell
vendor/bin/phpstan analyse -c phpstan.neon.dist --generate-baseline=phpstan-baseline.neon
```

### Niveau de base
La configuration initiale utilise le niveau 5 pour un bon compromis entre détection d'erreurs et bruit.

### CI
Un job GitHub Actions exécute PHPStan (si installé) pour signaler automatiquement les problèmes sur chaque PR.

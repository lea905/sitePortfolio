# Portfolio Symfony

## Dépannage
Si vous rencontrez des erreurs de connexion à la base de données (ex: MySQL timeout), assurez-vous de configurer correctement votre fichier `.env.local` pour forcer l'usage de SQLite si nécessaire :
```env
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
```
Ensuite, exécutez les migrations :
```bash
php bin/console doctrine:migrations:migrate
```

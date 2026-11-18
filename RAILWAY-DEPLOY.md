# KKNIC GREEN — deploy to Railway

The app uses PHP 8.3, Apache and mysqli. The Dockerfile listens on Railway's `PORT` variable.

## In Railway

1. Create a **New Project → Deploy from GitHub repo**, select `wanx-wan/KKNIC-GREEN` and branch `main`.
2. Add a **MySQL** service to the same Railway project.
3. In the **web service → Variables**, add reference variables to the MySQL service (assuming the database service is named `MySQL`):

   ```text
   MYSQLHOST=${{MySQL.MYSQLHOST}}
   MYSQLPORT=${{MySQL.MYSQLPORT}}
   MYSQLUSER=${{MySQL.MYSQLUSER}}
   MYSQLPASSWORD=${{MySQL.MYSQLPASSWORD}}
   MYSQLDATABASE=${{MySQL.MYSQLDATABASE}}
   ```

4. Apply/deploy the staged changes and generate a public domain under **Settings → Networking**.
5. Import your existing `kknicdb` SQL schema and seed data into the Railway MySQL database **before testing login, registration or activities**. This repository does not contain the SQL schema. An empty database will not run the app.
6. Enable **Auto Deploys** for `main` under the web service's source/deploy settings.

## Persist evidence uploads

Add a persistent **volume** to the web service mounted at `/var/www/html/uploads`. Without a volume, uploaded evidence files may be lost on redeploy. The start script sets permissions on the upload directory.

## Safety

- The legacy password reset flow originally allowed a reset by phone number without proving ownership; it is disabled until a verified recovery method is implemented.
- Test users, admin-only actions, evidence uploads, and reports before opening this service to the public.
- Do not commit .env files, real MySQL passwords, production SQL dumps or user uploads.
- Railway services and MySQL storage can incur charges depending on the plan and usage.

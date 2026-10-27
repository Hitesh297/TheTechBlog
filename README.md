# TheTechBlog
This a website developed on PHP and has a content management system

Visit site at [http://hitesh-patel.infinityfreeapp.com/mycms/](http://hitesh-patel.infinityfreeapp.com/mycms/)

## SQL Server setup

The application uses PDO with the Microsoft SQL Server PDO driver (`pdo_sqlsrv`).
Create an empty SQL Server database and run [`cms.sql`](./cms.sql) against it
before starting the application. This SQL Server script creates all three
tables, their constraints, and inserts the migrated seed data (4 articles,
4 authors, and 2 users). It preserves the IDs and photo data from the original
MariaDB dump. Run it only once per empty database.

Copy `.env.example` to `.env` and fill in the SQL Server connection settings.
The local `.env` file is ignored by Git. Values supplied directly in the PHP
process environment take precedence over values in `.env`.

- `SQLSERVER_HOST` — SQL Server host, optionally including a port (for example, `localhost,1433`)
- `SQLSERVER_DATABASE` — database name
- `SQLSERVER_USERNAME` — login name
- `SQLSERVER_PASSWORD` — login password

Quote values in `.env` if they contain leading/trailing whitespace or a `#`.
The SQL Server PDO driver and PHP GD extension must be enabled. GD is required
to resize and serve article and author photos. Connections require encryption
and validate the server certificate.

## Azure App Service deployment

Create a **Linux Web App** and choose **Publish: Code** with the **PHP 8.5**
runtime stack. Deploy the repository's PHP files with the Azure App Service
extension for Visual Studio Code or your preferred App Service deployment
method. The app is served from the repository root; no custom startup command
or container port setting is required.

In the Web App's **Environment variables** settings, add
`SQLSERVER_HOST`, `SQLSERVER_DATABASE`, `SQLSERVER_USERNAME`, and
`SQLSERVER_PASSWORD`. Use the Azure SQL endpoint such as
`tcp:<server>.database.windows.net,1433`, the database name, and SQL login.
App Service injects these settings into PHP's process environment, which takes
precedence over the local `.env` file. Store the password in an App Service
setting or Key Vault reference. Do not deploy `.env` or commit production
credentials.

Allow the Web App's outbound IP addresses in the Azure SQL firewall, or
configure private networking. Enable App Service **Health check** with the path
`/health.php`; it returns success only when a database query succeeds. After
deployment, verify the health check and an image URL. The PHP runtime must
provide `pdo_sqlsrv` and GD; if either extension is unavailable in the selected
runtime, use a custom container deployment instead.

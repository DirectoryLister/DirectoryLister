# Authentication

Directory Lister does not include any form of authentication out of the box.
This is intentional to keep the application focused and easier to maintain.
However, there are ways to use external authentication with Directory Lister.
The following are some known methods of adding authentication to your
application.

## HTTP Basic Authentication

Modern web servers have the ability to restrict access to a directory via a
`.htpasswd` file. The way this is configured varies based on the web server in
use so you will need to reference the documentation for your web server.

### Apache

  - [`mod_auth_basic` documentation](https://httpd.apache.org/docs/2.4/mod/mod_auth_basic.html)
  - [`.htpasswd` documentation](https://httpd.apache.org/docs/current/programs/htpasswd.html)

### NGINX

  - [Basic Authentication documentation](https://docs.nginx.com/nginx/admin-guide/security-controls/configuring-http-basic-authentication/)

## Proxy Authorization

You may use proxy authentication servers such as [Tinyauth](https://tinyauth.app)
or [Authelia](https://www.authelia.com) to add authorization to an instance of 
Directory Lister behind a reverse proxy.

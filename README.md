
# Anleitung Inbetriebnahme mit Docker
Diese Anleitung zeigt, wie OpenDataSpace.io auf dem lokalen Rechner mit Docker eingerichtet wird.

## Voraussetzungen
* git
* Docker / Docker Desktop

## Anleitung
Eine Schritt für Schritt Anleitung.

1. Clone Git Repository oder Code-Ordner in Editor öffnen.
    
    ```
    git clone https://github.com/OpenDataSpace-io/opendataspace.io.git

2. Docker Build
    
    ```
    docker compose build --no-cache
    ```

3. Docker Container starten
    
    ```
    docker-compose up -d
    ```

⚠️Hinweis:

Beim ersten mal müssen alle Packages heruntergeladen werden. Das kann eine Weile dauern.
Warten bis app-php und app-pwa bereit sind.

4. Demo Daten importieren
    
    ```
    docker-compose exec php bin/console doctrine:fixtures:load
    ```

Mit “yes” bestätigen.

5. Keycloak Demo importieren

⚠️Hinweis:

Der keycloak-config-cli-Job funktioniert zur Zeit nicht. Daher müssen wir die Demo-Realm manuell erstellen.

5.1 In die Keycloak Console einloggen.

https://localhost/oidc/admin/master/console/

Username: admin

Password: !ChangeMe!

5.2 Erstelle Demo realm

https://localhost/oidc/admin/master/console/#/master/add-realm

5.3 Demo Real importieren

https://github.com/OpenDataSpace-io/opendataspace.io/blob/main/helm/api-platform/keycloak/config/realm-demo.json

6. https://localhost öffnen und einloggen


#!/bin/sh
# Instala gettext y transforma el archivo

apk add --no-cache gettext > /dev/null

# Reemplazar variables de entorno en el script SQL
envsubst < /init/sample_api.sql.template > /init/sample_api.sql

<?php

//define("DB_HOST", "localhost");
//define("DB_USER", "root");
//define("DB_PASS", "eaa69cpxy2");
define("DB_HOST", "easybarcos.mysql.dbaas.com.br");
define("DB_USER", "easybarcos");
define("DB_PASS", "yoGo95b0");

define("APP_NAME", "Easy Barcos");

define("EMAIL_REMETENTE", "rodrigo@emagine.com.br");
define("NOME_REMETENTE", "Rodrigo Landim");

define("MAILJET_HOST", "in.mailjet.com");
define("MAILJET_EMAIL", "contato@imobsync.com.br");
define("MAILJET_USERNAME", "2e46011b1b85a6de9e6f8220ae5eb0ab");
define("MAILJET_PASSWORD", "a837abb0dedec02c536719161020068d");

define("MAIL_HOST", "smtp.gmail.com");
define("MAIL_EMAIL", "no-reply@emagine.com.br");
define("MAIL_USERNAME", "no-reply@emagine.com.br");
define("MAIL_PASSWORD", "eaa69cpxy2");

define("DB_NAME", "easybarcos");
define("TEMA_PATH", "/app");
//define("TEMA_PATH", "/easy-barcos");
//define("TEMA_PATH", "/emagine-frete");
define('CACHE_DIR', "/var/www/emagine.com.br/upload/cache");
define("UPLOAD_PATH", "/var/www/emagine.com.br/upload");

define("MAX_PAGE_COUNT", 10);

define("MAIL_BASE_URL", "http://emagine.com.br" . TEMA_PATH);
define("SITE_URL", "http://emagine.com.br" . TEMA_PATH);

define("USUARIO_TELEFONE_OBRIGATORIO", true);
define("USUARIO_USA_FOTO", false);

define("FRETE_CALCULO_ROTA", false);
define("FRETE_VELOCIDADE_MEDIA", 30);
define("GOOGLE_MAPS_API", "AIzaSyBgrWD-mJvKK7DJbRFKECMxxUYXJXgHp-I");

define("BLL_FRETE", "Emagine\\Frete\\EasyBarcos\\BLL\\FreteBLL");

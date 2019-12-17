#!/bin/sh
FRETE_DIR=/f/xampp/htdocs/emagine-frete
./vendor/bin/phpunit \
    --bootstrap $FRETE_DIR/src/Test/config.php \
    --testdox $FRETE_DIR/vendor/landim32/emagine-log/src/Test/LogTest.php
./vendor/bin/phpunit \
    --bootstrap $FRETE_DIR/src/Test/config.php \
    --testdox $FRETE_DIR/vendor/landim32/emagine-pagamento/src/Test/PagamentoTest.php
./vendor/bin/phpunit \
    --bootstrap $FRETE_DIR/src/Test/config.php \
    --testdox $FRETE_DIR/src/Test/FreteTest.php
<?php
namespace Emagine\CRM;

use Emagine\Base\EmagineApp;
use Emagine\Login\Controls\UsuarioPerfil;

/**
 * @var EmagineApp $app
 */

?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php echo UsuarioPerfil::render(); ?>
            <?php echo $app->getMenu("lateral"); ?>
        </div>
        <div class="col-md-9">
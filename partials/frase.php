<?php
$args = $args['horizonte'];
?>
<section class="horizon bg-cervezas">
    <div class="gr-12 no-gutter">
        <div class="box box--frase" style="background-image: url(<?php echo $args['imagen_de_fondo']['url']; ?>);">
            <div class="container">
                <div class="box__body">
                    <p class="box__frase"><?php echo $args['frase']; ?></p>
                    <p class="box__autor"><?php echo $args['autor']; ?></p>
                </div>
            </div>
        </div>
    </div>
</section>
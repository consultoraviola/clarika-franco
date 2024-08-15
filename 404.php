<?php 
get_header(); ?>
<?php $pagina = get_field('page_404', 'options'); ?>
<main>
    <section class="gutter-top-12 gutter-bottom-4 no-overflow gutter-vertical-2">
        <div class="container">
            <div class="row">
                <div class="gr-12 gr-12@medium">
                    <section class="horizon horizon--error layout layout-error gutter-vertical-2">
                        <div class="row justify-content-center">
                            <div class="gr-6 gr-8@medium gr-10@tablet gr-12@small is-flex-col-center-middle has-gap-1 text-center">
                                <h1 class="horizon__title"><?php echo $pagina['titulo'] ?></h1>
                                <div class="horizon__excerpt margin-top-2">
                                    <?php echo apply_filters('the_content', $pagina['bajada']) ?>
                                </div>
                                <?php if(isset($pagina['enlace']) && !empty($pagina['enlace'])): $enlace = $pagina['enlace'] ?>
                                <div class="horizon__action gutter-top-2">
                                    <a href="<?php echo $enlace['url'] ?>" class="button button--main button--wide" title="<?php echo $enlace['title'] ?>" target="<?php echo $enlace['target'] ?>"><?php echo $enlace['title'] ?></a>
                                </div>
                                <?php else: ?>
                                <div class="horizon__action gutter-top-2">
                                    <a href="/" class="button button--full-black" title="Ir a ">Ir a la página de inicio</a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>
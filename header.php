<?php require_once('_meta-header.php'); ?>
<header class="header">
    <div class="container nav-bar">
        <div class="nav-bar__brand">
            <h1 class="hide">Franco Violaa</h1>
            <a class="app-brand" href="<?php echo home_url() ?>">
                <?php $logo = get_field('logo_principal_desktop', 'options'); ?>
                <img src="<?php echo $logo['url']; ?>" class="app-brand__logo">
            </a>
        </div>

        <nav class="nav-bar__menu">
            <button class="nav-bar__mobile-menu" id="menu-mobile" aria-label="Ver menú">
                <span></span><span></span><span></span>
            </button>

            <?php
            wp_nav_menu(
                array(
                    'menu'              => "menu-main",
                    'theme_location'    => "menu-main",
                    'menu_class'        => "nav-bar__menu",
                    'menu_id'           => "main-menu",
                )
            );
            ?>
        </nav>
    </div>
</header>

<?php

/**
 * Anade o mezcla un array de tax_query existente con otro devolviendo el resultado
 * @param  mixed - $old_tax_query - tax_query actual, puede estar vacio
 * @param  array - $aditional_tax_query - tax_query que agregar
 * @param  string $relation = relation del tax_query, default: "AND"
 * @return array - tax_query formateado y mesclado
 */
function merge_tax_query($old_tax_query, $aditional_tax_query, $relation = 'AND')
{
    if (!$old_tax_query || !is_array($old_tax_query)) {
        return array($aditional_tax_query);
    }
    if (!isset($old_tax_query['relation']) || !$old_tax_query['relation']) {
        $old_tax_query['relation'] = $relation;
    }
    $old_tax_query[] = $aditional_tax_query;
    return $old_tax_query;
}

/**
 * cut_string_to
 * @param  [string] $string     [Texto a cortar]
 * @param  [int] $charnum       [Numero de caracteres máximo para el texto]
 * @param  string $sufix        [Sufijo para el texto cortado]
 * @return [string]             [Texto cortado]
 */
function cut_string_to($string, $charnum, $sufix = ' ...')
{
    $string = strip_tags($string);
    if (strlen($string) > $charnum) {
        $string = substr($string, 0, ($charnum - strlen($sufix))) . $sufix;
    }
    return mb_convert_encoding($string, "UTF-8");
}

/**
 * [printme]
 * Imprime en pantalla cualquier cosa entre <pre>
 * @param  [mixed] $thing [description]
 * @return void
 */
function printme($thing)
{
    echo '<pre style="display:block; background: black; color:white; width:100%; border: 1px solid white;">';
    print_r($thing);
    echo '</pre>';
}

/**
 * [ensure_url]
 * Convierte un string con forma de url en una URL valida, si ya es una URL valida entonces se devuelve tal cual
 * @param  [type] $proto_url [description]
 * @return [type]            [description]
 */
function ensure_url($proto_url, $protocol = 'http')
{
    // se revisa si es un link interno primero
    if (substr($proto_url, 0, 1) === '/') {
        return $proto_url;
    }
    if (filter_var($proto_url, FILTER_VALIDATE_URL)) {
        return $proto_url;
    } elseif (substr($proto_url, 0, 7) !== 'http://' || substr($proto_url, 0, 7) !== 'https:/') {
        $url = $protocol . '://' . $proto_url;
    }
    // doble chequeo de validacion de URL
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return '';
    }
    return $url;
}

/**
 * Devuelve la URL de un attachment o false si no se encuentra el attachment
 * @param  [int] $id   ID del attachment
 * @param  [string] $size Nombre del tamano a sacar
 * @return [string] URL de la imagen en el tamano solicitado (false si es que falla)
 */
function get_image_src($id, $size)
{
    $img_data = wp_get_attachment_image_src($id, $size);
    if (empty($img_data)) {
        return false;
    }
    return $img_data[0];
}

/**
 * Revisa si el script dado ya se encuentra en la cola de impresion
 * si no es asi lo mete en la cola
 * @param  [string] $script_name [nombre del script a incluir]
 * @return void
 */
function needs_script($script_name)
{
    if (!wp_script_is($script_name)) {
        wp_enqueue_script($script_name);
    }
}


function needs_style($style_name)
{
    if (!wp_style_is($style_name)) {
        wp_enqueue_style($style_name);
    }
}

/**
 * Devuelve el nombre del rol de un usuario
 * @param  [object] $user Objeto de usuario de wordpress
 * @return [string]
 */
function get_user_role($user)
{
    $user_roles = $user->roles;
    $user_role = array_shift($user_roles);
    return $user_role;
}

/**
 * [object_to_array]
 * Devuelve el nombre del rol de un usuario
 * @param  [object] recibe un objeto o un array con obejtos y lo transforma recursivamente en solo array
 * @return [array]
 */
function object_to_array($obj)
{
    if (is_object($obj)) {
        $obj = (array) $obj;
    }
    if (is_array($obj)) {
        $new = array();
        foreach ($obj as $key => $val) {
            $new[$key] = object_to_array($val);
        }
    } else {
        $new = $obj;
    }
    return $new;
}

/**
 * Devuelve la extension del archivo
 * @param  string $file_path - PATH al archivo
 * @return string - Extension del archivo
 */
function parse_mime_type($file_path)
{
    $chunks = explode('/', $file_path);
    return substr(strrchr(array_pop($chunks), '.'), 1);
}

/**
 * Devuelve el peso del archivo formateado
 * @param  string $attachment_file_path - PATH al archivo
 * @return string - Tamano formateado en kb
 */
function get_attachment_size($attachment_file_path)
{
    return size_format(filesize($attachment_file_path));
}

function get_file_size($fileid, $decimal = 2)
{
    $filepath = get_attached_file($fileid);
    $bytes = filesize($filepath);
    $s = array('b', 'Kb', 'Mb', 'Gb');
    $e = floor(log($bytes) / log(1024));
    return sprintf('%.2f ' . $s[$e], ($bytes / pow(1024, floor($e))));
}

function convert_size($bytes, $decimal = 2)
{
    $s = array('b', 'Kb', 'Mb', 'Gb');
    $e = floor(log($bytes) / log(1024));
    return sprintf('%.2f ' . $s[$e], ($bytes / pow(1024, floor($e))));
}

function get_file_id_by_url($image_url)
{
    global $wpdb;
    $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url));
    return $attachment[0];
}

/**
 * Revisa si una URL es externa o no
 * @param  [string]  $url   - URL a probar
 * @return boolean
 */
function is_external($url)
{
    return !strpos($url, home_url()) || strpos($url, "/") === 0;
}

function print_floatval($number)
{
    return number_format(floatval($number), 5, '.', '');
}

function file_size_format($size)
{
    $units = array('Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');
    return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $units[$i];
}

function get_filesize($file)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $file);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    $filesize = curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD);
    curl_close($ch);
    return file_size_format($filesize);
}

/**
 * Va a buscar un $post en base al slug (post_name) pasado
 * @param  string $slug - Slug o post_name del post que se quiere rescatar
 * @param  string $field - (opcional) Campo especifico que se desea del post.
 *                         Puede ser cualquiera del post object
 * @return mixed - false si no encuentra el post, $post_object (object) si es que lo encuentra
 */
function get_post_by_slug($slug, $field = false)
{
    global $wpdb;
    $pid = $wpdb->get_var($wpdb->prepare("
		SELECT ID
		FROM $wpdb->posts
		WHERE post_name = %s
	", $slug));

    if (!$pid) {
        return false;
    }

    $post_obj = get_post($pid);

    if ($field && $post_obj) {
        return $post_obj->{$field};
    }

    return get_post($pid);
}

/**
 * Devuelve el permalink de un post o pagina desde el slug
 * @param  [string] $slug  - Slug de la pagina o post
 * @return string
 */
function get_link_by_slug($slug)
{
    return get_permalink(get_post_by_slug($slug, 'ID'));
}

function get_span_title($attr)
{
    $print = $break = '';
    if (!empty($attr['titulo'])) :
        !empty($attr['titulo_break']) ? $attr['class'] = $attr['class'] . ' span-break' : $attr['class'] = $attr['class'];

        $print  = '<' . $attr['tag'] . ' class="' . $attr['class'] . '">';
        foreach ($attr['titulo'] as $title) :
            $print .= '<span class="' . $title['tipo'] . '">' . $title['texto'] . '</span>';
            end($attr['titulo']) !== $title ? $print .= ' ' : $print .= '';
        endforeach;
        $print .= '</' . $attr['tag'] . '>';
    endif;
    return $print;
}

function get_enlace_acf($attr)
{
    $print = $subtarget = $class = '';
    if (!empty($attr)) :
        $enlace = $attr;

        !empty($enlace['target']) ? $target = 'target="' . $enlace['target'] . '"' : $target = '';
        if (strpos($enlace['url'], '#') !== false) :
            $subtarget = 'data-func="scrollToTarget" data-offset="0"';
        endif;

        if (isset($attr['class']) && !empty($attr['class'])) :
            $class = $attr['class'];
        endif;

        $print = '<a href="' . $enlace['url'] . '" class="' . $class . '" title="' . $enlace['title'] . '" ' . $target . ' ' . $subtarget . '>' . $enlace['title'] . '</a>';
    endif;
    return $print;
}

function json_metadata($array)
{
    $printjson = '<script type="application/ld+json">' . json_encode($array, JSON_PRETTY_PRINT) . '</script>';
    return $printjson;
}

function post_has_children($post_id)
{
    $query = get_children(array('post_parent' => $post_id));
    return !empty($query);
}

function get_children_by_parent($parent_id)
{
    $result = array();
    $post_type = get_post_type($parent_id);
    $args_parents = array(
        'post_type' => $post_type,
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'post_parent' => $parent_id,
        'orderby' =>  'menu_order',
        'order' => 'ASC'
    );

    $parents = new WP_Query($args_parents);

    if ($parents->have_posts()) :
        while ($parents->have_posts()) : $parents->the_post();
            $result[] = $parents->post->ID;
        endwhile;
    endif;

    wp_reset_query();

    return $result;
}

function site_metadata()
{
    $info_contacto = get_field('informacion_de_contacto', 'options');
    $redes_sociales = get_field('redes_sociales', 'options');
    $schema_ld = array(
        '@context' => 'http://schema.org/',
        '@type' => 'schema:Organization',
        '@id' => home_url(),
        'name' => get_bloginfo('name'),
        'description' => get_post_meta(get_the_ID(), '_yoast_wpseo_metadesc', true) ? get_post_meta(get_the_ID(), '_yoast_wpseo_metadesc', true) : get_bloginfo('description'),
        'email' => $info_contacto['email'],
        'telephone' => $info_contacto['call_center'],
        'url' => home_url(),
        'address' => array(
            '@type' => 'PostalAddress',
            'streetAddress' => $info_contacto['direccion'],
            'addressLocality' => $info_contacto['ciudad'],
            'addressCountry' => $info_contacto['pais'],
        ),
        'brand' => array(
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'url' => home_url(),
            'description' => get_post_meta(get_the_ID(), '_yoast_wpseo_metadesc', true) ? get_post_meta(get_the_ID(), '_yoast_wpseo_metadesc', true) : get_bloginfo('description'),
        ),
        'sameAs' => array($redes_sociales['instagram'], $redes_sociales['facebook'], $redes_sociales['twitter'], $redes_sociales['youtube'])
    );

    $logo_principal = get_field('logo_principal', 'options');
    if (!empty($logo_principal)) :
        $schema_ld['logo'] = wp_get_attachment_image_url($logo_principal, 'original');
    endif;
    $imagen_referencia = $info_contacto['imagen_referencia'];
    if (!empty($imagen_referencia)) :
        $schema_ld['image'] = wp_get_attachment_image_url($imagen_referencia, 'home_1280x720');
    endif;
 
    return '<!--structured data-->' . json_metadata($schema_ld);
}

/**
 * Obtiene los elementos hijos que contengan el padre
 * @return @string html
 */
function get_indice($exclude = array(), $include = array())
{
    global $post;
    //$paged = (get_query_var('paged')) ? absint(get_query_var('paged') - 1) : 0;
    $args = array(
        'post_parent' => $post->ID,
        'post_type'   => 'page',
        'post_status' => 'publish',
        'numberposts' => -1,
        'orderby'     => 'menu_order',
        'order'       => 'ASC',
        'post__not_in' => false
    );
    if (!empty($include)) {
        $args['post__in'] = $include;
        $args['post_parent'] = false;
        $args['orderby'] = 'post__in';
    }
    $child = new WP_Query($args);
    if ($child->have_posts()) {
        wp_reset_query();
        return $child;
    }
}

function get_custom_featured($post_type)
{
    $args = array(
        'post_type'   => $post_type,
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'orderby'     => 'date',
        'order'       => 'DESC'
    );
    $child = new WP_Query($args);
    if ($child->have_posts()) {
        while ($child->have_posts()) : $child->the_post();
            $result[] = $child->post->ID;
        endwhile;
    }
    wp_reset_query();
    return $result;
}
/**
 * map array of color into taxonomy
 * @return @string
 */
function translate_terms_to_colors($taxonomia, $post_id)
{
    $terms = get_the_terms($post_id, $taxonomia);
    $colors = array();
    if (!empty($terms)) :
        foreach ($terms as $term) :
            $color = get_field('color', $taxonomia . '_' . $term->term_id);
            if (!empty($color)) :
                $colors[] = $color;
            endif;
        endforeach;
    endif;
    return $colors;
}

/**
 * Use radio inputs instead of checkboxes for term checklists in specified taxonomies.
 *
 * @param   array   $args
 * @return  array
 */
function wpse_139269_term_radio_checklist($args)
{
    if (
        (!empty($args['taxonomy']) && $args['taxonomy'] === 'tipo-proyecto')
        ||
        (!empty($args['taxonomy']) && $args['taxonomy'] === 'componente')
        ||
        (!empty($args['taxonomy']) && $args['taxonomy'] === 'rol_destinatarios')
        ||
        (!empty($args['taxonomy']) && $args['taxonomy'] === 'destinatario')
        ||
        (!empty($args['taxonomy']) && $args['taxonomy'] === 'tipo_registro')
        /* <== Change to your required taxonomy */
    ) {
        if (empty($args['walker']) || is_a($args['walker'], 'Walker')) { // Don't override 3rd party walkers.
            if (!class_exists('WPSE_139269_Walker_Category_Radio_Checklist')) {
                /**
                 * Custom walker for switching checkbox inputs to radio.
                 *
                 * @see Walker_Category_Checklist
                 */
                class WPSE_139269_Walker_Category_Radio_Checklist extends Walker_Category_Checklist
                {
                    function walk($elements, $max_depth, ...$args)
                    {
                        $output = parent::walk($elements, $max_depth, ...$args);
                        $output = str_replace(
                            array('type="checkbox"', "type='checkbox'"),
                            array('type="radio"', "type='radio'"),
                            $output
                        );

                        return $output;
                    }
                }
            }

            $args['walker'] = new WPSE_139269_Walker_Category_Radio_Checklist;
        }
    }

    return $args;
}

add_filter('wp_terms_checklist_args', 'wpse_139269_term_radio_checklist');

//crear funcion get_proyectos_home()
function get_proyectos_home()
{
    $args = array(
        'post_type'   => 'proyecto',
        'post_status' => 'publish',
        'posts_per_page' => 7,
        'orderby'     => 'date',
        'order'       => 'DESC'
    );
    $child = new WP_Query($args);
    if ($child->have_posts()) {
        while ($child->have_posts()) : $child->the_post();
            $result[] = $child->post->ID;
        endwhile;
    }
    wp_reset_query();
    return $result;
}
// function get_bitacoras_proyecto($post__in)
// {
//     global $post;
//     $proyecto_id = $post->ID;
//     if (empty($post__in)) {
//         return false;
//     }
//     $args = array(
//         'post_type'   => 'bitacora',
//         'post_status' => 'publish',
//         'orderby'     => 'post__in',
//         'post__in'    => $post__in
//     );
//     $child = new WP_Query($args);
//     if ($child->have_posts()) {
//         while ($child->have_posts()) : $child->the_post();
//             if (is_page("ficha-privada")) {
//                 $uri = explode('/', $_SERVER['REQUEST_URI']);
//                 if (get_field('proyecto', $child->post->ID) == $uri[4]) {
//                     $result[] = $child->post->ID;
//                 }
//             } else {
//                 if (get_field('proyecto', $child->post->ID) == $proyecto_id) {
//                     //si la taxonomia es tipo_registro privado se excluye
//                     $terms = get_the_terms($child->post->ID, 'tipo_registro');
//                     if (!empty($terms)) :
//                         foreach ($terms as $term) :
//                             if ($term->slug != 'privado') {
//                                 $result[] = $child->post->ID;
//                             }
//                         endforeach;
//                     endif;
//                 }
//             }
//         endwhile;
//     }
//     wp_reset_query();
//     return $result;
// }
// function get_testimonios_proyecto($post__in)
// {
//     global $post;
//     $proyecto_id = $post->ID;
//     if (empty($post__in)) {
//         return false;
//     }
//     //testimonios pusblished but not private
//     $args = array(
//         'post_type'   => 'testimonio',
//         'post_status' => 'publish',
//         'orderby'     => 'post__in',
//         'post__in'    => $post__in,
//     );
//     $child = new WP_Query($args);
//     if ($child->have_posts()) {
//         while ($child->have_posts()) : $child->the_post();
//             if (is_page("ficha-privada")) {
//                 $uri = explode('/', $_SERVER['REQUEST_URI']);
//                 if (get_field('proyecto_relacionado_cita', $child->post->ID) == $uri[4]) {
//                     $result[] = $child->post->ID;
//                 }
//             } else {
//                 if (get_field('proyecto_relacionado_cita', $child->post->ID) == $proyecto_id) {
//                     $result[] = $child->post->ID;
//                 }
//             }
//         endwhile;
//     }
//     wp_reset_query();
//     return $result;
// }

function get_proyectos_dashboard()
{
    //Estamos en una dashboard privado donde solo veo los proyectos en los que yo soy [meta_query] artista educador, encargado acciona o profesor dupla relacionado, 
    //pero si soy role administrador o administrador_acciona veo todos los proyectos

    $current_user = wp_get_current_user();
    //the user have a ACF field relationship "institucion" with the ID of the establecimiento_educacional whish he is related
    $institucion = get_field('institucion', 'user_' . $current_user->ID);
    // setup vars on false for wp_query
    $tax_query = [];
    $meta_query = [];

    /** 
     * Cuando estamos en la zona privada o dashboard
     * Caso 1
     * si el role del usuario logueado es = a administrador o igual a encargado-acciona 
     * se muestran todos los proyectos .
     * 
     * Caso 2
     * Si el role del usuario logueado es artista-educador solo ve los proyectos donde su id esta en acf field artista_educador.
     * 
     * Caso 3
     * Si el role del usuario logueado es profesor-dupla solo ve los proyectos donde su id esta en acf field  profesor_dupla.
     *
     * Caso 4
     * Si role del usuario logueado es encargado-establecimiento-educacional  
     *  ve todos los proyectos donde ACF rbd_proyecto del posttype proyecto = rbd del posttype establecimiento_educacional 
     *  y donde ACF encargado_acciona  del posttype establecimiento_educacional es el id del usuario logueado.
     * 
     * Caso 5
     * Si role del usuario logueado es encargado-institucion-cultural  
     *  ve todos los proyectos donde ACF institucion_cultural del posttype proyecto = al ID posttype institucion 
     *  y donde ACF encargada  del posttype institucion es el id del usuario logueado.
     * 
     * Caso 6
     * Si role del usuario logueado es encargado-institucion-implementadora  
     *  ve todos los proyectos donde ACF institucion_implementadora del posttype proyecto = al ID posttype implementadora 
     *  y donde ACF encargado_acciona  del posttype implementadora es el id del usuario logueado
     * 
     * Caso 7
     * Si estamos en la zona publica se muestran todos los proyectos 
     * 
     * En todos los casos siempre es ordenados por meta_query ano descendente.
     */

    /**
     * En todos los casos es postype es proyecto 
     * En todos los casos posts_per_page es 25
     * En todos los casos es paged 
     * En todos los casos es ordenado por meta_query ano descendente.
     * En todos los casos meta_query se usa para filtrar
     * En todos los casos tax_query se usa para filtrar
     */

    $args['post_type'] = 'proyecto';
    $args['posts_per_page'] = 10;
    $args['meta_key'] = 'ano';
    $args['orderby'] = 'rand';

    // $estado = $_POST['estado'];
    // if (!empty($region)) {
    //     $meta_query[] = array('key' => 'estado', 'compare' => '=', 'value' => 'en-curso');
    // }

    // caso 2
    if (current_user_can('artista-educador')) {
        $meta_query[] = array('key' => 'artista_educador', 'compare' => 'IN', 'value' => $current_user->ID);
    }
    // caso 3
    if (current_user_can('profesor-dupla')) {
        $meta_query[] = array('key' => 'docente_dupla', 'compare' => 'IN', 'value' => $current_user->ID);
    }
    // caso 4
    if (current_user_can('encargado-establecimiento-educacional')) {
        //get the rbd from the post
        $rbd = get_field('rbd', $institucion);
        //add the meta_query. It is jus one RBD per proyecto
        $meta_query[] = array('key' => 'rbd_proyecto', 'compare' => '=', 'value' => $rbd);
    }
    // caso 5
    if (current_user_can('encargado-institucion-cultural')) {
        $meta_query[] = array('key' => 'institucion_cultural', 'compare' => '=', 'value' => $institucion);
    }
    // caso 6 implementadora
    if (current_user_can('encargado-institucion-implementadora')) {

        $meta_query[] = array('key' => 'institucion_implementadora', 'compare' => '=', 'value' => $institucion);
    }

    // si el meta_query es más de un elemento, se debe agregar el relation
    if ($meta_query && count($meta_query) > 1) {
        $meta_query_operator = array('relation' => 'AND');
        $meta_query = array_merge($meta_query_operator, $meta_query);
    }

    $args['meta_query'] = $meta_query;
    $args['tax_query'] = $tax_query;
    $child = new WP_Query($args);
    if ($child->have_posts()) {
        while ($child->have_posts()) : $child->the_post();
            $result[] = $child->post->ID;
        endwhile;
    }
    wp_reset_query();
    return $result;
}

function the_slug_is_ancestor($post_id_or_slug)
{
    global $post;
    // $post_id_or_slug = (int) $post_id_or_slug;
    if (is_archive() || $post == null || is_admin()) {
        return;
    }
    if (!$post_id_or_slug) {
        $post_id_or_slug = $post->ID;
    }
    $ancestors = get_post_ancestors($post_id_or_slug);
    if (in_array($post->ID, $ancestors)) {
        return true;
    } else {
        return false;
    }
}

function is_dashboard()
{
    return is_page('dashboard') or the_slug_is_ancestor('dashboard') or strpos($_SERVER['REQUEST_URI'], 'dashboard') ? true : false;
}

function get_botones_author($author_id)
{
    $botones = array();

    $tipo_portafolio = get_field('portafolio', $author_id);
    $tipo_portafolio = $tipo_portafolio !== 'Subir Archivo' ? 'external' : 'download';
    if ($tipo_portafolio == 'external') :
        $portafolio = array('title' => 'Ir al portafolio', 'target' => '_blank', 'url' => get_field('portafolio_enlace') ?? '', 'tipo' => 'external');
    else :
        $portafolio_archivo = get_field('portafolio_archivo', $author_id);
        if (!empty($portafolio_archivo)) :
            $portafolio = array('title' => 'Descargar portafolio', 'url' => $portafolio_archivo['url'], 'tipo' => 'download');
        endif;
    endif;

    $tipo_curriculum = get_field('curriculum', $author_id);
    $tipo_curriculum = $tipo_curriculum !== 'Subir Archivo' ? 'external' : 'download';
    if ($tipo_curriculum == 'external') :
        $curriculum = array('title' => 'Ir al curriculum', 'target' => '_blank', 'url' => get_field('curriculum_enlace') ?? '', 'tipo' => 'external');
    else :
        $curriculum_archivo = get_field('curriculum_archivo', $author_id);
        if (!empty($curriculum_archivo)) :
            $curriculum = array('title' => 'Descargar curriculum', 'url' => $curriculum_archivo['url'], 'tipo' => 'download');
        endif;
    endif;

    if (!empty($portafolio)) :
        $boton_portafolio = array('tipo' => $portafolio['tipo'], 'titulo' => $portafolio['title']);
        if ($portafolio['tipo'] == 'download') :
            $boton_portafolio['archivo'] = $portafolio;
        else :
            $boton_portafolio['enlace'] = $portafolio;
        endif;
        array_push($botones, $boton_portafolio);
    endif;

    if (!empty($curriculum)) :
        $boton_curriculum = array('tipo' => $curriculum['tipo'], 'titulo' => $curriculum['title']);
        if ($curriculum['tipo'] == 'download') :
            $boton_curriculum['archivo'] = $curriculum;
        else :
            $boton_curriculum['enlace'] = $curriculum;
        endif;
        array_push($botones, $boton_curriculum);
    endif;

    return $botones;
}

function proyectos_select()
{

    $current_user = wp_get_current_user();
    //the user have a ACF field relationship "institucion" with the ID of the establecimiento_educacional whish he is related
    $institucion = get_field('institucion', 'user_' . $current_user->ID);
    // setup vars on false for wp_query
    $meta_query = false;

    $args['post_type'] = 'proyecto';
    $args['posts_per_page'] = -1;
    $args['meta_key'] = 'ano';
    $args['orderby'] = 'meta_value_num';
    $args['order'] = 'DESC';

    // caso 2
    if (current_user_can('artista-educador')) {
        $meta_query[] = array('key' => 'artista_educador', 'compare' => 'IN', 'value' => $current_user->ID);
    }
    // caso 3
    if (current_user_can('profesor-dupla')) {
        $meta_query[] = array('key' => 'docente_dupla', 'compare' => 'IN', 'value' => $current_user->ID);
    }
    // caso 4
    if (current_user_can('encargado-establecimiento-educacional')) {
        //get the rbd from the post
        $rbd = get_field('rbd', $institucion);
        //add the meta_query. It is jus one RBD per proyecto
        $meta_query[] = array('key' => 'rbd_proyecto', 'compare' => '=', 'value' => $rbd);
    }
    // caso 5
    if (current_user_can('encargado-institucion-cultural')) {
        $meta_query[] = array('key' => 'institucion_cultural', 'compare' => '=', 'value' => $institucion);
    }
    // caso 6 implementadora
    if (current_user_can('encargado-institucion-implementadora')) {
        $meta_query[] = array('key' => 'institucion_implementadora', 'compare' => '=', 'value' => $institucion);
    }

    // si el meta_query es más de un elemento, se debe agregar el relation
    if ($meta_query && count($meta_query) > 1) {
        $meta_query_operator = array('relation' => 'AND');
        $meta_query = array_merge($meta_query_operator, $meta_query);
    }
    $args['meta_query'] = $meta_query;
    $query_proyectos = new WP_Query($args);
    $field[''] = 'Selecciona un proyecto';
    if ($query_proyectos->have_posts()) {
        while ($query_proyectos->have_posts()) {
            $query_proyectos->the_post();
            $field[$query_proyectos->post->ID] = get_the_title();
        }
    }
    wp_reset_query();
    $query_proyectos->wp_reset_postdata();
    return $field;
}

function get_recursos_dashboard()
{
    $current_user = wp_get_current_user();
    $user = new WP_User($current_user->ID);
    $roles = $user->roles;
    $tax_query = false;

    if (!in_array('administrator', $roles) && !in_array('encargado-acciona', $roles)) {
        $tax_query[] = array('taxonomy' => 'rol_destinatarios', 'field' => 'slug', 'terms' => $roles);
    }
    $args['post_type'] = 'material_educativo';
    $args['post_status'] = ['private'];
    $args['posts_per_page'] = 10;
    $args['tax_query'] = $tax_query;
    $args['orderby'] = 'rand';

    $query = new WP_Query($args);
    if ($query->have_posts()) :
        while ($query->have_posts()) :
            $query->the_post();
            //solo return an array with the post id
            $recursos[] = $query->post->ID;
        endwhile;
    endif;
    $query->wp_reset_postdata();
    return $recursos;
}

<?php
/*This file is part of NewsWords child theme.

All functions of this file will be loaded before of parent theme functions.
Learn more at https://codex.wordpress.org/Child_Themes.

Note: this function loads the parent stylesheet before, then child theme stylesheet
(leave it in place unless you know what you are doing.)
*/

function newswords_enqueue_child_styles() {
    $min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
    $parent_style = 'covernews-style';
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/bootstrap/css/bootstrap' . $min . '.css');
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style(
        'newswords',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'bootstrap', $parent_style ),
        wp_get_theme()->get('Version') );


}
add_action( 'wp_enqueue_scripts', 'newswords_enqueue_child_styles' );


/**
 * slider additions.
 */
require get_stylesheet_directory().'/inc/hooks/hook-front-page-main-banner-section-2.php';



/**
 * Front-page main banner section layout
 */
if(!function_exists('newswords_front_page_main_section_selection')){

    function newswords_front_page_main_section_selection(){

        $hide_on_blog = covernews_get_option('disable_main_banner_on_blog_archive');

            if ($hide_on_blog) {
                if (is_front_page()) {
                    do_action('newswords_action_front_page_main_section_2');
                }

            } else {
                if (is_front_page() || is_home()) {
                    do_action('newswords_action_front_page_main_section_2');
                }

        }
    }
}
add_action('newswords_action_front_page_main_section', 'newswords_front_page_main_section_selection');


/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function newswords_customize_register($wp_customize) {     
     $wp_customize->remove_control('trending_slider_title');
     $wp_customize->remove_control('select_trending_news_category');     
}
add_action('customize_register', 'newswords_customize_register', 99999 );


/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function newswords_widgets_init()
{
    
    register_sidebar(array(
        'name'          => esc_html__('Front-page Banner Ad Section', 'newswords'),
        'id'            => 'home-advertisement-widgets',
        'description'   => esc_html__('Add widgets for frontpage banner section advertisement.', 'newswords'),
        'before_widget' => '<div id="%1$s" class="widget covernews-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title widget-title-1"><span>',
        'after_title' => '</span></h2>',
    ));  


}

add_action('widgets_init', 'newswords_widgets_init');


function newswords_override_banner_advertisment_function(){
    remove_action('covernews_action_banner_advertisement', 'covernews_banner_advertisement', 10);
}

add_action('wp_loaded', 'newswords_override_banner_advertisment_function');

/**
 * Overriding Parent theme Advertisment section
 *
 * @since NewsWords 1.0.0
 *
 */
function newswords_banner_advertisement()
{

    if (('' != covernews_get_option('banner_advertisement_section')) ) { ?>
        <div class="banner-promotions-wrapper">
            <?php if (('' != covernews_get_option('banner_advertisement_section'))):

                $covernews_banner_advertisement = covernews_get_option('banner_advertisement_section');
                $covernews_banner_advertisement = absint($covernews_banner_advertisement);
                $covernews_banner_advertisement = wp_get_attachment_image($covernews_banner_advertisement, 'full');
                $covernews_banner_advertisement_url = covernews_get_option('banner_advertisement_section_url');
                $covernews_banner_advertisement_url = isset($covernews_banner_advertisement_url) ? esc_url($covernews_banner_advertisement_url) : '#';
                $covernews_open_on_new_tab = covernews_get_option('banner_advertisement_open_on_new_tab');
                $covernews_open_on_new_tab = ('' != $covernews_open_on_new_tab) ? '_blank' : '';

                ?>
                <div class="promotion-section">
                    <a href="<?php echo esc_url($covernews_banner_advertisement_url); ?>" target="<?php echo esc_attr($covernews_open_on_new_tab); ?>">
                        <?php echo $covernews_banner_advertisement; ?>
                    </a>
                </div>
            <?php endif; ?>                

        </div>
        <!-- Trending line END -->
        <?php
    }

     if (is_active_sidebar('home-advertisement-widgets')): ?>
                 <div class="banner-promotions-wrapper">
                <div class="promotion-section">
                    <?php dynamic_sidebar('home-advertisement-widgets'); ?>
                </div>
            </div>
            <?php endif; 
}
add_action('covernews_action_banner_advertisement', 'newswords_banner_advertisement', 10);

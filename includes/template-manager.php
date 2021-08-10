<?php

class Template_Manager {
    public function add_filters(){
        add_filter ('theme_page_templates', [$this, 'add_page_template']);
        add_filter ('template_include', [$this, 'redirect_page_template']);
    }

    public function add_page_template($templates) {
        $templates['calendar-template.php'] = 'Calendar';
        return $templates;
    }

    public function redirect_page_template ($template) {
        $post = get_post();
        $page_template = get_post_meta( $post->ID, '_wp_page_template', true );

        if ('calendar-template.php' == basename ($page_template)){
            $template = WP_PLUGIN_DIR . '/event-plugin/templates/calendar-template.php';
        } elseif (is_singular()){
            $template = WP_PLUGIN_DIR . '/event-plugin/templates/single.php';
        }

        return $template;
    }
}
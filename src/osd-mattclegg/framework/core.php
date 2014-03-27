<?php
/*  Copyright 2014  MattClegg  (email : cleggmatt@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
class OSD_MattClegg_SubPages {

    /**
     * @var int
     */
    public static $max_title_length = 20;

    /**
     * Key to identify plugin
     */
    public $plugin_key = 'osd_subpages_mattclegg';

    /**
     * Constant Key to identify plugin
     */
    public static $plugin_ckey = 'osd-mattclegg';


    function __construct(){

        //Check if public site
        if (!is_admin() ) {

            // Registers and enqueues stylesheets
            wp_register_style( $this->plugin_key . '_css', plugin_dir_url() . self::$plugin_ckey . '/assets/stylesheets/main.css' );
            wp_enqueue_style( $this->plugin_key . '_css' );

            wp_register_script( $this->plugin_key . '_js', plugin_dir_url() . self::$plugin_ckey . '/assets/js/main.js', array('jquery') );
            wp_enqueue_script( $this->plugin_key . '_js' );
        }
    }


    /**
     * Check if output required
     *
     * @return int (maximum of 2)
     */
    function hasSubPages(){
        global $post;

        //Do a quick SQL test
        return count(get_pages(array(
            'child_of' => $post->ID,
            'hierarchical' => false,
            'parent' => $post->ID,
            'number' => 2
        )));
    }

    /**
     * Render output
     *
     * @return string Parsed template content
     */
    function render($title = null) {
        global $post;

        require_once( 'walker.php' );

        $the_pages = wp_list_pages(array(
            'title_li' => '',
            'child_of' => $post->ID,
            'echo' => 0,
            'walker' => new OSD_MattClegg_SubPages_Walker()
        ));

        $can_sort_base_level = (self::hasSubPages() > 1 );

        if($the_pages) {
            require dirname( __DIR__ ) . "/views/view.php";
        }
    }
}
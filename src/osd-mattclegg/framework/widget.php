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

class OSD_MattClegg_SubPages_widget extends WP_Widget {

    function __construct() {
        parent::__construct('SubPages_widget', __('Sub Pages Widget', OSD_MattClegg_SubPages::$plugin_ckey),
            array(
                'classname' => 'osd_widget_subpages subpages-container',
                'description' => __('Display subpages', OSD_MattClegg_SubPages::$plugin_ckey)
            )
        );
    }

    public function widget( $args, $instance ) {

        $subpages = new OSD_MattClegg_SubPages();

        //Check if pages exist
        if($subpages->hasSubPages()){
            extract( $args );

            //Set the title
            $title = ( empty( $instance['title'] ) ) ? "" : apply_filters('widget_title',
                $args['before_title'] . $instance['title'] . $args['after_title']);

            //Echo output
            echo $args['before_widget'] . $subpages->render($title) . $args['after_widget'];
        }
    }

    // Widget Backend
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        } else {
            $title = __( 'New title', 'somc_subpages_mattclegg' );
        }
        // Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php
    }

    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }
}
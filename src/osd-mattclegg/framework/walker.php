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

class OSD_MattClegg_SubPages_Walker extends Walker_Page {

    //Map if sort option is required by recording if particular lvl
    var $outputLvlMap = array();

    /**
     * @see Walker::start_lvl()
     * @since 2.1.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int $depth Depth of page. Used for padding.
     * @param array $args
     */
    function start_lvl( &$output, $depth = 0, $args = array() ) {

        //Reset the map for this level
        //$this->outputLvlMap[$depth] = 0;

        $output .= parent::start_lvl( &$output, $depth, $args );
    }

    /**
     * @see Walker::end_lvl()
     * @since 2.1.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int $depth Depth of page. Used for padding.
     * @param array $args
     */
    function end_lvl( &$output, $depth = 0, $args = array() ) {

        $output .= parent::end_lvl( &$output, $depth, $args );

        if ( $this->outputLvlMap[$depth + 1] > 1 ) {
            $output .= '<button class="subpages-sort"></button>';
        }
    }

    /**
     * @see Walker_Page::start_el()
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $page Page data object.
     * @param int $depth Depth of page. Used for padding.
     * @param int $current_page Page ID.
     * @param array $args
     */
    function start_el( &$output, $page, $depth = 0, $args = array(), $current_page = 0 ) {

        $this->outputLvlMap[$depth]++;

        if ( $depth )
            $indent = str_repeat("\t", $depth);
        else
            $indent = '';

        extract($args, EXTR_SKIP);
        $css_class = array('page_item', 'page-item-'.$page->ID);

        if( isset( $args['pages_with_children'][ $page->ID ] ) )
            $css_class[] = 'page_item_has_children';

        if ( !empty($current_page) ) {
            $_current_page = get_post( $current_page );
            if ( in_array( $page->ID, $_current_page->ancestors ) )
                $css_class[] = 'current_page_ancestor';
            if ( $page->ID == $current_page )
                $css_class[] = 'current_page_item';
            elseif ( $_current_page && $page->ID == $_current_page->post_parent )
                $css_class[] = 'current_page_parent';
        } elseif ( $page->ID == get_option('page_for_posts') ) {
            $css_class[] = 'current_page_parent';
        }

        $css_class = implode( ' ', apply_filters( 'page_css_class', $css_class, $page, $depth, $args, $current_page ) );

        if ( '' === $page->post_title )
            $page->post_title = sprintf( __( '#%d (no title)' ), $page->ID );


        /** This filter is documented in wp-includes/post-template.php */
        $output .= $indent . '<li class="' . $css_class . '">';

        $output .= '<a href="' . get_permalink($page->ID) . '">';

        //Truncate the title after any other 3rd party filters have been applied
        $output .= $link_before . $this->truncate_title( apply_filters( 'the_title', $page->post_title, $page->ID ) ) . $link_after;

        // Only add post thumbnail if it exists
        if( has_post_thumbnail($page->ID) )
            $output .= get_the_post_thumbnail($page->ID, array(20,20));

        $output .= '</a>';

        // Only add buttons if pages_with_children is true
        if( isset( $args['pages_with_children'][ $page->ID ] ) )
            $output .= '<button class="subpages-expand"></button>';

        if ( !empty($show_date) ) {
            if ( 'modified' == $show_date )
                $time = $page->post_modified;
            else
                $time = $page->post_date;

            $output .= " " . mysql2date($date_format, $time);
        }

    }


    /**
     * Truncate the title (if longer then defined string length)
     * Firstly attempts to not split in the middle of a word,
     * then returns a multi-byte safe string.
     *
     * @param $title
     * @return string
     */
    function truncate_title($title) {

        $maxLineLength = OSD_MattClegg_SubPages::$max_title_length;

        //Check if required
        if(strlen($title) > $maxLineLength){

            // Auxiliar counters
            $currentLength = 0;
            $output = "";
            $isStringMaxLength = false;

            //Split by word to avoid words being broken in the mid
            foreach(explode(' ', $title) as $word) {

                if(!$isStringMaxLength){

                    // +1 to add back the space lost in explode()
                    $currentLength += strlen($word) + 1;

                    //Check if length will be too long after appending
                    if($currentLength > $maxLineLength) {

                        //Max length reached
                        $isStringMaxLength = true;

                    } else {

                        //Append the string
                        $output .= "{$word} ";

                    }

                }

            }
        }else{
            return $title;
        }

        //Remove trailing whitespace
        $output = rtrim($output);

        //Check if still too long as first word might have too many characters
        if(strlen($output) > $maxLineLength){
            return mb_substr($output, 0, $maxLineLength) . "&#8230;";
        } else {
            return $output . "&#8230;";
        }
    }

}
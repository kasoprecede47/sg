<?php

// Page Title
function coeur_wp_title( $title, $sep ) {
  global $paged, $page;

  if ( is_feed() ) {
    return $title;
  }

  // Add the site name.
  $title .= get_bloginfo( 'name', 'display' );

  // Add the site description for the home/front page.
  $site_description = get_bloginfo( 'description', 'display' );
  if ( $site_description && ( is_home() || is_front_page() ) ) {
    $title = "$title $sep $site_description";
  }

  // Add a page number if necessary.
  if ( $paged >= 2 || $page >= 2 ) {
    $title = "$title $sep " . sprintf( __( 'Page %s', 'twentyfourteen' ), max( $paged, $page ) );
  }

  return $title;
}
add_filter( 'wp_title', 'coeur_wp_title', 10, 2 );

/**
* Paging
*
* @author Frenchtastic.eu
*/
function coeur_paging( $query=null ) {

  global $wp_query;
  $query = $query ? $query : $wp_query;
  $big = 999999999;

  $paginate = paginate_links( array(
    'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
    'type' => 'array',
    'total' => $query->max_num_pages,
    'format' => '?paged=%#%',
    'current' => max( 1, get_query_var('paged') ),
    'prev_text' => __('&laquo;', 'coeur'),
    'next_text' => __('&raquo;', 'coeur'),
    )
  );

  if ($query->max_num_pages > 1) :
    ?>
  <ul class="pagination">
    <?php
    foreach ( $paginate as $page ) {
      echo '<li>' . $page . '</li>';
    }
    ?>
  </ul>
  <?php
  endif;
}

// Extra Functions
add_filter('next_post_link', 'coeur_post_link_attributes');
add_filter('previous_post_link', 'coeur_post_link_attributes');

function coeur_post_link_attributes($output) {
  $injection = 'class="blog-nav-item"';
  return str_replace('<a href=', '<a '.$injection.' href=', $output);
}

function coeur_commentCount(){
  global $post;
  $coeur_the_id = $post->ID;
  $coeur_thepost= get_post($id= $coeur_the_id);
  $coeur_comment_count = $coeur_thepost->comment_count;

  echo '<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="#com_container" data-placement="bottom" rel="tooltip" title="' . __('Show Comments', 'coeur') . '"><i class="fa fa-comment-o"></i> ' .$coeur_comment_count.'</a></li>';
}

function coeur_previousPost() {
  $coeur_prev_post = get_previous_post();
  if(!empty($coeur_prev_post)){
    echo '<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="'.get_permalink( $coeur_prev_post->ID).'"><i class="fa fa-angle-down"></i> '. __('Previous Post', 'coeur') .'</a></li>';
  }
}

function coeur_nextPost() {
  $coeur_next_post = get_next_post();
  if(!empty($coeur_next_post)){
    echo '<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="'.get_permalink( $coeur_next_post->ID).'">'. __('Next Post', 'coeur') .' <i class="fa fa-angle-up"></i></a></li>';
  }
}

function coeur_authorLink(){
  global $post;
  $coeur_author_id = $post->post_author;
  $coeur_author_name = get_the_author_meta( 'display_name', $coeur_author_id );
  $coeur_author_name = ucfirst($coeur_author_name);
  $coeur_author_url = get_author_posts_url( $post->post_author );

  echo '<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="'.$coeur_author_url.'" data-placement="bottom" rel="tooltip" title="' . __('Author\'s profile', 'coeur') . '">'.$coeur_author_name.'</a></li>';
}


/**
* Shows post author link if set to in settings
* @author Frenchtastic
* @since Coeur 1.7
*/
function coeur_author(){
  global $post;
  $coeur_author_id = $post->post_author;
  $coeur_author_name = get_the_author_meta( 'display_name', $coeur_author_id );
  $coeur_author_name = ucfirst($coeur_author_name);
  $coeur_author_url = get_author_posts_url( $post->post_author );

  if (get_theme_mod( 'coeur_show_author' ) == '1'){
    echo __('by', 'coeur'), '<a href="'.$coeur_author_url.'"> '.$coeur_author_name.'</a>';
  }
}

/**
* Changes text preceding the post date
* @author Frenchtastic
* @since Coeur 1.7
*/
function coeur_post_date(){
  $coeur_meta = get_option('coeur_meta_posted');

  if (empty($coeur_meta)){
    echo __('Posted on', 'coeur');
  } else {
    return esc_html_e(get_option('coeur_meta_posted'));
  }
}

/**
* Displays categories if set to do so in settings
* 
* @author Frenchtastic
* @author Nickweb
* @since Coeur 1.7
*/
function coeur_cat(){
      global $post;
      $categories = get_the_category($post->ID);
      $separator = ', ';
      $output = 'in ';
      if($categories && get_theme_mod( 'coeur_show_cat' ) == '1'){
        foreach($categories as $category) {
          $output .= ' <a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( __( 'View all posts in %s', 'coeur' ), $category->name ) ) . '"> '.$category->cat_name.'</a>'.$separator;
        }
        echo trim($output, $separator);
      }
}

function coeur_footer(){
  $coeur_footer = get_option('footer_copyright');

  if(empty($coeur_footer)){
    echo '<a class="footer-credits" href="'.esc_url('http://frenchtastic.eu').'">Design by Frenchtastic.eu</a>';
  } else {
    echo get_option('footer_copyright');
  }
}

/**
* Excerpt length
* @since Coeur 1.0
*/
function coeur_excerpt_length( $length ) {
  return 73;
}
add_filter( 'excerpt_length', 'coeur_excerpt_length', 999 );

/**
* Read more link
* @since Coeur 1.0
*/
function coeur_read_more( $more ) {
  return '.. <a href="'.get_permalink( get_the_ID() ).'">'.__('Read More', 'coeur').'</a>';
}
add_filter('excerpt_more', 'coeur_read_more');

add_filter('wp_list_categories', 'coeur_count_span');
function coeur_count_span($links) {
  $links = str_replace('</a> (', '</a> <span class="cat-count">', $links);
    $links = str_replace(')', '</span>', $links);
    return $links;
}
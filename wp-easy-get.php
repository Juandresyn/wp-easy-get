<?php
function define_id($postid){
  // Check if the $postid is defined or empty
  if ($postid != null && $postid != ""){
    return $postid;
  }else if(get_the_ID()){
    // If is not defined of empty, try to catch the id using wp function get_the_ID()
    return get_the_ID();
  }
}
function get_thumbs($size, $postid){
  // Check the ID
  $the_id = define_id($postid);
  // Posible sizes
  $sizes = array('thumbnail', 'medium', 'large', 'full');
  // Using a default image in case image not exist
  $default_img_id = 10;
  // Check that the size provided is one of the default ones
  if (in_array($size, $sizes)){
    // get the image id of the post
    $thumb_check = get_post_thumbnail_id($the_id);
    if($thumb_check){
      // If the image exist, AKA, the post have an image
      $thumb_id = $thumb_check;
    }else{
      $thumb_id = get_post_thumbnail_id($default_img_id);
    }
    // get the imag url
    $thumb_url_array = wp_get_attachment_image_src($thumb_id, $size, true);
    $result = $thumb_url_array[0];
  }else{
    // if the size is not defined,show the default image.
    $thumb_id = get_post_thumbnail_id($default_img_id);
    $thumb_url_array = wp_get_attachment_image_src($thumb_id, $size, true);
    $result = $thumb_url_array[0];
  }
  return $result;
}
function thumbs($size, $postid){
  echo get_thumbs($size, define_id($postid));
}

//shotcode for thumbs
// Add Shortcode
function thumbs_shortcode( $atts ) {
  // Attributes
  extract( shortcode_atts(
    array(
      'id' => '2',
      'size' => 'thumbnail',
      'class' => 'image',
      'alt' => 'image'
    ), $atts));
  $img = get_thumbs($size, $id);
  return "<img src='$img' alt='$alt' class='$class' data-id='$id'>";
}
add_shortcode( 'thumbs', 'thumbs_shortcode' );

function get_field($field, $postid){
  $the_id = define_id($postid);
  $key = get_post_meta( $the_id, $field, true);
  if (!empty($key)) {
    return $key;
  }
}

function field($field, $postid){
  echo get_field($field, $postid);
}

function field_shortcode( $atts ) {
  // Attributes
  extract( shortcode_atts(
    array(
      'id' => '',
      'key' => ''
    ), $atts));
  $value = get_field($key, $id);
  return $value;
}
add_shortcode( 'custom_field', 'field_shortcode' );

function got_post_category($id, $q = false, $wrapper_start = "", $wrapper_end = ""){
  $the_id = define_id($id);
  $value = get_the_category($the_id);
  if ($q != null && $q != "" && $q == true){
    $posttags = get_the_tags();
    if ($value) {
      foreach($value as $cat) {
        $cats .= htmlentities("$wrapper_start $cat->name $wrapper_end", ENT_QUOTES | ENT_IGNORE, "UTF-8");
      }
    }
    return html_entity_decode($cats);
  }else{
    return esc_html($value[0]->name);
  }
}

function post_category($id, $q, $wrapper_start, $wrapper_end){
  echo got_post_category($id, $q, $wrapper_start, $wrapper_end);
}

function category_shortcode($atts) {
  // Attributes
  extract(shortcode_atts(
    array(
      'id' => '',
      'q' => false,
      'wrapper_start' => '',
      'wrapper_end' => ''
    ), $atts));
  $value = got_post_category($id, $q, $wrapper_start, $wrapper_end);
  return $value;
}
add_shortcode( 'category', 'category_shortcode' );

?>

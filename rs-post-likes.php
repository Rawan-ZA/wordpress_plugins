<?php
/*
   Plugin Name: Posts Liks 
   versions: 1.0
   description: Allow users to add like or dislike for  your posts  .
  */

    //Likes 
function  the_post_likes($post_id = null)
{
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $like = (int) get_post_meta($post_id, '_post_likes', true);
    printf('%d', $like);
}
 //Dislikes 
function  the_post_dislikes($post_id = null)
{
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    $dislike = (int) get_post_meta($post_id, '_post_dislikes', true);
    printf('%d', $dislike);
}
function the_likes_form($post_id)
{
    $allow = get_post_meta($post_id,'_allow_liking',true);
    if($allow == 1){
        include __DIR__ . '/likes-form.php';
    }
}
   //Do likes
function do_like_post()
{
    $nonce = isset($_POST['nonce']) ? $_POST['nonce'] : '';
    if (!wp_verify_nonce($nonce, 'likes_value')) {
        wp_die('Invalid nonce');
    }
    if (isset($_POST['like']) && isset($_POST['post_id'])) {
        $post_id = $_POST['post_id'];
        $like = (int) get_post_meta($post_id, '_post_likes', true);
        $like++;
        update_post_meta($post_id, '_post_likes', $like);
    }
}
        //Admin action 
function like_post()
{
    $post_id = $_POST['post_id'] ?? 0;
    if (!$post_id) {
        wp_die('No Post Selected');
    }
    do_like_post();
    wp_redirect(get_permalink($post_id));
}
    add_action('admin_post_nopriv_likes_value', 'like_post');
    add_action('admin_post_likes_value', 'like_post');

    //Ajax Action 
    function ajax_like_post()
{
    $post_id = $_POST['post_id'] ?? 0;
    if (!$post_id) {
        wp_send_json_error([
            'message' => 'No Post Selected'
        ]);
    }
    do_like_post();
    //return josn as response
    $meta = get_post_meta($post_id);
    wp_send_json([
        'likes' => $meta['_post_likes'][0],
    ]);
}

//  ajax action 
add_action('wp_ajax_nopriv_likes_value', 'ajax_like_post');
add_action('wp_ajax_likes_value', 'ajax_like_post');

   //Do dislike
function do_dislike_post()
{
    $nonce = isset($_POST['nonce']) ? $_POST['nonce'] : '';
    if (!wp_verify_nonce($nonce, 'dislikes_value')) {
        wp_die('Invalid nonce');
    }
    if (isset($_POST['dislike']) &&  isset($_POST['post_id'])) {
        $post_id = $_POST['post_id'];
        $dislike = (int) get_post_meta($post_id, '_post_dislikes', true);
        $dislike++;
        update_post_meta($post_id, '_post_dislikes', $dislike);
    }
}
function dislike_posts()
{
    $post_id = $_POST['post_id'] ?? 0;
    if (!$post_id) {
        wp_die('No Post Selected');
    }
    do_dislike_post();
    wp_redirect(get_permalink($post_id));
}
// admin action 
add_action('admin_post_nopriv_dislikes_value', 'dislike_posts');
add_action('admin_post_dislikes_value', 'dislike_posts');

function ajax_dislike_posts()
{
    $post_id = $_POST['post_id'] ?? 0;
    if (!$post_id) {
        wp_send_json_error([
            'message' => 'No Post Selected'
        ]);
    }
    do_dislike_post();
    // return josn as response
    $meta = get_post_meta($post_id);
    wp_send_json([
        'dislikes' => $meta['_post_dislikes'][0],
    ]);
}

// ajax action 

add_action('wp_ajax_nopriv_dislikes_value', 'ajax_dislike_posts');
add_action('wp_ajax_dislikes_value', 'ajax_dislike_posts');

 // enqueue js scipts to ajax
function post_enqueue_scripts()
{
    if (is_single()) {
        wp_enqueue_script(
            'like-dislike-scripts',
            plugin_dir_url(__FILE__) . 'js/scripts.js',
            ['jquery'],
            false,
            true,
        );
        wp_localize_script(
            'like-dislike-scripts',
            'like_post',
            [
                'nonce' => wp_create_nonce('likes_value'),
                'ajax_url' => admin_url('admin-ajax.php'),
                'thank_you' => __('Thank you !', 'post_like'),
                'wait' => __('Please wait ...', 'post_like'),
                'like' => __(' <i class="far fa-thumbs-up" style="font-size:22px"></i> Like', 
                'post_like'),
                'dislike' => __('<i class="far fa-thumbs-down" style="font-size:22px"></i> Dislikes ', 'post_like'),
            ]

        );
    }
}
add_action('wp_enqueue_scripts', 'post_enqueue_scripts');

// post like metabox  -> allow to admin add or not in his post

function like_dislike_post_mb()
{

    add_meta_box(
        'post_like_dislike_mb',
        __('Post Like or Dislike', 'post_like'),
        'post_like_dislike_metabox',
        ['post', 'page', 'product'],
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'like_dislike_post_mb');

      //add meta box
function post_like_dislike_metabox( WP_Post $post)
{   echo 'Likes  '. the_post_likes($post->ID) .'</br>';
    echo 'Dislikes  '.the_post_dislikes($post->ID).'</br>';
    $value = update_post_meta($post->ID,'_allow_liking',true);
    $checked = $value ? 'checked' :'';
    printf( '
    <div class="components-panel__row">
    <div class="components-base-control components-checkbox-control css-wdf2ti-Wrapper e1puf3u0">
     <input type="checkbox" value="1" %s id ="nspector-checkbox-control-7" name="post_allow_liking">
     <label class="components-checkbox-control__label" for="inspector-checkbox-control-7">
     Allow Like Or Dislike Post</label>',$checked);
 
}
//   save meta box value
function allow_like_dislike_post_save($post_id,$post,$update){
    $value = isset($_POST['post_allow_liking']) ? 1: 0;
    update_post_meta($post_id,'_allow_liking',$value);

}
add_action('save_post','allow_like_dislike_post_save',10,3);


<!-- Like form  -->
<form action="<?= admin_url('admin-post.php') ?>" method="post" style="display: flex;" id="like-form" >
    <input type="hidden" name="action" value="likes_value">
    <input type="hidden" name="post_id" value="<?= $post_id ?>">
    <input type="hidden" name="nonce" value="<?= wp_create_nonce('likes_value')?>">

    <input type="hidden"  name="like" value="like">
    
    <button type="submit" style='background:none;border:none'> <i class='far fa-thumbs-up' style="font-size:22px"></i>like</button>
</form>
     
<!-- Dislike form  -->

<form action="<?= admin_url('admin-post.php') ?>" method="post" style="display:flex;" id="dislike-form">
    <input type="hidden" name="action" value="dislikes_value">
    <input type="hidden" name="post_id" value="<?= $post_id ?>">
    <input type="hidden" name="nonce" value="<?= wp_create_nonce('dislikes_value')?>">

    <input type="hidden" name="dislike" value="dislike">

    <button type="submit" style='background:none;border:none'><i class='far fa-thumbs-down' style="font-size:22px"></i>Dislike</button>

</form> 

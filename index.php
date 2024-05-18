<?php get_header(); ?>
<div class="row">
    <div class="col-md-<?php jays_cake_main_content_width_columns(); ?>">
        <?php do_action('jays_cake_generate_the_content'); ?>
    </div>
    <?php get_sidebar('right'); ?>		
</div>
<?php 
get_footer();

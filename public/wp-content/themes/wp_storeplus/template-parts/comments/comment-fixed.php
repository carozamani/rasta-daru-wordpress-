<!-- Comments fixed -->
<section id="comments-fixed-container">
    <div id="comments-fixed">
        <!-- Title of comments -->
        <div class="comments-title">
            <h4>بخش نظرات (<?php echo get_comments_number(); ?>)</h4>
            <!-- Close comments fixed -->
            <button id="close-comments-fixed" class="button-no-style"><i class="fa-light fa-times"></i></button>
        </div>
        <?php
        // If comments are open or we have at least one comment, load up the comment template.
        if (comments_open() || get_comments_number()) :
            comments_template();
        endif;
        ?>
    </div>
</section>
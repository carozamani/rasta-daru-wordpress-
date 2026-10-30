<?php if (is_active_sidebar('irantheme_sidebar_template')) : ?>
  <div class="irantheme-sidebar-holder">
    <?php dynamic_sidebar('irantheme_sidebar_template'); ?>
  </div>
<?php else : ?>
  <?php _e('برای استفاده از سایدبار کافیست در منوی "نمایش > ابزارک ها" ابزار های مورد نیاز خود را اضافه نمایید.'); ?>
<?php endif ?>
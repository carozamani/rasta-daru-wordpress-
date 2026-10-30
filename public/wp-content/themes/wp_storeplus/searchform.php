<!-- Search form -->
<form class="irantheme-navigation-search-form" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
  <!-- Search icon & spinner -->
  <span class="irantheme-navigation-search-case irantheme-navigation-search-case-alt">
    <i class="fa-light fa-magnifying-glass"></i>
  </span>
  <!-- Search input -->
  <input type="text" class="irantheme-navigation-search-input" value="<?php echo get_search_query(); ?>" autocomplete="off" name="s" id="s" placeholder="<?php _e('به دنبال چیزی هستید؟'); ?>">
</form>
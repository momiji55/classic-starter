<?php
/**
 * Search form template
 */
?>

<form role="search" class="search-form" method="get" action="<?php echo esc_url(home_url('/')); ?>">
  <input name="s" class="search-form-input" type="search" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="<?php echo esc_attr__('キーワードで検索', 'textdomain'); ?>" aria-label="<?php echo esc_attr__('検索キーワード', 'textdomain'); ?>">

  <button class="search-form-submit" type="submit" aria-label="<?php echo esc_attr__('検索', 'textdomain'); ?>">
    <svg width="20" height="20" viewBox="0 0 20 20" aria-hidden="true" focusable="false">
      <circle cx="9" cy="9" r="5.5" fill="none" stroke="currentColor" stroke-width="1.2"></circle>
      <line x1="13.2" y1="13.2" x2="17.2" y2="17.2" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"></line>
    </svg>
  </button>
</form>

<?php
/**
 * Sidebar（ウィジェットエリア）
 *
 * ウィジェットが無い時は何も出力せず、レイアウトは1カラムのままにする。
 *
 * @package classic-starter
 */

if (!is_active_sidebar('sidebar-1')) {
  return;
}
?>

<aside class="sidebar" aria-label="<?php echo esc_attr__('Sidebar', 'classic-starter'); ?>">
  <?php dynamic_sidebar('sidebar-1'); ?>
</aside>

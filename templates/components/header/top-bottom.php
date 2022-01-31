<?php
if (!defined('ABSPATH')) {
	return;
}
?>

<div class="container-fluid nav-top nav-top-custom" id="top-nav-menu-nav">
    <div class="nav-top-container">
		<?php
		wp_nav_menu(
			[
				'theme_location'  => 'top-navigation',
				'menu'            => 'top-navigation',
				'container'       => 'div',
				'container_class' => 'top-navigation top-navigation-container navigation-container',
				'menu_class'      => 'top-navigation-menu navigation-menu',
				'echo'            => true,
				'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
				'item_spacing'    => 'preserve',
				'depth'           => 1,
			]
		);
		?>
    </div>
</div>

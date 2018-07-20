<?php

  // это пример кода к статье Quick Start with REES46 for backenders http://bit.ly/rees46-quick-start

  // пример подготовки начальных данных
  // в реальном проекте используйте функции вашей CMS
  $rees46_variables = [
    'product_id'     => 123,
    'stock_status'   => true,
    'category_id'    => 456,
    'cart_ext_array' => [],
    'search_query'   => '...',
    'products_array' => [],
    'order_id'       => '...',
    'order_price'    => '...',
    'page_type'      => 'product'
  ];

  // имитируем содержимое корзины (в этом примере два товара)
  // в реальном проекте используйте функции вашей CMS
  $cart_ext_array = [];
  $cart_ext_array[] = [
    'id'     => 37,
    'amount' => 4,
    'stock'  => true
  ];
  $cart_ext_array[] = [
      'id'     => 56,
      'amount' => 2,
      'stock'  => true
  ];

  // проверяем что корзина изменилась
  $cart_hash = md5(json_encode($cart_ext_array));
  if ($_COOKIE['rees46_cart_ext_array'] !== $cart_hash) {
    setcookie('rees46_cart_ext_array', $cart_hash, time()+86400, '/');
    $rees46_variables['cart_ext_array'] = $cart_ext_array;
  } else {
    $rees46_variables['cart_ext_array'] = [];
  }

  // собираем data layer
  $datalayer = [
    'item' => [
      'id'           => $rees46_variables['product_id'],
      'stock'        => $rees46_variables['stock_status']
    ],
    'category_id'    => $rees46_variables['category_id'],
    'cart_ext_array' => $rees46_variables['cart_ext_array'],
    'search_query'   => $rees46_variables['search_query'],
    'purchase' => [
      'products'     => $rees46_variables['products_array'],
      'order'        => $rees46_variables['order_id'],
      'order_price'  => $rees46_variables['order_price']
    ]
  ];

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>

<script>
<!-- инициализируем подключение к REES46 -->
(function (r) {
    window.r46 = window.r46 || function () {
        (r46.q = r46.q || []).push(arguments)
    };
    var s = document.getElementsByTagName(r)[0], rs = document.createElement(r);
    rs.async = 1;
    rs.src = '//cdn.rees46.com/v3.js';
    s.parentNode.insertBefore(rs, s);
})('script');
<!-- возьмите код магазина из личного кабинета app.rees46.com -->
r46('init', 'dc8e10ad1b82f6c9c2a8ee2206fc5a');
</script>

<script>
<!-- пробросим содержимое data layer на фронтенд -->
var _r46_datalayer=<?php echo(json_encode($datalayer)); ?>;
</script>

<script>
<?php

// отправляем содержимое корзины в REES46
if (count($rees46_variables['cart_ext_array']) > 0) {
  ?>
  r46('track', 'cart', _r46_datalayer.cart_ext_array);
  <?php
}

// отправляем различные данные в REES46 в зависимости от типа страницы
switch ($rees46_variables['page_type']) {
  case 'product':
    ?>
    r46('track', 'view',     _r46_datalayer.item);
    <?php
    break;
  case 'category':
    ?>
    r46('track', 'category', _r46_datalayer.category_id);
    <?php
    break;
  case 'purchase':
    ?>
    r46('track', 'purchase', _r46_datalayer.purchase);
    <?php
    break;
  case 'search':
    ?>
    r46('track', 'search',   _r46_datalayer.search_query);
    <?php
    break;
}

?>
</script>

</head>

<body <?php body_class(); ?>>

<?php do_action( 'storefront_before_site' ); ?>

<div id="page" class="hfeed site">
	<?php do_action( 'storefront_before_header' ); ?>

	<header id="masthead" class="site-header" role="banner" style="<?php storefront_header_styles(); ?>">
		<div class="col-full">

			<?php
			/**
			 * Functions hooked into storefront_header action
			 *
			 * @hooked storefront_skip_links                       - 0
			 * @hooked storefront_social_icons                     - 10
			 * @hooked storefront_site_branding                    - 20
			 * @hooked storefront_secondary_navigation             - 30
			 * @hooked storefront_product_search                   - 40
			 * @hooked storefront_primary_navigation_wrapper       - 42
			 * @hooked storefront_primary_navigation               - 50
			 * @hooked storefront_header_cart                      - 60
			 * @hooked storefront_primary_navigation_wrapper_close - 68
			 */
			do_action( 'storefront_header' ); ?>

		</div>
	</header><!-- #masthead -->

	<?php
	/**
	 * Functions hooked in to storefront_before_content
	 *
	 * @hooked storefront_header_widget_region - 10
	 */
	do_action( 'storefront_before_content' ); ?>

	<div id="content" class="site-content" tabindex="-1">
		<div class="col-full">

		<?php
		/**
		 * Functions hooked in to storefront_content_top
		 *
		 * @hooked woocommerce_breadcrumb - 10
		 */
		do_action( 'storefront_content_top' );

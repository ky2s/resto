<?php
header('Content-Type: application/json');
$site = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
$site_name = rawurldecode($_GET['name']);
$site_theme = rawurldecode($_GET['theme']);
$site_bg = rawurldecode($_GET['bg']);
$site_icon = (!empty($_GET['icon']) ? rawurldecode($_GET['icon']) : preg_replace('{/$}', '', $site) . '/img/icon-512.png');
?>
{
  "name": "<?=$site_name?>",
  "short_name": "<?=$site_name?>",
  "start_url": "/",
  "display": "standalone",
  "theme_color": "<?=($site_theme !== '' ? $site_theme : '#fff')?>",
  "background_color": "<?=($site_bg !== '' ? $site_bg : '#fff')?>",
  "icons": [{
    "src": "<?=$site_icon?>",
    "sizes": "192x192",
    "type": "image/png"
  },{
    "src": "<?=$site_icon?>",
    "sizes": "512x512",
    "type": "image/png"
  }],
  "related_applications": [{
    "platform": "webapp",
    "url": "<?=(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") .'://'. $_SERVER['HTTP_HOST'] . explode('?', $_SERVER['REQUEST_URI'], 2)[0];?>"
  }]
}
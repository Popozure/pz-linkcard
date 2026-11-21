<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

$screen = get_current_screen();
if (!$screen) { return; }
$columns = array(
	'cb' => '<input type="checkbox">',
	'id' => __('ID', 'pz-linkcard'),
	'title' => __('URL', 'pz-linkcard').' / '.__('Title', 'pz-linkcard'),
	'excerpt' => __('Excerpt', 'pz-linkcard'),
	'post_id' => __('Post ID', 'pz-linkcard'),
	'post_date' => __('Post Date', 'pz-linkcard'),
	'post_modified' => __('Post Modified Date', 'pz-linkcard'),
	'charset' => __('Charset', 'pz-linkcard'),
	'domain' => __('Domain', 'pz-linkcard'),
	'sns' => __('SNS', 'pz-linkcard'),
	'regist_time' => __('Registered Date', 'pz-linkcard'),
	'update_time' => __('Update Date', 'pz-linkcard'),
	'sns_time' => __('SNS Check Date', 'pz-linkcard'),
	'sns_nexttime' => __('Next Share Count Retrieval Date', 'pz-linkcard'),
	'alive_time' => __('Alive Check Date', 'pz-linkcard'),
	'alive_nexttime' => __('Next Status Check Date', 'pz-linkcard'),
	'use_post_id1' => __('Published Post ID', 'pz-linkcard'),
	'click_count' => __('Click Count', 'pz-linkcard'),
	'update_result' => __('Result code', 'pz-linkcard'),
);
if (empty($this->options['debug-mode'])) { unset($columns['sns_nexttime'], $columns['alive_nexttime']); }
add_filter('manage_'.$screen->id.'_columns', function () use ($columns) { return $columns; });
add_filter('default_hidden_columns', function ($hidden, $current_screen) {
	if ($current_screen->id !== 'tools_page_pz-linkcard-cacheman') { return $hidden; }
	$hidden = array('post_id', 'post_date', 'post_modified', 'charset', 'sns_time', 'sns_nexttime', 'alive_time', 'alive_nexttime');
	$saved = get_user_meta(get_current_user_id(), 'pz_lkc_cacheman_columns', true);
	if (is_array($saved)) {
		foreach ($saved as $key => $visible) {
			$key = $key === 'use_post_id' ? 'use_post_id1' : ($key === 'result' ? 'update_result' : $key);
			$hidden = array_diff($hidden, array($key));
			if (!$visible) { $hidden[] = $key; }
		}
	}
	return array_values(array_unique($hidden));
}, 10, 2);
add_screen_option('per_page', array('default' => 10, 'option' => 'pz_linkcard_cards_per_page'));
ob_start();
require __DIR__.'/pz-linkcard-cacheman-help.php';
$help = ob_get_clean();
$screen->add_help_tab(array('id' => 'pz-linkcard-search-help', 'title' => __('Search', 'pz-linkcard').__('Help', 'pz-linkcard'), 'content' => $help));

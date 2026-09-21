<?php defined('ABSPATH' ) || wp_die; ?>
<div class="pz-page<?php echo $pz_page_active('pz-basic' ); ?>" id="pz-basic">
	<div class="pz-submit-float"><?php submit_button(); ?></div>

	<div class="pz-tips" style="
		display: none;
		margin: 0 -8px 0 -8px;
		padding: 4px 2px 2px 4px;
		width: 100%;
		border: 1px solid #000;
		border-radius: 4px;
		box-shadow: inset 4px 4px 4px rgba(0,0,0,0.5);
		background-color: #eff;
		color: #444;">
	</div>

	<table class="form-table">
	<h2><?php echo	__('Basic Settings', 'pz-linkcard' ).$help_open.'basic'.$help_close; ?></h2>
<?php
	// 簡単書式設定
	$item_name		=	'special-format';
	$item_list		=	array(
		''			=>		__('None',							'pz-linkcard'),
		'LkC'		=>		__('Pz-LkC Default',				'pz-linkcard'),
		'hbc'		=>		__('Normal',						'pz-linkcard'),
		'cmp'		=>		__('Compact',						'pz-linkcard'),
		'smp'		=>		__('Simple',						'pz-linkcard'),
		'JIN'		=>		__('Headline',						'pz-linkcard'),
		'ct1'		=>		__('Cellophane tape "center"',		'pz-linkcard'),
		'ct2'		=>		__('Cellophane tape "Top corner"',	'pz-linkcard'),
		'ct3'		=>		__('Cellophane tape "long"',		'pz-linkcard'),
		'ct4'		=>		__('Cellophane tape "diagonal"',		'pz-linkcard'),
		'tac'		=>		__('Cellophane tape and curling',	'pz-linkcard'),
		'ppc'		=>		__('Curling paper',					'pz-linkcard'),
		'sBR'		=>		__('Stitch blue & red',				'pz-linkcard'),
		'sGY'		=>		__('Stitch green & yellow',			'pz-linkcard'),
		'sqr'		=>		__('Square',						'pz-linkcard'),
		'ecl'		=>		__('Enclose',						'pz-linkcard'),
		'ref'		=>		__('Reflection',					'pz-linkcard'),
		'inI'		=>		__('Information orange',				'pz-linkcard'),
		'inN'		=>		__('Neutral bluegreen',				'pz-linkcard'),
		'inE'		=>		__('Enlightened green',				'pz-linkcard'),
		'inR'		=>		__('Resistance blue',				'pz-linkcard'),
		'wxp'		=>		__('Windows XP',					'pz-linkcard'),
		'w95'		=>		__('Windows 95',					'pz-linkcard'),
		'slt'		=>		__('Slanting',						'pz-linkcard'),
		'3Dr'		=>		__('3D Rotate',						'pz-linkcard'),
		'pin'		=>		__('Pushpin',						'pz-linkcard'),
	);
	$item_descript		=	__('Easy Format',					'pz-linkcard' );
	$item_notice		=	__('*', 'pz-linkcard' ).' '.__('It applies over other formatting settings.', 'pz-linkcard' );
	echo_list($item_name, $prop[$item_name], $item_list, $item_descript, $item_notice );
?>
		<tr>
			<th scope="row"><?php esc_html_e('Saved Datetime', 'pz-linkcard' ); ?></th>
			<td>
				<input name="properties[saved-date]" type="number" min="0" step="1" value="<?php echo esc_attr($this->options['saved-date'] ); ?>" class="pz-admin-only" readonly="readonly" />
				<?php echo is_numeric($this->options['saved-date'] ) ? esc_html($this->pz_Date(PZLKC_DATETIME_FORMAT, $this->options['saved-date'] ) ) : esc_html($this->options['saved-date'] ); ?>
			</td>
		</tr>
	</table>
	<?php submit_button(); ?>

	<h2><?php echo	__('Changelog', 'pz-linkcard' ); ?></h2>
	<div class="pz-changelog">
		<?php echo	$changelog; ?>
	</div>
	<?php submit_button(); ?>

	<h2><?php echo	__('Related Information', 'pz-linkcard' ); ?></h2>
	<table class="form-table">
		<tr>
			<th scope="row"><?php echo	__('How to', 'pz-linkcard' ).' '.__('(', 'pz-linkcard' ).__('Japanese Only', 'pz-linkcard' ).__(')', 'pz-linkcard' ); ?></th>
			<td>
				<p><?php echo	self::PLUGIN_NAME.' Ver.'.PZLKC_PLUGIN_VERSION; ?></p>
				<p><a href="<?php echo	esc_attr($plugin_url ); ?>" rel="external noopener" target="_blank"><?php echo	esc_attr($plugin_url ); ?></a></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e("Author's Site", 'pz-linkcard' ); ?></th>
			<td><?php echo	__('Popozure.', 'pz-linkcard' ).' ('.__("Poporon's PC Daily Diary", 'pz-linkcard' ).')'; ?><BR><a href="<?php echo $pz_url; ?>" rel="external noopener" target="_blank"><?php echo $pz_url; ?></A></td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('When in Trouble', 'pz-linkcard' ); ?></th>
			<td><?php echo	__('X Account', 'pz-linkcard' ); ?><BR><a href="<?php echo self::AUTHOR_TWITTER_URL; ?>" rel="external noopener" target="_blank"><?php echo self::AUTHOR_TWITTER; ?></A></td>
		</tr>

		<tr class="pz-debug-only">
			<th scope="row"><?php esc_html_e('Donation', 'pz-linkcard' ); ?></th>
			<td><a href="<?php echo self::AUTHOR_DONATE_URL; ?>" rel="external noopenner noreferrer" target="_blank" target="_blank"><?php esc_html_e('Wishlist', 'pz-linkcard' ); ?></a></td>
		</tr>

	</table>
	<?php submit_button(); ?>
</div>

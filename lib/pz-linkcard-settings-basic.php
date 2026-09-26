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
		'ct4'		=>		__('Cellophane tape "diagonal"',	'pz-linkcard'),
		'tac'		=>		__('Cellophane tape and curling',	'pz-linkcard'),
		'ppc'		=>		__('Curling paper',					'pz-linkcard'),
		'sBR'		=>		__('Stitch blue & red',				'pz-linkcard'),
		'sGY'		=>		__('Stitch green & yellow',			'pz-linkcard'),
		'sqr'		=>		__('Square',						'pz-linkcard'),
		'ecl'		=>		__('Enclose',						'pz-linkcard'),
		'ref'		=>		__('Reflection',					'pz-linkcard'),
		'inI'		=>		__('Information orange',			'pz-linkcard'),
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
<?php
	$plugin_support_url	=	'https://wordpress.org/support/plugin/pz-linkcard/';
	$poporon_x_url		=	'https://x.com/popo68k';
	$intro_card		=	static function($link, $icon, $name, $description, $class, $dashicon = '' ) {
		echo	'<div class="pz-introduction-base"><a href="'.esc_url($link ).'" rel="external noopener noreferrer" target="_blank" class="pz-introduction-card '.esc_attr($class ).'"><div class="pz-introduction-thumb">';
		if	($dashicon ) {
			echo	'<div class="dashicons '.esc_attr($dashicon ).' pz-introduction-dashicon"></div>';
		} else {
			echo	'<img src="'.esc_url($icon ).'" alt="'.esc_attr($name ).'" />';
		}
		echo	'</div><div class="pz-introduction-content"><div class="pz-introduction-title">'.esc_html($name ).'</div><div class="pz-introduction-description">'.esc_html($description ).'</div></div></a></div>';
	};
?>
		<tr>
			<th scope="row"><?php echo	__('How to', 'pz-linkcard' ).' '.__('(', 'pz-linkcard' ).__('Japanese Only', 'pz-linkcard' ).__(')', 'pz-linkcard' ); ?></th>
			<td>
				<?php $intro_card($plugin_url, $this->plugin_dir_url.'img/logo_pz-linkcard.png', self::PLUGIN_NAME, 'Version '.PZLKC_PLUGIN_VERSION, 'pz-introduction-pzlkc' ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row" rowspan="3"><?php esc_html_e('When in Trouble', 'pz-linkcard' ); ?></th>
			<td>
				<?php $intro_card($plugin_support_url, '', __('Pz-LinkCard Forum', 'pz-linkcard' ), __('This is a forum for Pz-LinkCard by the official WordPress.org website.', 'pz-linkcard' ), 'pz-introduction-wporg', 'dashicons-wordpress' ); ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php $intro_card(self::AUTHOR_TWITTER_URL, $this->plugin_dir_url.'img/icon_x.png', __('Popozure.', 'pz-linkcard' ).' ('.self::AUTHOR_TWITTER.')', __('If you find any problems, please let us know via direct message.', 'pz-linkcard' ), 'pz-introduction-twitter' ); ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php $intro_card($poporon_x_url, $this->plugin_dir_url.'img/icon_x.png', __('Poporon@Popozure.', 'pz-linkcard' ).' (@popo68k)', __("It's okay here too.", 'pz-linkcard' ), 'pz-introduction-twitter' ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e("Author's Site", 'pz-linkcard' ); ?></th>
			<td>
				<?php $intro_card($pz_url, $this->plugin_dir_url.'img/popozure_large.png', __('Popozure.', 'pz-linkcard' ), __("Poporon's PC Daily Diary", 'pz-linkcard' ), 'pz-introduction-popozure' ); ?>
			</td>
		</tr>

		<tr>
			<th scope="row"><?php esc_html_e('Donation', 'pz-linkcard' ); ?></th>
			<td>
				<?php $intro_card(self::AUTHOR_DONATE_URL, $this->plugin_dir_url.'img/icon_amazon.png', __('Wishlist', 'pz-linkcard' ), __('You do not have to send me a gift, but if you make your own purchases through this link, I will receive a little extra money. That helps keep me motivated.', 'pz-linkcard' ), 'pz-introduction-amazon' ); ?>
			</td>
		</tr>

	</table>
	<?php submit_button(); ?>
</div>

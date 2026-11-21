<?php if ( ! defined( 'ABSPATH' ) ) { exit; } $search_help_label = __('Search', 'pz-linkcard').__('Help', 'pz-linkcard'); $search_help_date = current_time('Y-m-d'); ?>
<h3><?php echo esc_html($search_help_label ); ?></h3>
				<dl class="pz-man-search-command-list">
					<div><dt><code>id:123</code></dt><dd><?php echo esc_html(__('Pz Card', 'pz-linkcard' ).' '.__('ID', 'pz-linkcard' ) ); ?></dd></div>
					<div><dt><code>post:123</code></dt><dd><?php echo esc_html(__('Post ID', 'pz-linkcard' ) ); ?></dd></div>
					<div><dt><code>title:keyword</code></dt><dd><?php echo esc_html(__('Title', 'pz-linkcard' ) ); ?></dd></div>
					<div><dt><code>excerpt:keyword</code></dt><dd><?php echo esc_html(__('Excerpt', 'pz-linkcard' ) ); ?></dd></div>
					<div><dt><code>url:example.com</code></dt><dd><?php echo esc_html(__('URL', 'pz-linkcard' ) ); ?></dd></div>
					<div><dt><code>domain:example.com</code></dt><dd><?php echo esc_html(__('Domain', 'pz-linkcard' ) ); ?></dd></div>
					<div><dt><code>sitename:keyword</code></dt><dd><?php echo esc_html(__('Site Name', 'pz-linkcard' ) ); ?></dd></div>
					<div><dt><code>charset:utf-8</code></dt><dd><?php echo esc_html(__('Character Set', 'pz-linkcard' ) ); ?></dd></div>
					<div><dt><code>click:&gt;=10</code></dt><dd><?php echo esc_html(__('Click Count', 'pz-linkcard' ) ); ?></dd></div>
					<div><dt><code>regist:<?php echo esc_html($search_help_date ); ?></code></dt><dd><?php echo esc_html(__('Registered Date', 'pz-linkcard' ) ); ?></dd></div>
					<div><dt><code>update:<?php echo esc_html($search_help_date ); ?></code></dt><dd><?php echo esc_html(__('Update Date', 'pz-linkcard' ) ); ?></dd></div>
					<div><dt><code>sns_nexttime&lt;=now</code></dt><dd><?php echo esc_html(__('Next Share Count Retrieval Date', 'pz-linkcard')); ?></dd></div>
					<div><dt><code>alive_nexttime&lt;=now</code></dt><dd><?php echo esc_html(__('Next Status Check Date', 'pz-linkcard')); ?></dd></div>
					<div><dt><code>result:200</code></dt><dd><?php echo esc_html(__('Result Code', 'pz-linkcard' ) ); ?></dd></div>
					<div class="pz-man-search-operators">
						<dt class="screen-reader-text"><?php echo esc_html__('Comparison operators', 'pz-linkcard' ); ?></dt>
						<dd>
							<span><code>:</code> <?php echo esc_html__('Equal', 'pz-linkcard' ); ?></span>
							<span><code>:&lt;</code> <?php echo esc_html__('Less than', 'pz-linkcard' ); ?></span>
							<span><code>:&lt;=</code> <?php echo esc_html__('Less than or equal to', 'pz-linkcard' ); ?></span>
							<span><code>:&gt;</code> <?php echo esc_html__('Greater than', 'pz-linkcard' ); ?></span>
							<span><code>:&gt;=</code> <?php echo esc_html__('Greater than or equal to', 'pz-linkcard' ); ?></span>
							<span><code>:&lt;&gt;</code> <?php echo esc_html__('Not equal to', 'pz-linkcard' ); ?></span>
							<small><?php echo esc_html__('The colon before a comparison operator can be omitted.', 'pz-linkcard' ); ?></small>
						</dd>
					</div>
			</dl>
			<h3 class="pz-man-shortcut-title"><?php echo esc_html(__('Keyboard Shortcuts' , 'default') ); ?></h3>
			<dl class="pz-man-shortcut-list">
				<div><dt><kbd>F1</kbd></dt><dd><?php echo esc_html__('Show Help', 'pz-linkcard' ); ?></dd></div>
				<div><dt><kbd>F2</kbd></dt><dd><?php echo esc_html__('Show Screen Options', 'pz-linkcard' ); ?></dd></div>
				<div><dt><kbd>F3</kbd></dt><dd><?php echo esc_html(__('Search cards', 'pz-linkcard' ) ); ?></dd></div>
				<div><dt><kbd>F4</kbd></dt><dd><?php echo esc_html__('Switch between Card Manager and Settings', 'pz-linkcard' ); ?></dd></div>
				<div><dt><kbd>Ctrl</kbd>+<kbd>A</kbd></dt><dd><?php echo esc_html(__('Select All' , 'default') ); ?></dd></div>
				<div><dt><kbd>Ctrl</kbd>+<kbd>F</kbd> / <kbd>Alt</kbd>+<kbd>Q</kbd></dt><dd><?php echo esc_html(__('Search', 'pz-linkcard' ) ); ?></dd></div>
				<div><dt><kbd>Ctrl</kbd>+<kbd>;</kbd></dt><dd><?php echo esc_html(__('Today' , 'default').' (yyyy-mm-dd)' ); ?></dd></div>
				<div><dt><kbd>Ctrl</kbd>+<kbd>←</kbd> / <kbd>Ctrl</kbd>+<kbd>→</kbd></dt><dd><?php echo esc_html(__('Previous page' , 'default').' / '.__('Next page' , 'default') ); ?></dd></div>
			</dl>
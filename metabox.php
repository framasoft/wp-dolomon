<?php
// vim:set ft=php noexpandtab:
wp_nonce_field( 'dolomon_meta_box_nonce', 'dolomon_meta_box_nonce' );
?>
<p>
	<label for="dolomon-url"><?php _e( 'URL', 'dolomon' ) ?></label><br>
	<input id="dolomon-url" name="dolomon-url" type="url" class="widefat" placeholder="https://example.org <?php _e( 'mandatory' ) ?>">
</p>
<p>
	<label for="dolomon-name"><?php _e( 'Name', 'dolomon' ) ?></label><br>
	<input id="dolomon-name" name="dolomon-name" type="text" class="widefat" placeholder="<?php _e( 'optional' ) ?>">
</p>
<p>
	<label for="dolomon-extra"><?php _e( 'Extra', 'dolomon' ) ?></label><br>
	<input id="dolomon-extra" name="dolomon-extra" type="text" class="widefat" placeholder="<?php _e( 'optional' ) ?>">
</p>
<p>
	<label for="dolomon-short"><?php _e( 'Short', 'dolomon' ) ?></label><br>
	<input id="dolomon-short" name="dolomon-short" type="text" class="widefat" placeholder="<?php _e( 'optional' ) ?>">
</p>
<p>
	<label for="dolomon-cat"><?php _e( 'Category', 'dolomon' ) ?></label>
	<small>
		(<a href="#" id="showadddolocat"><?php _e( 'Add a category', 'dolomon' ) ?></a>)
	</small><br>
	<select id="dolomon-cat" name="dolomon-cat" class="widefat">
		<?php foreach ( $dolo_cache['cats'] as $cat ) { ?>
			<option value="<?php echo $cat['id'] ?>"><?php echo $cat['name'] ?></option>
		<?php } ?>
	</select>
</p>
<p>
	<label for="dolomon-tag"><?php _e( 'Tag', 'dolomon' ) ?></label>
	<small>
		(<a href="#" id="showadddolotag"><?php _e( 'Add a tag', 'dolomon' ) ?></a>)
	</small><br>
	<select id="dolomon-tag" name="dolomon-tag" multiple class="widefat">
		<?php foreach ( $dolo_cache['tags'] as $tag ) { ?>
			<option value="<?php echo $tag['id'] ?>"><?php echo $tag['name'] ?></option>
		<?php } ?>
	</select>
</p>
<p>
	<a href="#" id="dolomon-submit" class="button"><?php _e( 'Create a dolo', 'dolomon' ) ?></a>
</p>
<p>
	<a href="#" id="showdolotb"><?php _e( 'Show my dolos', 'dolomon' ) ?></a>
</p>
<div id="addDoloCat" style="display:none;">
	<h2><?php _e( 'Add a category', 'dolomon' ) ?></h2>
	<p>
		<label for="dolomon-cat-name"><?php _e( 'Name', 'dolomon' ) ?></label><br>
		<input id="dolomon-cat-name" name="dolomon-cat-name" type="text">
	</p>
	<p>
		<a href="#" id="dolomon-cat-submit" class="button"><?php _e( 'Create a category', 'dolomon' ) ?></a>
	</p>
</div>
<div id="addDoloTag" style="display:none;">
	<h2><?php _e( 'Add a tag', 'dolomon' ) ?></h2>
	<p>
		<label for="dolomon-tag-name"><?php _e( 'Name', 'dolomon' ) ?></label><br>
		<input id="dolomon-tag-name" name="dolomon-tag-name" type="text">
	</p>
	<p>
		<a href="#" id="dolomon-tag-submit" class="button"><?php _e( 'Create a tag', 'dolomon' ) ?></a>
	</p>
</div>
<div id="myDolos" style="display:none;">
	<div id="tabs-container">
		<ul class="tabs-menu">
			<li class="current"><a href="#tab-1"><?php _e( 'My dolos', 'dolomon' ) ?></a></li>
			<li><a href="#tab-2"><?php _e( 'My dolomon categories', 'dolomon' ) ?></a></li>
			<li><a href="#tab-3"><?php _e( 'My dolomon tags', 'dolomon' ) ?></a></li>
			<li><a href="#tab-4"><?php _e( 'Shortcodes help', 'dolomon' ) ?></a></li>
		</ul>
		<div class="tab">
			<div class="tab-content sc-builder">
				<h3><?php _e( 'shortcode builder', 'dolomon' ) ?></h3>
				<span>
					<label for="dolo-sc-name">name</label>
					<input id="dolo-sc-name" type="text" placeholder="%name (%count)">
				</span>
				<span>
					<label for="dolo-sc-count">count</label>
					<input id="dolo-sc-count" type="checkbox">
				</span>
				<span>
					<label for="dolo-sc-extra">extra</label>
					<input id="dolo-sc-extra" type="checkbox">
				</span>
				<span>
					<label for="dolo-sc-link">link</label>
					<input id="dolo-sc-link" type="checkbox">
				</span>
				<span class="hidden">
					<label for="dolo-sc-button">button</label>
					<input id="dolo-sc-button" type="checkbox">
				</span>
				<span class="hidden">
					<label for="dolo-sc-self">self</label>
					<input id="dolo-sc-self" type="checkbox">
				</span>
				<span class="hidden">
					<label for="dolo-sc-page">page</label>
					<input id="dolo-sc-page" type="checkbox">
				</span>
				<span class="hidden">
					<label for="dolo-sc-cat"><?php _e( 'Categories', 'dolomon' ) ?></label>
					<select id="dolo-sc-cat" name="dolomon-cat" multiple>
					<?php foreach ( $dolo_cache['cats'] as $cat ) { ?>
						<option value="<?php echo $cat['id'] ?>"><?php echo $cat['name'] ?></option>
					<?php } ?>
					</select>
				</span>
				<span class="hidden">
					<label for="dolo-sc-tag"><?php _e( 'Tags', 'dolomon' ) ?></label>
					<select id="dolo-sc-tag" name="dolomon-tag" multiple>
					<?php foreach ( $dolo_cache['tags'] as $tag ) { ?>
						<option value="<?php echo $tag['id'] ?>"><?php echo $tag['name'] ?></option>
					<?php } ?>
					</select>
				</span>
				<p><?php _e( 'click on a link to copy it to your clipboard', 'dolomon' ) ?>
			</div>
			<div id="tab-1" class="tab-content current" data-selected="dolo">
				<table>
					<thead>
						<tr>
							<th><?php _e( 'Category', 'dolomon' ) ?></th>
							<th><?php _e( 'URL', 'dolomon' ) ?></th>
							<th><?php _e( 'Dolomon URL', 'dolomon' ) ?></th>
							<th><?php _e( 'Name', 'dolomon' ) ?></th>
							<th><?php _e( 'Extra', 'dolomon' ) ?></th>
							<th><?php _e( 'Shortcode', 'dolomon' ) ?></th>
							<th><?php _e( 'Tags', 'dolomon' ) ?></th>
						</tr>
						<tr>
							<td><input class="form-control dolo-filter" type="text" placeholder="<?php _e( 'filter', 'dolomon' ) ?>" data-filter=".dolo-filter-category"></td>
							<td><input class="form-control dolo-filter" type="text" placeholder="<?php _e( 'filter', 'dolomon' ) ?>" data-filter=".dolo-filter-url"></td>
							<td><input class="form-control dolo-filter" type="text" placeholder="<?php _e( 'filter', 'dolomon' ) ?>" data-filter=".dolo-filter-durl"></td>
							<td><input class="form-control dolo-filter" type="text" placeholder="<?php _e( 'filter', 'dolomon' ) ?>" data-filter=".dolo-filter-name"></td>
							<td><input class="form-control dolo-filter" type="text" placeholder="<?php _e( 'filter', 'dolomon' ) ?>" data-filter=".dolo-filter-extra"></td>
							<td><input class="form-control dolo-filter" type="text" placeholder="<?php _e( 'filter', 'dolomon' ) ?>" data-filter=".dolo-filter-shortcode"></td>
							<td><input class="form-control dolo-filter" type="text" placeholder="<?php _e( 'filter', 'dolomon' ) ?>" data-filter=".dolo-filter-tags"></td>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $dolo_cache['dolos'] as $dolo ) { ?>
							<tr>
								<td class="dolo-filter-category"><?php echo esc_attr( $dolo['category_name'] ) ?></td>
								<td class="dolo-filter-url"><?php echo $dolo['url'] ?></td>
								<td><a href="#" class="dolo-filter-durl" onclick="copyText('<?php echo $url . $dolo['short'] ?>')"><?php echo $url . $dolo['short'] ?></a></td>
								<td class="dolo-filter-name"><?php echo esc_attr( $dolo['name'] ) ?></td>
								<td class="dolo-filter-extra"><?php echo esc_attr( $dolo['extra'] ) ?></td>
								<td><a href="#" class="dolo-filter-shortcode" data-id="<?php echo $dolo['id'] ?>" onclick="copyText('[dolo id=<?php echo $dolo['id'] ?>]')">[dolo id=<?php echo $dolo['id'] ?>]</a></td>
								<td class="dolo-filter-tags">
									<?php echo implode( ', ', array_map( 'esc_attr', wp_list_pluck( $dolo['tags'], 'name' ) ) ); ?>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div id="tab-2" class="tab-content" data-selected="cat">
				<table>
					<thead>
						<tr>
							<th><?php _e( 'Name', 'dolomon' ) ?></th>
							<th><?php _e( 'Number of dolos', 'dolomon' ) ?></th>
							<th><?php _e( 'Shortcode', 'dolomon' ) ?></th>
						</tr>
						<tr>
							<td><input class="form-control dolo-filter" type="text" placeholder="<?php _e( 'filter', 'dolomon' ) ?>" data-filter=".dolo-filter-name"></td>
							<td><input class="form-control dolo-filter" type="text" placeholder="<?php _e( 'filter', 'dolomon' ) ?>" data-filter=".dolo-filter-number"></td>
							<td><input class="form-control dolo-filter" type="text" placeholder="<?php _e( 'filter', 'dolomon' ) ?>" data-filter=".dolo-filter-shortcode"></td>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $dolo_cache['cats'] as $cat ) { ?>
							<tr>
								<td class="dolo-filter-name"><?php echo esc_attr( $cat['name'] ) ?></td>
								<td class="dolo-filter-number"><?php echo $cat['dolos_count'] ?></td>
								<td><a href="#" class="dolo-filter-shortcode" data-id="<?php echo $cat['id'] ?>" onclick="copyText('[dolos cat=<?php echo $cat['id'] ?>]')">[dolos cat=<?php echo $cat['id'] ?>]</a></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div id="tab-3" class="tab-content" data-selected="tag">
				<table>
					<thead>
						<tr>
							<th><?php _e( 'Name', 'dolomon' ) ?></th>
							<th><?php _e( 'Number of dolos', 'dolomon' ) ?></th>
							<th><?php _e( 'Shortcode', 'dolomon' ) ?></th>
						</tr>
						<tr>
							<td><input class="form-control dolo-filter" type="text" placeholder="<?php _e( 'filter', 'dolomon' ) ?>" data-filter=".dolo-filter-name"></td>
							<td><input class="form-control dolo-filter" type="text" placeholder="<?php _e( 'filter', 'dolomon' ) ?>" data-filter=".dolo-filter-number"></td>
							<td><input class="form-control dolo-filter" type="text" placeholder="<?php _e( 'filter', 'dolomon' ) ?>" data-filter=".dolo-filter-shortcode"></td>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $dolo_cache['tags'] as $tag ) { ?>
							<tr>
								<td class="dolo-filter-name"><?php echo esc_attr( $tag['name'] ) ?></td>
								<td class="dolo-filter-number"><?php echo $tag['dolos_count'] ?></td>
								<td><a href="#" class="dolo-filter-shortcode" data-id="<?php echo $tag['id'] ?>" onclick="copyText('[dolos tag=<?php echo $tag['id'] ?>]')">[dolos tag=<?php echo $tag['id'] ?>]</a></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div id="tab-4" class="tab-content">
				<h2>[dolo]</h2>
				<?php _e( 'Show a single dolo, formatted with those parameters:', 'dolomon' ) ?>
				<ul>
					<li>
						<pre><code>id</code></pre> ➡ <pre><code>[dolo id=1]</code></pre> ➡ <pre><code>https://dolomon.example.org/hit/exemple</code></pre><br>
						<em><?php _e( 'mandatory', 'dolomon' ) ?></em><br>
						<?php _e( 'The only mandatory parameter, choose the dolo to show.', 'dolomon' ) ?>
					</li>
					<li>
						<pre><code>name</code></pre> ➡ <pre><code>[dolo id=1 name="%name (%count)"]</code></pre> ➡ <pre><code>My first dolo (42)</code></pre><br>
						<em><?php _e( 'optional', 'dolomon' ) ?></em><br>
						<?php _e( 'This format the rendered text with the text of your choice. %foo will be replaced like this:', 'dolomon' ) ?>
						<ul>
							<li>
								<pre><code>%name</code></pre>  ➡ <?php _e( 'the name of the dolo or its target URL (like https://example.org) if it\'s unnamed', 'dolomon' ) ?>
							</li>
							<li>
								<pre><code>%url</code></pre>   ➡ <?php _e( 'the target URL of the dolo', 'dolomon' ) ?>
							</li>
							<li>
								<pre><code>%count</code></pre> ➡ <?php _e( 'the visit counter of the dolo', 'dolomon' ) ?>
							</li>
							<li>
								<pre><code>%cat</code></pre>   ➡ <?php _e( 'the name of the category the dolo belongs to', 'dolomon' ) ?>
							</li>
							<li>
								<pre><code>%tags</code></pre>  ➡ <?php _e( 'the name of the dolo\'s tags, comma separated', 'dolomon' ) ?>
							</li>
							<li>
								<pre><code>%extra</code></pre> ➡ <?php _e( 'the content of the dolo\'s "extra" field', 'dolomon' ) ?>
							</li>
						</ul>
					</li>
					<li>
						<pre><code>count</code></pre> ➡ <pre><code>[dolo id=1 count=true]</code></pre> ➡ <pre><code>42</code></pre><br>
						<em><?php _e( 'optional', 'dolomon' ) ?></em><br>
						<?php _e( 'the visit counter of the dolo', 'dolomon' ) ?>
					</li>
					<li>
						<pre><code>extra</code></pre> ➡ <pre><code>[dolo id=1 extra=true]</code></pre> ➡ <pre><code>foo bar baz</code></pre><br>
						<em><?php _e( 'optional', 'dolomon' ) ?></em><br>
						<?php _e( 'the content of the dolo\'s "extra" field', 'dolomon' ) ?>
					</li>
					<li>
						<pre><code>link</code></pre> ➡ <pre><code>[dolo id=1 link=true]</code></pre> ➡ <pre><code>&lt;a href="https://dolomon.example.org/hit/exemple"&gt;https://dolomon.example.org/hit/exemple&lt;/a&gt;</code></pre><br>
						<em><?php _e( 'optional', 'dolomon' ) ?></em><br>
						<?php _e( 'returns a link to the dolomon URL. Formatted with those optional parameters (default to the dolomon URL):', 'dolomon' ) ?>
						<ul>
							<li>
								<pre><code>button</code></pre> ➡ <pre><code>[dolo id=1 link=true button=true]</code></pre><br>
								<?php _e( 'add a class to the link to look like a button', 'dolomon' ) ?>
							</li>
							<li>
								<pre><code>self</code></pre> ➡ <pre><code>[dolo id=1 link=true self=true]</code></pre><br>
								<?php _e( 'the name of the dolo or its target URL (like https://example.org) if it\'s unnamed', 'dolomon' ) ?><br>
								<?php _e( 'the other parameters works like described above', 'dolomon' ) ?>
							</li>
							<li>
								<pre><code>name</code></pre> ➡ <pre><code>[dolo id=1 link=true name="%name (%count)]</code></pre>
							</li>
							<li>
								<pre><code>count</code></pre> ➡ <pre><code>[dolo id=1 link=true count=true]</code></pre>
							</li>
							<li>
								<pre><code>extra</code></pre> ➡ <pre><code>[dolo id=1 link=true extra=true]</code></pre>
							</li>
						</ul>
					</li>
				</ul>
				<?php _e( 'Please note that the parameters don\'t cumulate. <pre><code>self</code></pre> is overloaded by any other parameter except <pre><code>button</code></pre>', 'dolomon' ) ?>
				<?php _e( 'Then the formatting stops at first match in that order:', 'dolomon' ) ?>
				<ol>
					<li>
						<pre><code>name</code></pre>
					</li>
					<li>
						<pre><code>count</code></pre>
					</li>
					<li>
						<pre><code>extra</code></pre>
					</li>
				</ol>
				<h2>[dolos]</h2>
				<?php _e( 'Shows the dolos of a category, a tag, or all, depending of the parameters.', 'dolomon' ) ?><br>
				<?php _e( 'Accepts all the parameters of the <pre><code>[dolo]</code></pre> shortcode except <pre><code>id</code></pre>.', 'dolomon' ) ?>
				<?php _e( 'Those parameters will be used for the formatting of the dolos.', 'dolomon' ) ?>
				<ul>
					<li>
						<pre><code>page</code></pre> ➡ <pre><code>[dolos page=true]</code></pre><br>
						<?php _e( 'Shows all the dolos, grouped by categories, with a filter field to quickly search a dolo.', 'dolomon' ) ?><br>
						<?php _e( 'Have an optional parameter:', 'dolomon' ) ?>
						<ul>
							<li>
								<pre><code>featured</code></pre> ➡ <pre><code>[dolos page=true featured=1,3,18]</code></pre><br>
								<?php _e( 'Add a list of dolos before the categories blocks. Argument is a comma separated list of dolos id', 'dolomon' ) ?>
							</li>
						</ul>
					</li>
					<li>
						<pre><code>cat</code></pre> ➡ <pre><code>[dolos cat=1]</code></pre><br>
						<?php _e( 'The id of the category. Will show something like that:' ) ?>
						<h3><?php _e( 'Name of the category', 'dolomon' ) ?></h3>
						<ul>
							<li>dolo 1</li>
							<li>dolo 2</li>
						</ul>
						<?php _e( 'Can take the following optional parameters:', 'dolomon' ) ?>
						<ul>
							<li>
								<pre><code>tags</code></pre> ➡ <pre><code>[dolos cat=1 tags=3,7]</code></pre><br>
								<?php _e( 'used to filter the dolos of the category: will show only the dolos that have at least one of those tags', 'dolomon' ) ?>
							</li>
							<li>
								<pre><code>notitle</code></pre> ➡ <pre><code>[dolos cat=1 notitle=true]</code></pre><br>
								<?php _e( 'do no show the category name, print only the list of dolos', 'dolomon' ) ?>
							</li>
						</ul>
					</li>
					<li>
						<pre><code>tag</code></pre> ➡ <pre><code>[dolos tag=1]</code></pre><br>
						<?php _e( 'The id of the tag. Will show something like that:' ) ?>
						<h3><?php _e( 'Name of the tag', 'dolomon' ) ?></h3>
						<ul>
							<li>dolo 1</li>
							<li>dolo 2</li>
						</ul>
						<?php _e( 'Can take the following optional parameters:', 'dolomon' ) ?>
						<ul>
							<li>
								<pre><code>cats</code></pre> ➡ <pre><code>[dolos tag=1 cats=3,7]</code></pre><br>
								<?php _e( 'used to filter the dolos of the tag: will show only the dolos that belongs to one of those categories', 'dolomon' ) ?>
							<li>
								<pre><code>notitle</code></pre> ➡ <pre><code>[dolos cat=1 notitle=true]</code></pre><br>
								<?php _e( 'do no show the category name, print only the list of dolos', 'dolomon' ) ?>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var dolomonPostUrl = '<?php echo $url ?>';
	var dolomon_hit_enter = '<?php _e( 'Hit Ctrl+C then enter to copy the short link', 'dolomon' ) ?>';
	var dolomon_dismiss_notice = '<?php _e( 'Dismiss this notice.' ) ?>';
	var dolomon_add_cat_success = '<?php _e( 'The category has been successfully created', 'dolomon' ) ?>';
	var dolomon_add_tag_success = '<?php _e( 'The tag has been successfully created', 'dolomon' ) ?>';
	var dolomon_add_dolo_success = '<?php _e( 'The dolo has been successfully created', 'dolomon' ) ?>';
</script>

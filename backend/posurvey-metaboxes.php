<?php

add_action( 'add_meta_boxes', 'po_add_survey_metaboxes' );

function po_add_survey_metaboxes(){
	add_meta_box(
		'po_survey_metafields',
		'Stage Items',
		'po_add_survey_metafields',
		'posurvey',
		'normal',
		'high'
	);
}

function po_add_survey_metafields(){
	global $post;
    wp_nonce_field( basename( __FILE__ ), 'wpse_our_nonce' );
	$post_metas = get_post_meta( $post->ID );
	$stage_metas = get_post_meta($post->ID, '_po_survey_stage_meta', true);
	
	?>
	<div class="po-items-placeholder">
		<div class="po-items-placeholder-list">
			<ul id="po-make-clonable">
			<?php if (!$stage_metas){ ?>
				<li class="input-placeholder" data-item="1">
					<div>
						<label for="po_item_id">Item ID</label>
						<input type="text" name="po_item[1][id]" required>
					</div>
					<div>
						<label for="po_item_id">Item Image URL</label>
						<input type="url" name="po_item[1][image_url]" required>
						<button class="set_custom_images button">Set Image URL</button>
					</div>
					<div>
						<label for="po_item_id">Item Title</label>
						<input type="text" name="po_item[1][title]" required>
					</div>
					<div class="po_float_controls">
						<ul>
							<li><i class="fas fa-ellipsis-v" title="Reorder"></i></li>
							<li><i class="fas fa-trash-alt" title="Remove"></i></li>
						</ul>
					</div>
				</li>
				<?php }else{ $i = 1;?>
				<?php foreach($stage_metas as $meta){ ?>
				<li class="input-placeholder" data-item="<?php echo $i ?>">
					<div>
						<label for="po_item_id">Item ID</label>
						<input type="text" name="po_item[<?php echo $i ?>][id]" value="<?php echo $meta['id'] ?>" required>
					</div>
					<div>
						<label for="po_item_id">Item Image URL</label>
						<input type="url" name="po_item[<?php echo $i ?>][image_url]" value="<?php echo $meta['image_url'] ?>" required>
						<button class="set_custom_images button">Set Image URL</button>
					</div>
					<div>
						<label for="po_item_id">Item Title</label>
						<input type="text" name="po_item[<?php echo $i ?>][title]" value="<?php echo $meta['title'] ?>" required>
					</div>
					<div class="po_float_controls">
						<ul>
							<li><i class="fas fa-ellipsis-v" title="Reorder"></i></li>
							<li><i class="fas fa-trash-alt" title="Remove"></i></li>
						</ul>
					</div>
				</li>
				<?php $i++; } // End foreach ?>
				<?php } // End if ?>
			</ul>
		</div>
		<div class="po-items-placeholder-footer">
			<button id="po_add_item" class="button"><?php _e('Add item', 'po_survey') ?></button>
		</div>
	</div>
	<?php
}

function wpse_save_meta_fields($post_id){
	update_post_meta($post_id, '_po_survey_stage_meta', $_POST['po_item']);
}
add_action( 'save_post', 'wpse_save_meta_fields' );
add_action( 'new_to_publish', 'wpse_save_meta_fields' );
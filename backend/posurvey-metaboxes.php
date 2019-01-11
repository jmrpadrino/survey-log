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

    add_meta_box(
        'po_survey_applications_metafields',
        'Survey Application Information',
        'po_survey_applications_metafields',
        'pos_apply',
        'normal',
        'high'
    );
    add_meta_box(
        'po_survey_applications_logs',
        'Survey Application Logs',
        'po_survey_applications_logs',
        'pos_apply',
        'normal',
        'high'
    );
    add_meta_box(
        'po_survey_publish',
        _('Publish'),
        'po_survey_publish',
        'pos_apply',
        'side',
        'high'
    );
}

add_action( 'do_meta_boxes', 'wpdocs_remove_plugin_metaboxes' );

/**
 * Remove Editorial Flow meta box for users that cannot delete pages
 */
function wpdocs_remove_plugin_metaboxes(){
    remove_meta_box( 'submitdiv', 'pos_apply', 'side' );
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
                <div class="po_float_controls">
                    <ul>
                        <li><i class="fas fa-trash-alt" title="Remove"></i></li>
                        <li><i class="fas fa-ellipsis-v" title="Reorder"></i></li>
                    </ul>
                </div>
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
            </li>
            <?php }else{ $i = 1;?>
            <?php foreach($stage_metas as $meta){ ?>
            <li class="input-placeholder" data-item="<?php echo $i ?>">
                <div class="po_float_controls">
                    <ul>
                        <li><i class="fas fa-trash-alt" title="Remove"></i></li>
                        <li><i class="fas fa-ellipsis-v" title="Reorder"></i></li>
                    </ul>
                </div>
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
    update_post_meta($post_id, '_po_survey_stage_log', $_POST['po_item']);
    update_post_meta($post_id, '_po_survey_status', $_POST['_po_survey_status']);
}
//add_action( 'save_post', 'wpse_save_meta_fields' );
//add_action( 'new_to_publish', 'wpse_save_meta_fields' );



function po_survey_publish(){
?>
<div style="display:none;">
    <p class="submit"><input type="submit" name="save" id="save" class="button" value="Save"></p>
</div>
<input name="original_publish" type="hidden" id="original_publish" value="Update">
<input name="save" type="submit" class="button button-primary button-large" id="publish" value="Update">
<?php
}

function po_survey_applications_metafields($post){
    $survey_data = get_post_meta($post->ID, '_po_survey_meta', true);
    $survey_status = get_post_meta($post->ID, '_po_survey_status', true);
    $survey_title = get_term_by('slug', $survey_data['survey']['survey_slug'], 'po-survey-group');

    ?>
    <style>
        .survey-quote-info-placeholder{
            display: flex;
            justify-content: space-between;
            flex-direction: row;
            align-items: flex-start;
            align-content: flex-start;
            line-height: 1
        }
        .op-col-1{
            width: 33.333333%;
            padding: 5px 10px;
        }
        .op-col-2{
            width: 66.666666%;
            padding: 5px 10px;
        }
        .survey-quote-info-placeholder h3{
            color: #ffad12;
        }
        .survey-quote-info-placeholder p{
            margin: 0;
        }
        .survey-quote-info-placeholder select{
            width: 100%;
        }
    </style>
    <div class="survey-quote-info-placeholder">
        <div class="op-col-1">
            <h3><?php _e('General', 'op_survey') ?></h3>
            <strong><?php _e('Date of application') ?>:</strong>
            <p><?php echo $post->post_date ?></p>
            <h3 for="_po_survey_status"><?php _e('Status') ?></h3>
            <select id="_po_survey_status" name="_po_survey_status">
                <option value="0" <?php echo ($survey_status == '0') ? 'selected' : '' ?>><?php _e('On Hold', 'op_survey') ?></option>
                <option value="1" <?php echo ($survey_status == '1') ? 'selected' : '' ?>><?php _e('In Progress', 'op_survey') ?></option>
                <option value="2" <?php echo ($survey_status == '2') ? 'selected' : '' ?>><?php _e('Ready', 'op_survey') ?></option>
                <option value="3" <?php echo ($survey_status == '3') ? 'selected' : '' ?>><?php _e('Pending', 'op_survey') ?></option>
                <option value="4" <?php echo ($survey_status == '4') ? 'selected' : '' ?>><?php _e('Closed', 'op_survey') ?></option>
            </select>
            <h3><?php _e('Contact Info', 'op_survey') ?></h3>
            <p><strong><?php _e('Name', 'op_survey') ?>:</strong> <?php echo $survey_data['userData']['first_name'] . ' ' . $survey_data['userData']['last_name']?></p>
            <p><strong><?php _e('Phone', 'op_survey') ?>:</strong> <a href="tel:<?php echo $survey_data['userData']['phone'] ?>"><?php echo $survey_data['userData']['phone'] ?></a></p>
            <p><strong><?php _e('Email', 'op_survey') ?>:</strong> <a href="mailto:<?php echo $survey_data['userData']['email'] ?>"><?php echo $survey_data['userData']['email'] ?></a></p>
        </div>
        <div class="op-col-2">
            <h3><?php _e('Survey Application Info', 'op_survey') ?></h3>
            <p><strong><?php _e('Survey Name', 'op_survey') ?>:</strong> <?php echo $survey_title->name ?></p>
            <h3><?php _e('Survey stages and answares', 'op_survey') ?></h3>

            <?php
                foreach($survey_data['values'] as $index => $value){
                    $question = get_post($index);
                    echo '<p><strong>'.$question->post_title.'</strong> '.$value.'</p>';
                }
            ?>

        </div>
    </div>
    <?php
}

function po_survey_applications_logs(){
    global $post;
    wp_editor( get_post_meta($post->ID, '_po_survey_log', true), 'survey_log', array(
        'wpautop'       => true,
        'media_buttons' => false,
        'textarea_name' => '_po_survey_log',
        'textarea_rows' => 25,
        'teeny'         => false
    ) );
}

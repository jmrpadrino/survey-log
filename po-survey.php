<?php
/**
 * @package po_survey
 * @version 0.1
 *
 *
 * Plugin Name: Survey - Palacios Online
 * Plugin URI: https://palacios-online.de/
 * Description: Plugin for Surveys
 * Author: José Rodriguez and The Palacios Online Team
 * Author URI: https://palacios-online.de/
 * Text Domain: po_survey
 * Domain Path: /languages
 * License: GPLv2 or later

    Survey - Palacios Online is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    any later version.

    Survey - Palacios Online is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Survey - Palacios Online.
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'POSURVEY_PLUGIN_DIR', trailingslashit( dirname(__FILE__) ) );
define( 'POSURVEY_PLUGIN_URI', plugins_url('', __FILE__) );

// BACKEND
require_once 'backend/posurvey-backend-setup.php';
require_once 'backend/posurvey-taxonomies.php';
require_once 'backend/posurvey-custom-post-types.php';
require_once 'backend/posurvey-metaboxes.php';

// FRONTEND
require_once 'frontend/posurvey-frontend-setup.php';
require_once 'frontend/posurvey-shortcodes.php';

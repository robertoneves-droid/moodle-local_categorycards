<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Settings for local_categorycards.
 *
 * @package    local_categorycards
 * @copyright  2026 Moodle
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    // Create settings page.
    $settings = new admin_settingpage('local_categorycards', get_string('pluginname', 'local_categorycards'));

    // Checkbox setting to enable/disable plugin.
    $settings->add(new admin_setting_configcheckbox(
        'local_categorycards/enabled',
        get_string('setting_enabled', 'local_categorycards'),
        get_string('setting_enabled_desc', 'local_categorycards'),
        1 // Enabled by default.
    ));

    // Select setting to define the number of columns.
    $options = [
        'auto' => get_string('columns_auto', 'local_categorycards'),
        '3' => get_string('columns_3', 'local_categorycards'),
        '4' => get_string('columns_4', 'local_categorycards'),
    ];
    $settings->add(new admin_setting_configselect(
        'local_categorycards/columns',
        get_string('setting_columns', 'local_categorycards'),
        get_string('setting_columns_desc', 'local_categorycards'),
        'auto',
        $options
    ));

    // Add settings page to the local plugins settings category.
    $ADMIN->add('localplugins', $settings);
}

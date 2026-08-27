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
 * Version details for local_categorycards.
 *
 * @package    local_categorycards
 * @copyright  2026 Moodle
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/** @var stdClass $plugin */

if (!defined('MATURITY_STABLE')) {
    define('MATURITY_STABLE', 200);
}

$plugin->version   = 2026061000;
$plugin->requires  = 2023100900; // Requiring Moodle 4.3 or later.
$plugin->component = 'local_categorycards';
$plugin->maturity  = MATURITY_STABLE;
$plugin->release   = '1.0.0';

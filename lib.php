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

defined('MOODLE_INTERNAL') || die;

global $CFG;
require_once($CFG->dirroot . '/question/editlib.php');
require_once($CFG->dirroot . "/local/questionbanktagfilter/questionbank_tagfilter_condition.php");
require_once($CFG->dirroot . "/local/questionbanktagfilter/classes/tags_column.php");
require_once($CFG->dirroot . "/local/questionbanktagfilter/classes/edit_action_column.php");

/**
 * Registers the tag condition to the QuestionBank View
 *
 * @param \core_question\bank\view $view
 * @return question_bank_tag_condition
 *
 * @package    local
 * @subpackage questionbanktagfilter
 * @copyright  2015-2016 CRS4
 * @license    https://opensource.org/licenses/mit-license.php MIT license
 */
function local_questionbanktagfilter_get_question_bank_search_conditions($caller)
{
    return array(new local_questionbanktagfilter_get_question_bank_search_condition($caller));
}

/**
 * Registers the tag column to display in the QuestionBank
 *
 * @param \core_question\bank\view $view
 * @return question_bank_column_base
 *
 * @package    local
 * @subpackage questionbanktagfilter
 * @copyright  2015-2016 CRS4
 * @license    https://opensource.org/licenses/mit-license.php MIT license
 */
function local_questionbanktagfilter_get_question_bank_column_types($question_bank_view)
{
    if ($question_bank_view == 'quiz_question_bank_view') {
        return array();
    }
    return array(
        'tags' => new local_questionbanktagfilter_tags_column($question_bank_view),
        'edit' => new local_questionbanktagfilter_edit_action_column($question_bank_view),
        'view' => new local_questionbanktagfilter_view_action_column($question_bank_view),
        'copy' => new local_questionbanktagfilter_copy_action_column($question_bank_view),
        'translate' => new local_questionbanktagfilter_translate_action_column($question_bank_view),
        'delete' => new local_questionbanktagfilter_delete_action_column($question_bank_view)
    );
}


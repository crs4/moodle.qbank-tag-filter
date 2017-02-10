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

require_once($CFG->dirroot . "/local/questionbanktagfilter/question_bank_tag_condition.php");
require_once($CFG->dirroot . '/question/classes/bank/search/hidden_condition.php');
require_once($CFG->dirroot . '/question/classes/bank/view.php');
require_once($CFG->dirroot . '/question/editlib.php');

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
function local_questionbanktagfilter_get_question_bank_search_conditions(core_question\bank\view $view){
    $condition = new \question_bank_tag_condition(true);
    $view->add_searchcondition($condition);
    return $condition;
}


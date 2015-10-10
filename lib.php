<?php
/**
 * Created by PhpStorm.
 * User: kikkomep
 * Date: 10/9/15
 * Time: 2:19 PM
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . "/local/questionbanktagfilter/question_bank_tag_condition.php");
require_once($CFG->dirroot . '/question/classes/bank/search/hidden_condition.php');
require_once($CFG->dirroot . '/question/classes/bank/view.php');
require_once($CFG->dirroot . '/question/editlib.php');

/**
 * Registers the tag condition to the QuestionBank View
 *
 * @param \core_question\bank\view $view
 * @return question_bank_tag_condition
 */
function local_questionbanktagfilter_get_question_bank_search_conditions(core_question\bank\view $view){
    $condition = new \question_bank_tag_condition(true);
    $view->add_searchcondition($condition);
    return $condition;
}


<?php

// Copyright (c) 2015-2016, CRS4
//
// Permission is hereby granted, free of charge, to any person obtaining a copy of
// this software and associated documentation files (the "Software"), to deal in
// the Software without restriction, including without limitation the rights to
// use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
// the Software, and to permit persons to whom the Software is furnished to do so,
// subject to the following conditions:
//
// The above copyright notice and this permission notice shall be included in all
// copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
// FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
// COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
// IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
// CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/question/classes/bank/search/condition.php');

/**
 * QuestionBank Tag Filter.
 *
 * @package    local
 * @subpackage questionbanktagfilter
 * @copyright  2015-2016 CRS4
 * @licence    https://opensource.org/licenses/mit-license.php MIT licence
 */
class question_bank_tag_condition extends \core_question\bank\search\condition
{
    /** @var bool Whether to include old "deleted" questions. */
    protected $hide;

    /** @var string SQL fragment to add to the where clause. */
    protected $where;

    /** @var string SQL fragment to add to the params to the WHERE clause. */
    protected $params;

    /**
     * Constructor.
     * @param bool $hide whether to include old "deleted" questions.
     */
    public function __construct($hide = true) {
        global $CFG, $DB;
        $this->hide = $hide;
        if ($hide) {
            $this->where = 'q.hidden = 0';
        }

        $tagidtest = null;
        $this->params = array();

        if(isset($_REQUEST["tags"]) && $_REQUEST["tags"] !== "*") {
            $current_tag = $_REQUEST["tags"];
            if (!empty($current_tag) && $current_tag !== "all") {
                $this->where = "q.id IN (SELECT tagi.itemid FROM {tag_instance} tagi WHERE tagi.tagid IN ($current_tag))";
            }
        }
    }

    public function where() {
        return  $this->where;
    }

    public function params() {
        return $this->params;
    }

    protected function get_tag_options($sortorder = 'name ASC') {
        global $DB;
        $values = $DB->get_records_sql("
            SELECT DISTINCT t.id as id, t.name AS name
              FROM {tag} t JOIN {tag_instance} ti ON t.id=ti.tagid
          ORDER BY $sortorder");

        $options = array("*" => get_string("alltags", 'local_questionbanktagfilter'));
        foreach($values as $name=>$value){
            $options[$value->id] = $value->name;
        }

        return $options;
    }

    public function display_options() {
        global $PAGE;
        // Includes a JavaScript
        $jsmodule = array(
            'name' => 'question_bank_tag_filter_utils',
            'fullpath' => '/local/questionbanktagfilter/question_bank_tag_filter_utils.js',
            'requires' => array());
        $PAGE->requires->js_init_call('M.question_bank_tag_filter_helper.init', array(), true, $jsmodule);

        $current_tags_value = isset($_REQUEST['tags']) ? $_REQUEST['tags'] : "";
        $current_tags = explode(",", $current_tags_value);
        $tagmenu = $this->get_tag_options();

        echo \html_writer::start_div('choosetag', array("style"=> "margin: 20px 0px;"));
        echo '<input id="id_tags" name="tags" type="hidden" value="' . $current_tags_value .'"/>';
        echo \html_writer::label(get_string('selectoneormoretag', 'local_questionbanktagfilter'), 'id_selectatag');
        echo \html_writer::select($tagmenu, 'tag', $current_tags, array(), array(
            'multiple' => '',
            'size' => '5',
            'style' => 'min-width: 300px; padding: 5px',
            'id' => 'id_selectatag'
        ));
        echo '<br/><input id="search_by_tag_button" type="button" value="' .
            get_string('filterbyselectedtags', 'local_questionbanktagfilter') . '"/>';
        echo \html_writer::end_div() . "\n";
    }

    /**
     * Print HTML to display the "Also show old questions" checkbox
     */
    public function display_options_adv() {
        echo \html_writer::start_div();
        echo \html_writer::end_div() . "\n";
    }
}

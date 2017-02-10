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
 * @license    https://opensource.org/licenses/mit-license.php MIT license
 */
class local_questionbanktagfilter_get_question_bank_search_condition extends core_question\bank\search\condition
{
    /** @var array list of tags to filter questions. */
    protected $tags;

    /** @var string SQL fragment to add to the where clause. */
    protected $where;

    /** @var string SQL fragment to add to the params to the WHERE clause. */
    protected $params;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->tags = optional_param_array('tags', array(), PARAM_TEXT);
        if ((!empty($this->tags)) && $this->tags[0] == null) {
            array_shift($this->tags);
        }
        if ((!empty($this->tags)) || (!empty($this->nottags))) {
            $this->init();
        }
    }

    /**
     * Initialize filter parameters.
     */
    private function init()
    {
        global $DB;

        $this->params = array();
        if (!empty($this->tags)) {
            if (!is_numeric($this->tags[0])) {
                list($tagswhere, $tagsparams) = $DB->get_in_or_equal($this->tags, SQL_PARAMS_NAMED, 'tag');
                $tagids = $DB->get_fieldset_select('tag', 'id', 'name ' . $tagswhere, $tagsparams);
            } else {
                $tagids = $this->tags;
            }
            list($where, $this->params) = $DB->get_in_or_equal($tagids, SQL_PARAMS_NAMED, 'tag');
            $this->where = "(SELECT COUNT(*) as tagcount FROM {tag_instance} ti WHERE itemid=q.id AND tagid $where)=" .
                count($this->tags);
        }
    }

    public function where() {
        return  $this->where;
    }

    public function params() {
        return $this->params;
    }

    /**
     * Display filter controls.
     */
    public function display_options()
    {
        require_login();

        $tags = $this->get_tags_used();
        $attr = array(
            'multiple' => 'true',
            'class' => 'searchoptions large',
            'style' => 'min-width: 300px; padding: 5px',
        );
        if (count($tags) > 10) {
            $attr['size'] = 10;
        }

        echo \html_writer::start_div('choosetag', array("style" => "margin: 20px 0px;"));
        echo \html_writer::label(get_string('selectoneormoretag', 'local_questionbanktagfilter'), 'id_selectatag');
        echo \html_writer::select($tags, 'tags[]', $this->tags,
            array('' => get_string("alltags", 'local_questionbanktagfilter')), $attr);
        echo \html_writer::end_div() . "\n";
    }

    /**
     * Print HTML to display the "Also show old questions" checkbox
     */
    public function display_options_adv()
    {
        echo \html_writer::start_div();
        echo \html_writer::end_div() . "\n<br>";
    }

    /**
     * Utility method which returns the list of tags in use.
     *
     * @return array
     */
    private function get_tags_used()
    {
        global $DB;
        $categories = $this->get_categories();
        list($catidtest, $params) = $DB->get_in_or_equal($categories, SQL_PARAMS_NAMED, 'cat');
        $sql = "SELECT name as value, name as display FROM {tag} WHERE id IN (SELECT DISTINCT tagi.tagid FROM {tag_instance} tagi, {question} WHERE itemtype='question' AND {question}.id=tagi.itemid AND category $catidtest) ORDER BY name";
        return $DB->get_records_sql_menu($sql, $params);
    }
    }
}
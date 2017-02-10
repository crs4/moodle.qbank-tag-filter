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
    
    public function where()
    {
        return $this->where;
    }

    public function params()
    {
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

    /**
     * Utility method which returns the current selected category.
     */
    protected function get_current_category($categoryandcontext)
    {
        global $DB;
        echo "categoryandcontext: $categoryandcontext\n";
        list($categoryid, $contextid) = explode(',', $categoryandcontext);
        if (!$categoryid) {
            return false;
        }

        if (!$category = $DB->get_record('question_categories',
            array('id' => $categoryid, 'contextid' => $contextid))
        ) {
            return false;
        }
        return $category;
    }

    /**
     * Utility method which returns the list of system categories.
     *
     * @return array
     */
    private function get_categories()
    {
        $cmid = optional_param('cmid', 0, PARAM_INT);
        $categoryparam = optional_param('category', '', PARAM_TEXT);
        $courseid = optional_param('courseid', 0, PARAM_INT);

        if ($cmid) {
            list($thispageurl, $contexts, $cmid, $cm, $quiz, $pagevars) =
                question_edit_setup('editq', '/mod/quiz/edit.php', true);
            if ($pagevars['cat']) {
                $categoryparam = $pagevars['cat'];
            }
        }

        if ($categoryparam) {
            $catandcontext = explode(',', $categoryparam);
            $cats = question_categorylist($catandcontext[0]);
            return $cats;
        } elseif ($cmid) {
            list($module, $cm) = get_module_from_cmid($cmid);
            $courseid = $cm->course;
            require_login($courseid, false, $cm);
            $thiscontext = context_module::instance($cmid);
        } else {
            $module = null;
            $cm = null;
            if ($courseid) {
                $thiscontext = context_course::instance($courseid);
            } else {
                $thiscontext = null;
            }
        }

        $cats = get_categories_for_contexts($thiscontext->id);
        return array_keys($cats);
    }
}
<?php


defined('MOODLE_INTERNAL') || die();


require_once($CFG->dirroot . '/question/classes/bank/search/condition.php');

/**
 * Created by PhpStorm.
 * User: kikkomep
 * Date: 10/9/15
 * Time: 2:39 PM
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

        $options = array("*" => "all tags");
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

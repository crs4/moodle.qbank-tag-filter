# moodle.qbank-tag-filter

A tag based filter for Moodle question bank.


## How to install

* Copy this repository to the `<MOODLE-ROOT-DIR>/local/questionbanktagfilter`
* Run the `register.sh` script (you need to properly set the `MOODLE_WWW` variable of your environment to point to the root folder of your Moodle installation) or edit `<MOODLE-ROOT-DIR>/config.php` to add the following line:

		$CFG->questionbankcolumns = 'checkbox_column,question_type_column,question_name_column,local_questionbanktagfilter_edit_action_column,local_questionbanktagfilter_translate_action_column,local_questionbanktagfilter_view_action_column,preview_action_column,local_questionbanktagfilter_copy_action_column,local_questionbanktagfilter_delete_action_column,creator_name_column,modifier_name_column,local_questionbanktagfilter_question_bank_column';
		
* Go to *Site Administrations* ---> *Notifications* and follow the Moodle instructions to complete the plugin installation.

## Requirements

* Moodle 2.9 or later (available on the [Moodle site](https://download.moodle.org/releases/supported/))


## Copyright and license
Code and documentation Copyright © 2015-2016, [CRS4](http://www.crs4.it). 
Code released under the [MIT license](https://opensource.org/licenses/mit-license.php). 
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

/**
 * Utility class.
 *
 * @package    local
 * @subpackage questionbanktagfilter
 * @copyright  2015-2016 CRS4
 * @licence    https://opensource.org/licenses/mit-license.php MIT licence
 */
M.question_bank_tag_filter_helper = {};

/**
 * Initializes the module
 */
M.question_bank_tag_filter_helper.init = function () {

    // Registers the click event
    document.getElementById("search_by_tag_button").onclick = function () {
        var s = document.getElementById("id_selectatag");
        console.log(s);
        var tags = [];
        for (var i = 0; i < s.options.length; i++) {
            console.log(i, s.options[i], s.options[i].selected);
            if (s.options[i].selected) {
                tags.push(s.options[i].value);
            }
        }
        var tags_input_value = tags.join(",");
        console.log(tags_input_value, tags);
        document.getElementById("id_tags").value = tags_input_value;
        document.getElementById("displayoptions").submit();
    }
};

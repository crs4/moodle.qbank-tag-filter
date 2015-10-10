/**
 * Created by kikkomep on 10/10/15.
 */

M.question_bank_tag_filter_helper = {};


/**
 *  echo '<script type="text/javascript">document.getElementById("search_by_tag_button").onclick=';
 echo 'function(){ console.log(this); ';
        echo 'var s = document.getElementById("id_selectatag"); console.log(s);';
        echo 'var tags = [];';
        echo 'for(var i=0; i < s.options.length; i++){ ';
        echo 'console.log(i, s.options[i],  s.options[i].selected);';
        echo 'if(s.options[i].selected){ tags.push(s.options[i].value)}; } ';
        echo 'var tags_input_value = tags.join(",");';
        echo 'console.log(tags_input_value, tags);';
        echo 'document.getElementById("id_tags").value=tags_input_value; ';
        echo 'alert("check:" + tags_input_value); document.getElementById("displayoptions").submit() }';
 echo '</script>';
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
        //alert("check:" + tags_input_value);
        document.getElementById("displayoptions").submit();
    }
}

<table class="form-table">
    <tbody>
        <tr valign="top">
            <th>
                Name
            </th>
            <td>
                <input type="text" id="calendar_name" name="calendar_name" value="<?php echo $cdata->calendar_name; ?>" class="large-text" placeholder="Name">
            </td>
        </tr>
        <tr valign="top">
            <th colspan="2">
                Calendar Links <a href="javascript:void(0)" class="add-new-h2" id="add-more-calendar-links">Add More</a>

                <table class="form-table" id="calendar-links">
                    <?php
                    if(count($cdata->calendar_links)>0){
                        foreach($cdata->calendar_links as $clink) {
                            ?>
                            <tr valign="top" class="calendar_link_row">
                                <th>
                                    Link
                                </th>
                                <td>
                                    <input type="text" name="calendar_links[]" value="<?php echo $clink; ?>" class="large-text" placeholder="ics/ical Link">
                                    <a href="javascript:void(0)" class="remove-calendar-link vrc-remove-link">Remove</a>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </table>
            </th>
        </tr>
        <tr valign="top">
            <th>
                Columns
            </th>
            <td>
                <select name="calendar_layout_options[columns]" class="large-text">
                <?php for($i=1;$i<=12; $i++):
                    $selected = '';
                    if($cdata->calendar_layout_options['columns'] == $i)
                        $selected = 'selected="selected"';
                    ?>
                    <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?></option>
                <?php endfor; ?>
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th>
                Rows
            </th>
            <td>
                <select name="calendar_layout_options[rows]" class="large-text">
                    <?php for($i=1;$i<=12; $i++):
                        $selected = '';
                        if($cdata->calendar_layout_options['rows'] == $i)
                            $selected = 'selected="selected"';
                        ?>
                        <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th>
                Size
            </th>
            <td>
                <fieldset>
                    <legend class="screen-reader-text"><span>input type="radio"</span></legend>
                    <?php foreach($layout_option_size as $sizek=>$sizev):
                        $checked = '';
                        if($sizek == $cdata->calendar_layout_options['size'])
                            $checked = 'checked="checked"';
                        ?>
                        <label title='<?php echo $sizev; ?>'><input type="radio" name="calendar_layout_options[size]" value="<?php echo $sizek; ?>" <?php echo $checked; ?> /> <span><?php echo $sizev; ?></span></label> &nbsp;
                    <?php endforeach; ?>
                </fieldset>
            </td>
        </tr>
    </tbody>
</table>
<table class="form-table" id="calendar-links-cloner">
    <tr valign="top" class="calendar_link_row">
        <th>
            Link
        </th>
        <td>
            <input type="text" name="calendar_links[]" value="" class="large-text" placeholder="ics/ical Link">
            <a href="javascript:void(0)" class="remove-calendar-link vrc-remove-link">Remove</a>
        </td>
    </tr>
</table>

<table class="form-table">
    <tbody>
    <tr valign="top">
        <th>
            Auto Sync
        </th>
        <td>
            <select id="auto_sync" name="auto_sync" class="large-text">
                <?php foreach($auto_sync as $val=>$text):
                    $selected = '';
                    if( $val == $VRCalendarSettings->getSettings('auto_sync', 'daily') )
                        $selected = 'selected="selected"';
                    ?>
                    <option value="<?php echo $val; ?>" <?php echo $selected; ?>><?php echo $text; ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    </tbody>
</table>
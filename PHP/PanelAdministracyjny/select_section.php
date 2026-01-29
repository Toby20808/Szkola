<?php
function renderSelectSection($i) {
    return "<div class='form-group'>
        <label>Column Name</label>
        <input type='text' name='col_name_$i' class='form-control'/>
    </div>
    <div class='form-group'>
        <label>Data Type</label>
        <select name='col_type_$i' class='form-control'>
        <option>INT</option>
        <option>FLOAT</option>
        <option>DATE</option>
        <option>VARCHAR</option>
        <option>TEXT</option>
    </select>
    </div>
    <div class='checkbox-group'>
        <label><input type='checkbox' name='primaryKey_$i' value='PRIMARY KEY'/> Primary Key</label>
        <label><input type='checkbox' name='notNull_$i' value='NOT NULL'/> NOT NULL</label>
    </div>
    <div class='form-group'>
        <label>Długość/Wartość:</label>
        <input type='number' name='col_len_$i' class='form-control'/>
    </div>";
}
?>

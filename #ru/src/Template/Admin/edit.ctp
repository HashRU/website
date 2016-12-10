<h1> Edit a user </h1>

<form action="/admin/edit" method="post">

<?php
	echo "<input type=\"hidden\" name=\"old_uid\" value=\"" . $item['uid'][0] . "\"/>";
	foreach($item as $key => $value) {
		if(!is_string($key)) {
			continue;
		}
		echo "<label for=\"$key\"> $key </label>";
		if(is_array($value)) {
			foreach($value as $id => $value_bit) {
				echo "<input type=\"text\" name=\"" . $key . "[" . $id . "]\"" . "value=\"$value_bit\"/>";
			}
		} else {
			echo "<input type=\"text\" name=\"$key\" value=\"$value\"/>";
		}
	}
?>
<input type="submit" value="Submit"/>
</form>

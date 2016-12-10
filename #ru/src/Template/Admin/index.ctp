<h1> User admin panel </h1>

<table>
	<?php foreach($items as $item) {
		echo
			'<tr> <td>' .
			'<form action="/admin/edit" method="get">' .
			'<label for="username">Username: ' . $item['uid'][0] . '</label>' .
			'<input type="hidden" name="username" value="' . $item['uid'][0] . '"/>' .
			'<input type="submit" value="Edit"/>' .
			'</form>' .
			'</td> </tr>';
	}
	?>
</table>

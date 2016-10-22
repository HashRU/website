<h1> User admin panel </h1>

<table>
	<?php foreach($items as $item) {
		echo
			'<tr>' .
			'<td>' . $item['uid'][0] . '</td>' .
			'<td> <button> edit </button> </td>' .
			'</tr>';
	}
	?>
</table>

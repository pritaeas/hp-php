<?php
    $data = array (
	    1 => array (
		    'title' => 'Using PHP',
		    'comments' => array (
			    'Nice article!',
			    'Thanks for explaining!'
	        )
		),
	    2 => array (
		    'title' => 'Using loops',
		    'comments' => array (
			    'This could be better.',
			    'Did you write this in 5 minutes?',
			    'Please show more. Kinda vague'
			)
	    )
    );

	foreach ($data as $post) {
		// This loops items 1 and 2
		echo "<h1>{$post['title']}</h1>";

		foreach ($post['comments'] as $comment) {
			// this loops the comments for the current item
			echo "<p>$comment</p>";
		}
	}
?>
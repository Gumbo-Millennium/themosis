<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#006b00">

	@wp_head
</head>
<body>
<div id="app">

	<div class="wrapper">
		<div class="container">
			<div class="content">
				<div class="post">
					{!! apply_filters('the_content', $post->post_content) !!}
				</div>
			</div>
			@include('banner')
		</div>
	</div>

</div>
@wp_footer
</body>
</html>

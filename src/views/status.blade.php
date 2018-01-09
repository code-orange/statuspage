<!doctype html>
<html>
	<head>
		<title>{{config('app.name', ENV('APP_NAME', 'Change me with APP_NAME or config app.name'))}} status</title>

		<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
		<style>
			* {
				font-family: 'Roboto', sans-serif;
			}

			main {
				width: 90%;
				max-width: 850px;
				margin: auto;
				padding-top: 5rem;
			}

			h1 {
				border-bottom: 1px solid black;
				font-size: 3em;
			}

			section, .check {
				border: 1px solid #E0E0E0;
				border-radius: 5px;
			}

			.check {
				overflow: auto;
				padding: 0.5rem;
				margin-top: 1em;
				margin-bottom: 1em;
			}
			.status {
				float: right;
			}

			section {
				margin-top: 1em;
				margin-bottom: 1em;
			}

			section h2 {
				margin: 0;
				padding: 0.5rem;
			}

			section .checks .check {
				border: none;
			}

			section .checks .check:first-child {
				margin-top: 0;
			}
			section .checks .check:last-child {
				margin-bottom: 0;
			}

			.indicator {
				padding: 1px;
				border: 1px solid;
			}

			.indicator-center {
				width: calc(1em - 4px);
				height: calc(1em - 4px);
			}
		</style>
	</head>
	<body>
		<main>
			<h1>{{config('app.name', ENV('APP_NAME', 'Change me with APP_NAME or config app.name'))}} status</h1>

			<div>
@foreach ($status as $l => $s)@include('statuspage::check', ['label' => $l, 'status' => $s])@endforeach
			</div>
		</main>
	</body>
</html>

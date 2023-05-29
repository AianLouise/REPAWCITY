<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="stylesheet" href="css/book-appointment3.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>rePaw City</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<style>
		scale
		#calendar {
			width: 35rem;
			border: 1px solid black;
			background-color: white;
			padding: 20px;
		}

		.fc-day-number {
			text-align: left;
			float: left;
		}

		.fc-toolbar {
			justify-content: space-between;
			align-items: center;
			margin-bottom: 10px;
		}

		.fc-left,
		.fc-right {
			flex-basis: 40%;
			text-align: left;
		}

		.fc-center {
			flex-basis: 20%;
			text-align: center;
		}
	</style>
</head>

<body>
	<div class="main">
		<div class="navbar1" id="myNavbar">
			<a href="index.php" class="logo"><img src="image/logo (1).png" class="img-logo"></a>
			<h1>Make an Appointment</h1>
		</div>
		<div class="progress1">
			<img src="image/book-appointment/progressbar3.png" alt="" class="progressbar">
		</div>
		<div class="content">
			<div class="scalendar">
				<div id="calendar"></div>
			</div>
		</div>
	</div>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
	<script>
		$(document).ready(function () {
			$('#calendar').fullCalendar({
				header: {
					left: 'prev',
					center: 'title',
					right: 'next'
				},
				selectable: true,
				select: function (start, end) {
					var selectedDate = moment(start).format('YYYY-MM-DD');
					$('#date-input').val(selectedDate);
				},
				events: [
					// Add your events here
					{
						title: 'Appointment',
						start: '2023-05-20T10:00:00',
						end: '2023-05-20T11:00:00',
						color: '#378006'
					},
					{
						title: 'Appointment',
						start: '2023-05-21T14:00:00',
						end: '2023-05-21T15:00:00',
						color: '#378006'
					},
					// Add more events as needed
				]
			});
		});
	</script>
</body>

</html>
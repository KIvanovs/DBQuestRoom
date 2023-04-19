
	// set the available rental dates and times
	var availableDates = ['2023-04-14', '2023-04-15', '2023-04-16'];
	var availableTimes = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00'];

	// handle form submission
	document.querySelector('form').addEventListener('submit', function(event) {
		event.preventDefault();

		// get selected date and time
		var selectedDate = document.querySelector('#date').value;
		var selectedTime = document.querySelector('#time').value;

		// check if selected date and time are available
		if (availableDates.includes(selectedDate) && availableTimes.includes(selectedTime)) {
			document.querySelector('#message').innerHTML = 'The rental is available on ' + selectedDate + ' at ' + selectedTime + '.';
		} else {
			document.querySelector('#message').innerHTML = 'Sorry, the rental is not available on ' + selectedDate + ' at ' + selectedTime + '.';
		}
	});


// Handle form submission
document.getElementById('add-station-form').addEventListener('submit', function (event) {
  event.preventDefault();

  // Collect form data
  const formData = new FormData(event.target);
  const stationData = Object.fromEntries(formData.entries());

  // Simulate sending data to a server
  console.log('New Station Added:', stationData);

  alert('New charging station added successfully!');
  event.target.reset(); // Clear the form
});

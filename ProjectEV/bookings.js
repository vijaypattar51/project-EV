// Add event listener to "Book" buttons
document.addEventListener("DOMContentLoaded", () => {
  const bookButtons = document.querySelectorAll(".book-btn");

  bookButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
      alert("Booking confirmed for " + btn.closest(".booking-card").querySelector("h3").innerText);
    });
  });
});

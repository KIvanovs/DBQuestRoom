
function filterAll() {
  var category = document.getElementById('category').value;
  var ageLimit = document.getElementById('ageLimit').value;
  var peopleAmount = document.getElementById('peopleAmount').value;
  var cards = document.querySelectorAll('.card');

  cards.forEach(function(card) {
    if (
      (!category || card.dataset.category === category) &&
      (!ageLimit || parseInt(card.dataset.ageLimit) <= parseInt(ageLimit)) &&
      (!peopleAmount || card.dataset.peopleAmount === peopleAmount)
    ) {
      card.style.display = 'block';
    } else {
      card.style.display = 'none';
    }
  });
}

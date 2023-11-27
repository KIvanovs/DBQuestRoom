function filterAll() {
    const selectedCategory = document.getElementById('category').value;
    const selectedAgeLimit = document.getElementById('ageLimit').value;
    const selectedPeopleAmount = document.getElementById('peopleAmount').value;
  
    const cards = document.querySelectorAll('.card');
  
    for (let i = 0; i < cards.length; i++) {
      const card = cards[i];
      const cardCategory = card.getAttribute('data-category');
      const cardAgeLimit = card.getAttribute('data-age-limit');
      const cardPeopleAmount = card.getAttribute('data-people-amount');
  
      let showCard = true;
  
      if (selectedCategory && cardCategory !== selectedCategory) {
        showCard = false;
      }
  
      if (selectedAgeLimit && cardAgeLimit !== selectedAgeLimit) {
        showCard = false;
      }
  
      if (selectedPeopleAmount && cardPeopleAmount !== selectedPeopleAmount) {
        showCard = false;
      }
  
      if (showCard) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    }
  }
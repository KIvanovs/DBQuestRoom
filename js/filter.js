function filterCards() {
    var category = document.getElementById("category").value;
    var ageLimit = document.getElementById("ageLimit").value;
    var peopleAmount = document.getElementById("peopleAmount").value;

    var cards = document.getElementsByClassName("card");

    for (var i = 0; i < cards.length; i++) {
        var card = cards[i];
        var cardCategory = card.getAttribute("data-category");
        var cardAgeLimit = parseInt(card.getAttribute("data-age-limit")); // Преобразование в число
        var cardPeopleAmount = card.getAttribute("data-people-amount"); // Не преобразовываем в число

        if (
            (category === "" || category === cardCategory) &&
            (ageLimit === "" || parseInt(ageLimit) >= cardAgeLimit) &&
            (peopleAmount === "" || peopleAmount === cardPeopleAmount)
        ) {
            card.style.display = "block";
        } else {
            card.style.display = "none";
        }
    }
}

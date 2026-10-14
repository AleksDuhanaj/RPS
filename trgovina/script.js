document.addEventListener("DOMContentLoaded", function () {
    const obrazec = document.getElementById("registracijaForm");

    if (obrazec) {
        obrazec.addEventListener("submit", function (event) {
            const geslo = document.getElementById("geslo").value;
            const ponovitev =
                document.getElementById("ponovitev").value;

            if (geslo.length < 8) {
                event.preventDefault();
                alert("Geslo mora imeti vsaj 8 znakov.");
                return;
            }

            if (geslo !== ponovitev) {
                event.preventDefault();
                alert("Gesli se ne ujemata.");
            }
        });
    }

    document.querySelectorAll(".brisi-form").forEach(function (obrazec) {
        obrazec.addEventListener("submit", function (event) {
            const potrjeno = confirm(
                "Ali res želiš izbrisati ta izdelek?"
            );

            if (!potrjeno) {
                event.preventDefault();
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    try {
        const searchParams = new URLSearchParams(window.location.search);
        let params = []
        for (const param of searchParams) {
          params.push(param[0]);
        }
        console.log(params);

    } catch (error) {
        console.error("Error occurred:", error);
    }

    // Récupérer le formulaire
    const form = document.getElementById('shortenForm');

    // Empêcher le formulaire de se soumettre normalement
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        // Récupérer l'URL saisie par l'utilisateur
        let inputUrl = document.getElementById('url').value;

        // Appeler une fonction pour raccourcir l'URL (ici, une fonction factice est utilisée)
        let shortenedUrl = shortenUrl(inputUrl);

        // Afficher le résultat (pour l'exemple, nous allons simplement le loguer)
        console.log('URL raccourcie : ' + shortenedUrl);
    });
});


function shortenUrl(url) {
    const insertDiv = document.getElementById('insertDiv');
    let urlID;
    try {
        // Here you would typically make an asynchronous request to your server
        // to handle the URL shortening process
        // For example, using Fetch API or XMLHttpRequest
        urlID = generateIDFromString(url)
        // For demonstration purposes, let's just log the URL to be shortened
        console.log('URL to be shortened:', url, 'ID :', urlID);
        insertDiv.innerHTML = urlID;


        // Simulating an error for demonstration
        if (url === '') {
            console.error('URL cannot be empty');
        }
        return url;

        // You can then proceed to send this URL to your server for actual shortening
        // and handle the response accordingly
    } catch (error) {
        console.error("Error occurred:", error);
    }
}

function generateIDFromString(str) {
    let hash = 0;
    for (let i = 0; i < str.length; i++) {
        const char = str.charCodeAt(i);
        hash = (hash << 5) - hash + char; // Same as Java's String.hashCode()
    }
    // Convert the hash to a positive number
    hash = Math.abs(hash);
    // Convert the hash to base 36
    let id = hash.toString(36);
    // Adjust the length of the ID based on the length of the input string
    id = id.padEnd(4 + str.length % 5, '0');
    return id;
}

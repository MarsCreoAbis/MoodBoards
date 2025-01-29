const ACCESS_KEY = '7yDJExOqcReYxSGtUKYRTQz_gsv3niZHUvMe9ZTIWww'; // Klucz API Unsplash


// Funkcja obsługująca wyszukiwanie
document.getElementById('searchForm').addEventListener('submit', function(event) {
    event.preventDefault();  // 
    const query = document.getElementById('query').value;
    searchPhotos(query);
});

// Funkcja wyszukiwania zdjęć
function searchPhotos(query) {
    const url = `https://api.unsplash.com/search/photos?query=${query}&per_page=2`;
    const headers = {
        'Authorization': `Client-ID ${ACCESS_KEY}`
    };
    fetch(url, { headers })
        .then(response => response.json())
        .then(data => {
            const photos = data.results;
            displayPhotos(photos);
        })
}

// Funkcja do wyświetlania zdjęć
function displayPhotos(photos) {
    const resultDiv = document.getElementById('result');
    resultDiv.innerHTML = '';  // 

    // 
    photos.forEach(photo => {
        const photoDiv = document.createElement('div');
        photoDiv.style.margin = '10px';

        const img = document.createElement('img');
        img.src = photo.urls.small;
        img.alt = 'Zdjęcie z Unsplash';
        img.style.maxWidth = '100%';
        img.style.height = 'auto';

        const button = document.createElement('button');
        button.type = 'submit';
        button.textContent = 'Wybierz'

        photoDiv.appendChild(img);
        photoDiv.appendChild(button);
        resultDiv.appendChild(photoDiv);

        button.addEventListener('click', function() {
            fetchAssociatedWord().then(randomWord => {
                document.getElementById('query').value = randomWord;
                searchPhotos(randomWord);
            });
        });
    });
    
}

async function fetchAssociatedWord() {
    const tagresultsDiv = document.getElementById('tag-result');
    tagresultsDiv.innerHTML = '';  // 
    const response = await fetch(`https://api.datamuse.com/words?rel_gen=${document.getElementById('query').value}`);
    const data = await response.json();
    const randomWord = data[Math.floor(Math.random() * data.length)].word;
    return randomWord;  // 
}

function updateHistory() {
    const historyContainer = document.getElementById('historyContainer');
    historyContainer.innerHTML = '';
    let pageHistory = JSON.parse(localStorage.getItem('pageHistory')) || [];
    pageHistory.forEach((item, index) => {
        const a = document.createElement('a');
        a.href = item.url;
        a.textContent = item.title;
        historyContainer.appendChild(a);
        if (index < pageHistory.length - 1) {
            historyContainer.appendChild(document.createTextNode(' ⭢ '));
        }
    });
}

function addToHistory(pageTitle, pageUrl) {
    let lastPage = sessionStorage.getItem('lastVisitedPage');
    if (lastPage) {
        lastPage = JSON.parse(lastPage);
        let pageHistory = JSON.parse(localStorage.getItem('pageHistory')) || [];
        const existingIndex = pageHistory.findIndex(item => item.url === lastPage.url);
        if (existingIndex > -1) {
            pageHistory.splice(existingIndex, 1);
        }
        pageHistory.push(lastPage);

        if (pageHistory.length > 5) {
            pageHistory.shift();
        }
        localStorage.setItem('pageHistory', JSON.stringify(pageHistory));
        updateHistory();
    }

    sessionStorage.setItem('lastVisitedPage', JSON.stringify({title: pageTitle, url: pageUrl}));
}

window.onload = function() {
    updateHistory();
    addToHistory(document.title, window.location.href);
};

function toggleSearchForm() {
    var form = document.getElementById('searchForm');
    form.style.display = form.style.display === 'block' ? 'none' : 'block';
    var searchInput = document.getElementById('searchInput');
    searchInput.focus();
}

function showSuggestions(value) {
    const suggestionsBox = document.getElementById('suggestions');
    suggestionsBox.innerHTML = '';
    if (!value) {
        suggestionsBox.style.display = 'none';
        return;
    }

    let searchHistory = JSON.parse(localStorage.getItem('searchHistory')) || [];
    let suggestions = searchHistory.filter(item => item.toLowerCase().includes(value.toLowerCase()));

    let popularMatches = popularSearches.filter(item => item.toLowerCase().includes(value.toLowerCase()));
    suggestions = popularMatches.concat(suggestions.filter(item => !popularMatches.includes(item)));

    suggestions = [...new Set(suggestions)];

    suggestions = suggestions.slice(0, 5);

    if (suggestions.length > 0) {
        suggestions.forEach(suggestion => {
            const div = document.createElement('div');
            div.className = 'suggestion-item';
            div.textContent = suggestion;
            div.onclick = function() {
                document.getElementById('searchInput').value = suggestion;
                suggestionsBox.style.display = 'none';
            };
            suggestionsBox.appendChild(div);
        });
        suggestionsBox.style.display = 'block';
        const searchInput = document.getElementById('searchInput');
        suggestionsBox.style.width = searchInput.offsetWidth + 'px';
        suggestionsBox.style.left = searchInput.offsetLeft + 'px';
        suggestionsBox.style.top = (searchInput.offsetTop + searchInput.offsetHeight) + 'px';
    } else {
        suggestionsBox.style.display = 'none';
    }
}

document.getElementById('searchForm').onsubmit = function() {
    const searchInput = document.getElementById('searchInput').value;
    if (searchInput) {
        let searchHistory = JSON.parse(localStorage.getItem('searchHistory')) || [];
        searchHistory = searchHistory.filter(item => item !== searchInput);
        searchHistory.push(searchInput);

        if (searchHistory.length > 10) {
            searchHistory.shift();
        }
        localStorage.setItem('searchHistory', JSON.stringify(searchHistory));
    }
};

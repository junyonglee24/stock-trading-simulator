const fixedStockSuggestions = [
    { '1. symbol': "AAPL", '2. name': "Apple Inc." },
    { '1. symbol': "GOOGL", '2. name': "Alphabet Inc." },
    { '1. symbol': "AMZN", '2. name': "Amazon.com Inc." },
    { '1. symbol': "MSFT", '2. name': "Microsoft Corp." },
    { '1. symbol': "TSLA", '2. name': "Tesla Inc." },
    { '1. symbol': "META", '2. name': "Meta Platforms Inc." },
    { '1. symbol': "NVDA", '2. name': "NVIDIA Corp." },
    { '1. symbol': "NFLX", '2. name': "Netflix Inc." },
    { '1. symbol': "INTC", '2. name': "Intel Corp." },
    { '1. symbol': "AMD", '2. name': "Advanced Micro Devices Inc." }
];

async function fetchStockSuggestions(query) {
    if (!query) {
        // Hide dropdown if there's no query
        document.getElementById('suggestionsDropdown').style.display = 'none';
        return;
    }

    // this dynamic search only works when api limit is not reached, otherwise only top 10 fixed suggestions will be shown
    const url = `https://www.alphavantage.co/query?function=SYMBOL_SEARCH&keywords=${query}&apikey=${apiKey}`;

    try {
        const response = await fetch(url);
        const data = await response.json();
        
        if (data.bestMatches) {
            displaySuggestions(data.bestMatches.slice(0, 10)); // Show up to 10 suggestions
        }
    } catch (error) {
        console.error('Error fetching stock suggestions:', error);
    }
}

// Display suggestions in the dropdown
function displaySuggestions(suggestions) {
    const dropdown = document.getElementById('suggestionsDropdown');
    dropdown.innerHTML = '';

    suggestions.forEach(suggestion => {
        const item = document.createElement('div');
        item.className = 'suggestion-item';
        item.textContent = `${suggestion['1. symbol']} - ${suggestion['2. name']}`;
        item.addEventListener('click', () => {
            document.getElementById('stockSymbolInput').value = suggestion['1. symbol'];
            document.getElementById('identifier').value = suggestion['1. symbol']; 
            document.getElementById('title').textContent = suggestion['2. name'];
            dropdown.style.display = 'none';
            fetchStockData(suggestion['1. symbol']);
            // Fetch data for the selected stock
        });
        dropdown.appendChild(item);
    });

    dropdown.style.display = 'block';
}

document.addEventListener('DOMContentLoaded', () => {
    // fetchStockData('MSFT'); // Initial load with MSFT data

    const symbolInput = document.getElementById('stockSymbolInput');

    // Show fixed suggestions when input is first clicked and is empty
    symbolInput.addEventListener('focus', () => {
        if (symbolInput.value.trim() === '') {
            displaySuggestions(fixedStockSuggestions);
        }
    });

    // Fetch live suggestions as user types
    symbolInput.addEventListener('input', (event) => {
        const query = event.target.value.trim();
        
        // If the query is not empty, fetch live suggestions
        if (query) {
            fetchStockSuggestions(query);
        } else {
            // If the input is cleared, show fixed suggestions again
            displaySuggestions(fixedStockSuggestions);
        }
    });

    document.addEventListener('click', (event) => {
        const dropdown = document.getElementById('suggestionsDropdown');
        if (!event.target.closest('.stock-search-bar')) {
            dropdown.style.display = 'none';
        }
    });
});
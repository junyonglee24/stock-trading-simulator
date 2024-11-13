const apiKey = 'demo';
const CACHE_EXPIRY_TIME = 5 * 60 * 1000; 
let apiLimitReached = false; // Track if API limit is reached
let intra_day_data = null;

async function fetchStockData(symbol) {
    const cacheKey = `stockData_${symbol}`;
    const cachedData = localStorage.getItem(cacheKey);

    // Check cached data for expiry or if API limit is reached
    if (cachedData) {
        const { intra_day_data, timestamp } = JSON.parse(cachedData);

        if (Date.now() - timestamp < CACHE_EXPIRY_TIME || apiLimitReached) {
            displayStockData(intra_day_data);
            return;
        }
    }

    // If API limit is not reached, proceed to fetch new data
    const intra_day_url = `https://www.alphavantage.co/query?function=TIME_SERIES_INTRADAY&symbol=${symbol}&interval=5min&outputsize=full&apikey=${apiKey}`;
    const daily_url = `https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=${symbol}&apikey=${apiKey}`;

    try {
        // intra-day data
        const intra_day_response = await fetch(intra_day_url);
        const intra_day_data = await intra_day_response.json();

        if (intra_day_data['Time Series (5min)']) {
            // Save the data and timestamp in localStorage
            localStorage.setItem(cacheKey, JSON.stringify({
                intra_day_data,
                timestamp: Date.now()
            }));

            localStorage.setItem('currentStockData', JSON.stringify(intra_day_data)); // Store globally

            displayStockData(intra_day_data);
            // pass data to calculate est total for trade form
            // Cal_estTotal(intra_day_data);
            // pass data to render candlestick chart
            candleStick(intra_day_data);

        } else if (intra_day_data['Note'] && intra_day_data['Note'].includes('API call frequency')) {
            // API limit reached, set flag and use cached data
            console.warn('API call limit reached. Using last cached data.');
            apiLimitReached = true;
            resetApiLimitFlag(); 

            // Fallback to last cached data if available
            if (cachedData) {
                const { intra_day_data } = JSON.parse(cachedData);
                displayStockData(intra_day_data); // Fallback to last cached data
            } else {
                displayFallbackData();
            }
        } else {
            // Parse and cache the CSV data
            const data = parseCSV(csvText);
            localStorage.setItem(cacheKey, JSON.stringify({
                data,
                timestamp: Date.now()
            }));

            displayStockData(data);
        }
        //  elif (data['Note'] && data['Note'].includes('API call frequency')) {
        //     // API limit reached, set flag and use cached data
        //     console.warn('API call limit reached. Using last cached data.');
        //     apiLimitReached = true; // Set limit flag
        //     if (cachedData) {
        //         const { data } = JSON.parse(cachedData);
        //         displayStockData(data); // Fallback to last cached data
        //     } else {
        //         displayFallbackData();
        //     }
        // } else {
        //     throw new Error('Data not available for the specified symbol.');
        // }
    } catch (error) {
        console.error('Fetch error:', error);
        displayFallbackData();
    }

    try {
            // daily day data for rsi
            const daily_response = await fetch(daily_url);
            const daily_data = await daily_response.json();

            // pass data to render candlestick chart
            rsiChart(daily_data);
    } catch (error) {
        console.error('Fetch error:', error);
        // displayFallbackData();
    }
}

function displayStockData(data) {
    const latestTime = Object.keys(data['Time Series (5min)'])[0];
    const stockInfo = data['Time Series (5min)'][latestTime];

    document.getElementById('stockPrice').textContent = `$${parseFloat(stockInfo['1. open']).toFixed(2)}`;
    document.getElementById('open').textContent = `$${parseFloat(stockInfo['1. open']).toFixed(2)}`;
    document.getElementById('low').textContent = `$${parseFloat(stockInfo['3. low']).toFixed(2)}`;
    document.getElementById('high').textContent = `$${parseFloat(stockInfo['2. high']).toFixed(2)}`;
    document.getElementById('close').textContent = `$${parseFloat(stockInfo['4. close']).toFixed(2)}`;
    document.getElementById('volume').textContent = `${parseInt(stockInfo['5. volume']).toLocaleString()}`;

    const formattedTime = new Date(latestTime).toLocaleString('en-US', {
        year: 'numeric', month: 'short', day: 'numeric',
        hour: '2-digit', minute: '2-digit', second: '2-digit'
    });
    document.getElementById('lastUpdated').textContent = `Last Updated: ${formattedTime}`;
}

function displayFallbackData() {
    document.getElementById('stockPrice').textContent = 'Data unavailable';
    document.getElementById('open').textContent = 'N/A';
    document.getElementById('low').textContent = 'N/A';
    document.getElementById('high').textContent = 'N/A';
    document.getElementById('close').textContent = 'N/A';
    document.getElementById('volume').textContent = 'N/A';
    document.getElementById('lastUpdated').textContent = 'Failed to fetch data';
}

document.addEventListener('DOMContentLoaded', () => {
    fetchStockData('MSFT');
});

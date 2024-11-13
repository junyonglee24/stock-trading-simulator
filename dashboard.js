const apiKey = 'demo';
const CACHE_EXPIRY_TIME = 5 * 60 * 1000; // 5 minutes
const API_RETRY_INTERVAL = 10 * 60 * 1000; // 10 minutes
let apiLimitReached = false; 

function isCacheValid(timestamp) {
    return Date.now() - timestamp < CACHE_EXPIRY_TIME;
}

function resetApiLimitFlag() {
    setTimeout(() => { apiLimitReached = false; }, API_RETRY_INTERVAL);
}

async function fetchStockData(symbol) {
    const cacheKey = `stockData_${symbol}`;
    const cachedData = localStorage.getItem(cacheKey);

    if (cachedData) {
        const { dailyData, timestamp } = JSON.parse(cachedData);

        if (isCacheValid(timestamp) || apiLimitReached) {
            populateNasdaqChart(dailyData);
            return;
        }
    }

    const daily_url = `https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=${symbol}&apikey=${apiKey}&datatype=csv`;

    try {
        const dailyResponse = await fetch(daily_url);
        const dailyCsv = await dailyResponse.text();

        // Check for API limit notice in the response
        if (dailyCsv.includes("API call frequency")) {
            console.warn('API call limit reached. Using last cached data.');
            apiLimitReached = true;
            resetApiLimitFlag();

            if (cachedData) {
                const { dailyData } = JSON.parse(cachedData);
                populateNasdaqChart(dailyData);
            } else {
                displayFallbackData();
            }
        } else {
            const dailyData = parseCSV(dailyCsv);

            localStorage.setItem(cacheKey, JSON.stringify({
                dailyData,
                timestamp: Date.now()
            }));

            populateNasdaqChart(dailyData);
        }
    } catch (error) {
        console.error('Fetch error:', error);
        displayFallbackData();
    }
}

// Helper function to parse CSV into JSON
function parseCSV(csvText) {
    const lines = csvText.trim().split('\n');
    const headers = lines[0].split(',');
    const data = {};

    for (let i = 1; i < lines.length; i++) {
        const row = lines[i].split(',');

        if (row.length === headers.length) {
            const timestamp = row[0];
            data[timestamp] = {
                '1. open': row[1],
                '2. high': row[2],
                '3. low': row[3],
                '4. close': row[4],
                '5. volume': row[5],
            };
        }
    }

    return data;
}

// Function to populate NASDAQ chart with data
function populateNasdaqChart(data) {
    const formattedData = Object.keys(data).map(date => {
        const [year, month, day] = date.split('-').map(Number);
        return {
            time: { year, month, day },
            value: parseFloat(data[date]['4. close']),
        };
    }).sort((a, b) => new Date(a.time.year, a.time.month - 1, a.time.day) - 
                      new Date(b.time.year, b.time.month - 1, b.time.day));

    nasdaqSeries.setData(formattedData);
}

function displayFallbackData() {
    document.getElementById('stockPrice').textContent = 'Data unavailable';
    document.getElementById('open').textContent = 'N/A';
    document.getElementById('price').value = 'N/A';
    document.getElementById('low').textContent = 'N/A';
    document.getElementById('high').textContent = 'N/A';
    document.getElementById('close').textContent = 'N/A';
    document.getElementById('volume').textContent = 'N/A';
    document.getElementById('lastUpdated').textContent = 'Failed to fetch data';
}

// Set up chart on page load
let nasdaqSeries;

document.addEventListener('DOMContentLoaded', function() {
    const nasdaqContainer = document.getElementById('index-chart');
    const nasdaqChart = LightweightCharts.createChart(nasdaqContainer, {
        width: nasdaqContainer.clientWidth,
        height: 300,
        layout: {
            background: { color: '#121212' },
            textColor: 'rgba(255, 255, 255, 0.9)',
        },
        grid: {
            vertLines: { visible: false },
            horzLines: { visible: false },
        },
        crosshair: { mode: LightweightCharts.CrosshairMode.Normal },
        rightPriceScale: { borderVisible: false },
        timeScale: { borderVisible: false, timeVisible: true, secondsVisible: false },
    });

    const resizeNasdaqChart = () => {
        requestAnimationFrame(() => nasdaqChart.resize(nasdaqContainer.clientWidth, 300));
    };
    window.addEventListener('resize', resizeNasdaqChart);

    nasdaqSeries = nasdaqChart.addLineSeries({
        color: '#4CAF50',
        lineWidth: 2,
    });

    // Fetch initial stock data
    fetchStockData('MSFT');
});

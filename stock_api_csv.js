const apiKey = 'VP7B7ZX1LLI60I77';
const CACHE_EXPIRY_TIME = 5 * 60 * 1000; // Cache expires in 5 minutes
const API_RETRY_INTERVAL = 10 * 60 * 1000; // Retry API calls after 10 minutes if limit was reached
let apiLimitReached = false;
let portfolio_dailyData;
window.intradayData = null;
// let dailyData;

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
        const { intradayData, dailyData, timestamp } = JSON.parse(cachedData);

        if (isCacheValid(timestamp) || apiLimitReached) {
            if (symbol === 'NASDAQ') {
                populateNasdaqChart(dailyData);
            }
            // Use cached data if valid
            if (document.getElementById('holdings-stocks')){
                return { intradayData, dailyData };
            }
            else if (document.getElementsByClassName('wallet').length > 0){
                return intradayData;
            }
            console.log(intradayData);
            displayStockData(intradayData);
            candleStick(intradayData);
            rsiChart(dailyData);
            window.intradayData = intradayData;

            const dataReadyEvent = new Event('intradayDataReady');
            document.dispatchEvent(dataReadyEvent);
            return;
        }
    }

    const intra_day_url = `https://www.alphavantage.co/query?function=TIME_SERIES_INTRADAY&symbol=${symbol}&interval=5min&apikey=${apiKey}&outputsize=full&datatype=csv`;
    const daily_url = `https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=${symbol}&apikey=${apiKey}&outputsize=full&datatype=csv`;

    try {
        const [intraDayResponse, dailyResponse] = await Promise.all([fetch(intra_day_url), fetch(daily_url)]);
        const intraDayCsv = await intraDayResponse.text();
        const dailyCsv = await dailyResponse.text();

        // Check for "Invalid API call" error message in the response
        if (intraDayCsv.includes("Invalid API call") || dailyCsv.includes("Invalid API call")) {
            console.error('Invalid stock symbol');
            alert('Invalid stock symbol entered. Please try again.');  // Show alert to user
            return;
        }

        // Check for API limit notice in the response
        if (intraDayCsv.includes("API call frequency") || dailyCsv.includes("API call frequency")) {
            console.warn('API call limit reached. Using last cached data.');
            apiLimitReached = true;
            resetApiLimitFlag();

            // Fallback to last cached data if available
            if (cachedData) {
                const { intradayData, dailyData, timestamp } = JSON.parse(cachedData);
                if (symbol === 'NASDAQ') {
                    populateNasdaqChart(dailyData);
                }
                if (document.getElementById('holdings-stocks')){
                    // console.log(intradayData);
                    return { intradayData, dailyData };
                }
                else if (document.getElementsByClassName('wallet').length > 0){
                    return intradayData;
                }
                console.log(intradayData);
                displayStockData(intradayData);
                candleStick(intradayData);
                rsiChart(dailyData);
                window.intradayData = intradayData;

                const dataReadyEvent = new Event('intradayDataReady');
                document.dispatchEvent(dataReadyEvent);
            } else {
                displayFallbackData();
            }
        } else {
            // Parse and cache the CSV data
            intradayData = parseCSV(intraDayCsv);
            dailyData = parseCSV(dailyCsv);

            localStorage.setItem(cacheKey, JSON.stringify({
                intradayData,
                dailyData,
                timestamp: Date.now()
            }));
            if (symbol === 'NASDAQ') {
                populateNasdaqChart(dailyData);
            }
            if (document.getElementById('holdings-stocks')){
                return { intradayData, dailyData };
            }
            else if (document.getElementsByClassName('wallet').length > 0){
                return intradayData;
            }
            console.log(intradayData);
            displayStockData(intradayData);
            candleStick(intradayData);
            rsiChart(dailyData);
            window.intradayData = intradayData;

            const dataReadyEvent = new Event('intradayDataReady');
            document.dispatchEvent(dataReadyEvent);
        }
    } catch (error) {
        console.error('Fetch error:', error);
        displayFallbackData();
    }
}

// Helper function to parse CSV into JSON
function parseCSV(csvText) {
    const lines = csvText.split('\n');
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

    // Sort data by date and keep only the latest 360 entries
    const sortedDates = Object.keys(data).sort((a, b) => new Date(b) - new Date(a));
    const latest360Data = sortedDates.slice(0, 360).reduce((result, date) => {
        result[date] = data[date];
        return result;
    }, {});

    return latest360Data;
}

function displayStockData(data) {
    console.log(data);
    const latestTime = Object.keys(data)[0];
    const stockInfo = data[latestTime];

    document.getElementById('stock-title').textContent = document.getElementById("stockSymbolInput").value.trim().split(" ")[0] || "AAPL";
    document.getElementById('stockPrice').textContent = `$${parseFloat(stockInfo['1. open']).toFixed(2)}`;
    document.getElementById('open').textContent = `$${parseFloat(stockInfo['1. open']).toFixed(2)}`;
    document.getElementById('price').value = `$${parseFloat(stockInfo['1. open']).toFixed(2)}`;
    document.getElementById('low').textContent = `$${parseFloat(stockInfo['3. low']).toFixed(2)}`;
    document.getElementById('high').textContent = `$${parseFloat(stockInfo['2. high']).toFixed(2)}`;
    document.getElementById('close').textContent = `$${parseFloat(stockInfo['4. close']).toFixed(2)}`;
    document.getElementById('volume').textContent = `${parseInt(stockInfo['5. volume']).toLocaleString()}`;

    // Calculate and display price change and percentage change
    const currentPrice = parseFloat(stockInfo['1. open']);
    const previousClose = parseFloat(stockInfo['4. close']);

    const priceChange = currentPrice - previousClose;
    const percentageChange = (priceChange / previousClose) * 100;

    document.getElementById('priceChange').textContent = `${priceChange >= 0 ? '+' : ''}${priceChange.toFixed(2)}`;
    document.getElementById('priceChange').style.color = priceChange >= 0 ? '#00e676' : '#ff1744';

    document.getElementById('percentageChange').textContent = `${percentageChange >= 0 ? '+' : ''}${percentageChange.toFixed(2)}%`;
    document.getElementById('percentageChange').style.color = percentageChange >= 0 ? '#00e676' : '#ff1744';

    document.getElementById('stockPrice').style.color = percentageChange >= 0 ? '#00e676' : '#ff1744';

    const formattedTime = new Date(latestTime).toLocaleString('en-US', {
        year: 'numeric', month: 'short', day: 'numeric',
        hour: '2-digit', minute: '2-digit', second: '2-digit'
    });
    // document.getElementById('lastUpdated').textContent = `Last Updated: ${formattedTime}`;
}

function displayFallbackData() {
    document.getElementById('stockPrice').textContent = 'Data unavailable';
    document.getElementById('open').textContent = 'N/A';
    document.getElementById('price').value = 'N/A';
    document.getElementById('low').textContent = 'N/A';
    document.getElementById('high').textContent = 'N/A';
    document.getElementById('close').textContent = 'N/A';
    document.getElementById('volume').textContent = 'N/A';
    // document.getElementById('lastUpdated').textContent = 'Failed to fetch data';
}

function cal_stock_stats(portfolio) {
    let totalChangeValue = 0;
    let initialTotalValue = 0;
    aggregatedTotalGainLoss = 0;

    portfolio.forEach(stockData => {
        const identifier = stockData.stock;
        const intraDayData = stockData.intra_day_Data;
        const quantity = stockData.quantity;

        // Calculate timestamps and get the latest and opening data
        const timestamps = Object.keys(intraDayData).sort((a, b) => new Date(b) - new Date(a));
        const latestData = intraDayData[timestamps[0]];
        const openingData = intraDayData[timestamps[timestamps.length - 1]];

        const closePrice = parseFloat(latestData["4. close"]);
        const openPrice = parseFloat(openingData["1. open"]);

        // Calculate individual stock change values
        const changeValue = (closePrice - openPrice) * quantity;
        const totalGainLoss = (closePrice * quantity) - (openPrice * quantity);
        const todayChangePercentage = ((closePrice - openPrice) / openPrice) * 100;

        // Update the individual stock change in the DOM if element exists
        const currentPriceElement = document.getElementById(`current-price-${identifier}`);
        if (currentPriceElement) {
            currentPriceElement.textContent = `$${closePrice.toFixed(2)}`;
        }

        const todayChangeElement = document.getElementById(`today-change-${identifier}`);
        if (todayChangeElement) {
            todayChangeElement.textContent = `$${changeValue.toFixed(2)} (${todayChangePercentage.toFixed(2)}%)`;
            todayChangeElement.style.color = changeValue >= 0 ? '#00e676' : '#ff1744';
        }

        const totalGainLossElement = document.getElementById(`total-gain-loss-${identifier}`);
        if (totalGainLossElement) {
            totalGainLossElement.textContent = `$${totalGainLoss.toFixed(2)}`;
            totalGainLossElement.style.color = totalGainLoss >= 0 ? '#00e676' : '#ff1744';
        }

        // Accumulate values for total portfolio change
        totalChangeValue += changeValue;
        initialTotalValue += openPrice * quantity;

        // Accumulate total gain/loss for the portfolio
        aggregatedTotalGainLoss += totalGainLoss;
    });

    // Calculate the overall portfolio change percentage
    const portfolioChangePercentage = (totalChangeValue / initialTotalValue) * 100;

    // Update the total portfolio change if element exists
    const todayChangeElement = document.getElementById('today_change');
    if (todayChangeElement) {
        todayChangeElement.textContent = `$${totalChangeValue.toFixed(2)} (${portfolioChangePercentage.toFixed(2)}%)`;
        todayChangeElement.style.color = totalChangeValue >= 0 ? '#00e676' : '#ff1744';
    }

    // Update the aggregated total gain/loss if element exists
    const holdingsTotalGainLossElement = document.getElementById('holdings-total-gain-loss');
    if (holdingsTotalGainLossElement) {
        holdingsTotalGainLossElement.textContent = `$${aggregatedTotalGainLoss.toFixed(2)}`;
        holdingsTotalGainLossElement.style.color = aggregatedTotalGainLoss >= 0 ? '#00e676' : '#ff1744';
    }
}

function fetchStockDescription(identifier) {
    const stock_desc_url = `https://www.alphavantage.co/query?function=SYMBOL_SEARCH&keywords=${identifier}&apikey=${apiKey}`;

    return fetch(stock_desc_url)
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (data.bestMatches && data.bestMatches.length > 0) {
                // Get the name (description) of the first matching stock
                return data.bestMatches[0]['2. name'];
            } else {
                return 'Description not available';
            }
        })
        .catch(error => {
            console.error(`Error fetching description for ${identifier}:`, error);
            return 'Description not available';
        });
}

function updateStockDescriptions(portfolio) {
    portfolio.forEach(stockData => {
        const identifier = stockData.stock;

        // Fetch the description based on the stock identifier
        fetchStockDescription(identifier)
            .then(description => {
                // Update the DOM with the fetched stock description
                document.getElementById(`stock-descriptions-${identifier}`).textContent = description;
            })
            .catch(error => {
                console.error(`Error fetching description for ${identifier}:`, error);
                document.getElementById(`stock-descriptions-${identifier}`).textContent = 'Description not available';
            });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementsByClassName('stock-info').length > 0) {
        document.getElementById('stock-title').textContent = "MSFT";
        fetchStockData('AAPL'); // Initial load with MSFT data
    } else if (document.getElementById('holdings-stocks')) {
        if (!portfolio_data.identifier || portfolio_data.identifier.length === 0){
            // fallback for rsi chart
            weighted_rsiChart([]);
        }
        else{
            const totalPortfolioValue = portfolio_data.stockvalue.reduce((sum, total) => sum + parseFloat(total), 0);

            // console.log(totalPortfolioValue);
            // for aggregated RSI chart
            const rsi_portfolioPromises = portfolio_data.identifier.map((symbol, index) => {
            const quantity = parseFloat(portfolio_data.quantities[index]);
            const stockValue = parseFloat(portfolio_data.stockvalue[index]);
            const weight = stockValue / totalPortfolioValue;

            // Fetch data and return a promise that resolves to the structured stock object
            return fetchStockData(symbol).then((data) => ({
                stock: symbol,
                    dailyData: data.dailyData,
                weight: weight
            }));
        });
    
            // for calculating stock holdings statistics
            const portfolioPromises = portfolio_data.identifier.map((symbol, index) => {
                const quantity = parseFloat(portfolio_data.quantities[index]);
                const stockValue = parseFloat(portfolio_data.stockvalue[index]);
    
                // Fetch data and return a promise that resolves to the structured stock object
                return fetchStockData(symbol).then((data) => {
                    console.log('Data returned from fetchStockData:', data);
                    return {
                        stock: symbol,
                        intra_day_Data: data.intradayData,
                        quantity: quantity
                    };
                });
                
            });

        // Wait until all fetches complete and the portfolio is fully populated
        Promise.all(rsi_portfolioPromises).then((rsi_portfolio) => {
            weighted_rsiChart(rsi_portfolio);
        });
    
        Promise.all(portfolioPromises).then((portfolio) => {
                cal_stock_stats(portfolio);
                updateStockDescriptions(portfolio);
        });
        }
    }
    else if (document.getElementsByClassName('wallet').length > 0){
        const portfolioPromises = portfolio_data.identifier.map((symbol, index) => {
            const quantity = parseFloat(portfolio_data.quantities[index]);
            const stockValue = parseFloat(portfolio_data.stockvalue[index]);

            // Fetch data and return a promise that resolves to the structured stock object
            return fetchStockData(symbol).then((data) => ({
                stock: symbol,
                intra_day_Data: data,
                quantity: quantity
            }));
        });

        Promise.all(portfolioPromises).then((portfolio) => {
            cal_stock_stats(portfolio);
        });
    }
});

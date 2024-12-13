let candleSeries;
let rsiSeries;

document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementsByClassName('stock-info').length > 0){
    // Set up candlestick chart
        const container = document.getElementById('candlestick-chart');
        const chart = LightweightCharts.createChart(container, {
            width: container.clientWidth,
            height: 500,
            layout: {
                background: { color: '#121212' },
                textColor: 'rgba(255, 255, 255, 0.9)',
            },
            grid: {
                vertLines: { visible: false },
                horzLines: { visible: false },
            },
            crosshair: {
                mode: LightweightCharts.CrosshairMode.Normal,
            },
            rightPriceScale: { borderVisible: false },
            timeScale: {
                borderVisible: false,
                timeVisible: true,
                secondsVisible: false,
            },
        });

        // Handle resizing
        window.addEventListener('resize', () => {
            chart.resize(container.clientWidth, 500);
        });

        // Create candlestick series
        candleSeries = chart.addCandlestickSeries({
            upColor: '#00ff00',
            downColor: '#ff0000',
            borderDownColor: 'rgba(255, 144, 0, 1)',
            borderUpColor: 'rgba(255, 144, 0, 1)',
            wickDownColor: 'rgba(255, 144, 0, 1)',
            wickUpColor: 'rgba(255, 144, 0, 1)',
        });

        // Set up RSI chart
        const rsiContainer = document.getElementById('rsi-chart');
        const rsiChart = LightweightCharts.createChart(rsiContainer, {
            width: rsiContainer.clientWidth,
            height: 200,
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

        window.addEventListener('resize', () => {
            rsiChart.resize(rsiContainer.clientWidth, 200);
        });

        // Create RSI series
        rsiSeries = rsiChart.addLineSeries({
            color: '#ffb400',
            lineWidth: 2,
        });
    }
    else if (document.getElementById('holdings-stocks')) {
            // Set up RSI chart
        const rsiContainer = document.getElementById('rsi-chart');
        const rsiChart = LightweightCharts.createChart(rsiContainer, {
            width: rsiContainer.clientWidth,
            height: rsiContainer.clientHeight,
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

        window.addEventListener('resize', () => {
            rsiChart.resize(rsiContainer.clientWidth, 200);
        });

        // Create RSI series
        rsiSeries = rsiChart.addLineSeries({
            color: '#fb00ff',
            lineWidth: 2,
        });
    }
});


// Candlestick Chart Data Loader
function candleStick(cachedData) {
        
    if (!cachedData) {
        console.error("Invalid intraday data format received for chart rendering.");
        return;
    }

    const formattedData = Object.keys(cachedData).map(timestamp => {
        const stockInfo = cachedData[timestamp];
        const unixTimestamp = Math.floor(new Date(timestamp).getTime() / 1000);

        return {
            time: unixTimestamp,
            open: parseFloat(stockInfo['1. open']),
            high: parseFloat(stockInfo['2. high']),
            low: parseFloat(stockInfo['3. low']),
            close: parseFloat(stockInfo['4. close']),
        };
    });

    // Sort data by timestamp
    formattedData.sort((a, b) => a.time - b.time);

    // Set the data for the candlestick chart
    candleSeries.setData(formattedData);
}

// RSI Chart Data Loader
function rsiChart(cachedData) {
    const dailyData = cachedData;

    if (!dailyData) {
        console.error("Invalid daily data format received for RSI chart rendering.");
        return;
    }

    // Calculate RSI data points from daily data
    const rsiData = calculateRSI(dailyData);
    
    // Set the data for the RSI line chart
    rsiSeries.setData(rsiData);
}

// Helper function to calculate RSI
function calculateRSI(dailyData, period = 14) {
    const closingPrices = Object.keys(dailyData)
        .map(date => ({
            time: date,
            close: parseFloat(dailyData[date]['4. close'])
        }))
        .reverse(); // Ensure data is in ascending order

    const rsiData = [];
    let gains = 0;
    let losses = 0;

    // Initialize gains and losses for the first period
    for (let i = 1; i <= period; i++) {
        const change = closingPrices[i].close - closingPrices[i - 1].close;
        if (change > 0) gains += change;
        else losses -= change;
    }

    let avgGain = gains / period;
    let avgLoss = losses / period;
    let rs, rsi;

    if (avgLoss === 0) {
        rs = Infinity;
        rsi = 100;
    } else {
        rs = avgGain / avgLoss;
        rsi = 100 - 100 / (1 + rs);
    }

    rsiData.push({ time: closingPrices[period].time, value: rsi });

    // Calculate RSI for each subsequent day
    for (let i = period + 1; i < closingPrices.length; i++) {
        const change = closingPrices[i].close - closingPrices[i - 1].close;
        avgGain = change > 0 ? (avgGain * (period - 1) + change) / period : (avgGain * (period - 1)) / period;
        avgLoss = change < 0 ? (avgLoss * (period - 1) - change) / period : (avgLoss * (period - 1)) / period;

        if (avgLoss === 0) {
            rs = Infinity;
            rsi = 100;
        } else {
            rs = avgGain / avgLoss;
            rsi = 100 - 100 / (1 + rs);
        }

        // Only add valid RSI values
        if (!isNaN(rsi) && isFinite(rsi)) {
            rsiData.push({ time: closingPrices[i].time, value: rsi });
        } else {
            console.warn(`Invalid RSI value at time ${closingPrices[i].time}: ${rsi}`);
        }
    }

    return rsiData;
}

function getLast360DaysData(dailyData) {
    // Convert the dailyData object into an array of entries [date, data]
    const dailyDataArray = Object.entries(dailyData);

    // Sort by date in ascending order
    dailyDataArray.sort((a, b) => new Date(a[0]) - new Date(b[0]));

    // Take only the last 30 entries
    const last30DaysData = dailyDataArray.slice(-360);

    // Convert back to an object
    const last30DaysDataObject = Object.fromEntries(last30DaysData);

    return last30DaysDataObject;
}


function calculateWeightedRSI(portfolio, period = 14) {
    const weightedRSI = [];

    // Calculate individual RSIs for each stock in the portfolio
    const individualRSIs = portfolio.map(stock => {
        // Get only the last 30 days of dailyData
        const last30DaysData = getLast360DaysData(stock.dailyData);

        return {
            stock: stock.stock,
            rsiData: calculateRSI(last30DaysData, period),
            weight: stock.weight
        };
    });

    // Assuming all stocks have the same number of days of data
    const dataLength = individualRSIs[0].rsiData.length;

    // Calculate weighted RSI for each day
    for (let i = 0; i < dataLength; i++) {
        let weightedRSIValue = 0;

        individualRSIs.forEach(individual => {
            const individualRSIValue = individual.rsiData[i]?.value;
            if (isNaN(individualRSIValue) || !isFinite(individualRSIValue)) {
                console.warn(`Invalid RSI value for stock ${individual.stock} at index ${i}`);
            } else {
                weightedRSIValue += individualRSIValue * individual.weight;
            }
        });

        if (!isNaN(weightedRSIValue) && isFinite(weightedRSIValue)) {
            weightedRSI.push({
                time: individualRSIs[0].rsiData[i].time,
                value: weightedRSIValue
            });
        }
    }

    return weightedRSI;
}

function weighted_rsiChart(portfolio) {
    if (!rsiSeries) {
        console.error('rsiSeries is not initialized');
        return;
    }
    const weightedRSI = calculateWeightedRSI(portfolio);

    const sortedData = weightedRSI.sort((a, b) => new Date(a.time) - new Date(b.time));

    rsiSeries.setData(sortedData);
}

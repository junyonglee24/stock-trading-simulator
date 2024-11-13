let candleSeries;
let rsiSeries;

document.addEventListener('DOMContentLoaded', function() {
    // setup candlestick chart
    const container = document.getElementById('candlestick-chart');
    var chart = LightweightCharts.createChart(document.getElementById('candlestick-chart'), {
        width: container.clientWidth,
        height: 500,
        layout: {
            background: {color:'#121212'},
            textColor: 'rgba(255, 255, 255, 0.9)',
        },
        grid: {
            vertLines: {
                // color: '#121212',
                visible: false,
            },
            horzLines: {
                // color: '#121212',
                visible: false,
            },
        },
        crosshair: {
            mode: LightweightCharts.CrosshairMode.Normal,
        },
        rightpriceScale: {
            // borderColor: '#121212',
            borderVisible: false,
        },
        timeScale: {
            // borderColor: '#121212',
            borderVisible: false,
            timeVisible: true,
            secondsVisible: false,
        },
    });

    window.addEventListener('resize', () => {
        chart.resize(container.clientWidth, 500);
    });

    candleSeries = chart.addCandlestickSeries({
        upColor: '#00ff00',
        downColor: '#ff0000',
        borderDownColor: 'rgba(255, 144, 0, 1)',
        borderUpColor: 'rgba(255, 144, 0, 1)',
        wickDownColor: 'rgba(255, 144, 0, 1)',
        wickUpColor: 'rgba(255, 144, 0, 1)',
    });

     // setup rsi chart
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
 
     rsiSeries = rsiChart.addLineSeries({
         color: '#ffb400',
         lineWidth: 2,
     });
});

function calculateRSI(dailyData, period = 14) {
    const rsiData = [];
    const closingPrices = Object.keys(dailyData).map(date => ({
        date,
        close: parseFloat(dailyData[date]['4. close']),
    })).reverse(); // Reverse to go from oldest to newest

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
    let rs = avgGain / avgLoss;
    let rsi = 100 - 100 / (1 + rs);

    rsiData.push({ time: closingPrices[period].date, value: rsi });

    // Calculate RSI for each subsequent day
    for (let i = period + 1; i < closingPrices.length; i++) {
        const change = closingPrices[i].close - closingPrices[i - 1].close;
        if (change > 0) {
            avgGain = (avgGain * (period - 1) + change) / period;
            avgLoss = (avgLoss * (period - 1)) / period;
        } else {
            avgGain = (avgGain * (period - 1)) / period;
            avgLoss = (avgLoss * (period - 1) - change) / period;
        }
        rs = avgGain / avgLoss;
        rsi = 100 - 100 / (1 + rs);
        rsiData.push({ time: closingPrices[i].date, value: rsi });
    }

    return rsiData;
}

function candleStick(data) {
    if (!data || !data['Time Series (5min)']) {
        console.error("Invalid data format received for chart rendering.");
        return;
    }

    const timeSeries = data['Time Series (5min)'];
    const formattedData = Object.keys(timeSeries).map(timestamp => {
        const stockInfo = timeSeries[timestamp];

        const unixTimestamp = Math.floor(new Date(timestamp).getTime() / 1000);

        return {
            time: unixTimestamp,
            open: parseFloat(stockInfo['1. open']),
            high: parseFloat(stockInfo['2. high']),
            low: parseFloat(stockInfo['3. low']),
            close: parseFloat(stockInfo['4. close'])
        };
    });

    formattedData.sort((a, b) => a.time - b.time);

    candleSeries.setData(formattedData);
}

function rsiChart(data) {

    if (!data || !data['Time Series (Daily)']) {
        console.error("Invalid data format received for chart rendering.");
        return;
    }

    const timeSeries = data['Time Series (Daily)'];
    const rsiData = calculateRSI(timeSeries);
    rsiSeries.setData(rsiData);

}
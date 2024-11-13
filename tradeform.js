document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity');
    const quantityButtons = document.querySelectorAll('.quantity-btn');

    quantityButtons.forEach(button => {
        button.addEventListener('click', () => {
            const value = button.getAttribute('data-value');
            quantityInput.value = value;
        });
    });
});

function toggleTradeForm(type) {
    const buyButton = document.getElementById("buyButton");
    const sellButton = document.getElementById("sellButton");
    const tradeButton = document.querySelector(".trade-button");

    if (type === "sell") {
        sellButton.classList.add("active");
        buyButton.classList.remove("active");
        tradeButton.textContent = "Sell MSFT";
    } else {
        buyButton.classList.add("active");
        sellButton.classList.remove("active");
        tradeButton.textContent = "Buy MSFT";
    }
}

function Cal_transFee(intradayData) {

    const latestTime = Object.keys(intradayData)[0];
    const stockInfo = intradayData[latestTime];

    const stockPrice = parseFloat(stockInfo['1. open']);

    const qty = parseFloat(document.getElementById("quantity").value);

    // Calculate transaction fee: (stock price x quantity) * 0.01
    const trans_fee = (stockPrice * qty) * 0.01;

    document.getElementById("transactionFees").innerText = "$" + trans_fee.toFixed(2);

    return trans_fee;
}

function Cal_estTotal(intradayData){
    // const stockData = JSON.parse(localStorage.getItem('currentStockData')); // Retrieve stock data

    // calculate est total using (stock price x quantity) + transaction fee  **when quantity is set
    const latestTime = Object.keys(intradayData)[0];
    const stockInfo = intradayData[latestTime];
    
    const stockPrice = parseFloat(stockInfo['1. open']).toFixed(2);
    console.log(stockPrice);
    const qty = document.getElementById("quantity").value;
    var trans_fee = Cal_transFee(intradayData); // change if needed

    const est_total = (stockPrice * qty) + trans_fee;

    document.getElementById("estimatedTotal").innerText = "$" + parseFloat(est_total).toFixed(2);
}

function Cal_estLoss(intradayData){
    // calculate est loss using |(stock price - stop price)| x quantity **if stop price is set
    // const stockData = JSON.parse(localStorage.getItem('currentStockData')); // Retrieve stock data

    const latestTime = Object.keys(intradayData)[0];
    const stockInfo = intradayData[latestTime];
    
    const stockPrice = parseFloat(stockInfo['1. open']).toFixed(2);
    const qty = document.getElementById("quantity").value;
    const stop_price = document.getElementById("stopPrice").value;

    const est_loss = Math.abs(stockPrice - stop_price) * qty;
    
    document.getElementById("estimatedLoss").innerText = "$" + parseFloat(est_loss).toFixed(2);
}

document.addEventListener('intradayDataReady', function() {

    var quantity_node = document.getElementById("quantity");
    var stop_price_check_node = document.getElementById("stop-price-checkbox");
    var stop_price_node = document.getElementById("stopPrice");

    quantity_node.addEventListener("input", function() {
        Cal_estTotal(window.intradayData);
        // Cal_transFee(intradayData);
        if (stop_price_check_node.checked) {
            Cal_estLoss(window.intradayData);
        }},
        false);

    stop_price_check_node.addEventListener("change", () => {
    if (stop_price_check_node.checked) {
        Cal_estLoss(window.intradayData);
    } else {
        document.getElementById("estimatedLoss").innerText = "$0.00";
    }
    });

    stop_price_node.addEventListener("input", () => {
        if (stop_price_check_node.checked) {
            Cal_estLoss(window.intradayData);
        } else {
            document.getElementById("estimatedLoss").innerText = "$0.00";
        }
        });
})
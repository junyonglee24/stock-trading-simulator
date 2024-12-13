document.addEventListener("DOMContentLoaded", () => {
    // Trade form submission event
    const tradeForm = document.getElementById('tradeForm');
    tradeForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData(tradeForm);

        fetch('/stock-trading-simulator/php/account/transactions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => alert(data))
        .catch(error => console.error('Error:', error));
    });
});


    function toggleTradeForm(action) {
        const buyButton = document.getElementById('buyButton');
        const sellButton = document.getElementById('sellButton');
        const buySellAction = document.getElementById('buySellAction');
        const tradeButton = document.getElementById('trade-button');

        if (action === 'buy') {
            buyButton.classList.add('active');
            sellButton.classList.remove('active');
            buySellAction.value = "Buy";
            tradeButton.textContent = "Buy";

        } else if (action === 'sell') {
            sellButton.classList.add('active');
            buyButton.classList.remove('active');
            buySellAction.value = "Sell";
            tradeButton.textContent = "Sell";
        }
    }


let popup = document.getElementById("tradePopup");

function showtradeformAlert(message) {
    document.getElementById("alertMessage").innerText = message;
    
    document.getElementById("trade-form-alert").style.display = "flex";
}

function closeAlert() {
    document.getElementById("trade-form-alert").style.display = "none";
}

function openPopup(identifier, buySellAction, tradeType, price) {

    const quantityInput = document.getElementById('quantity').value;

    // Validation for quantity
    if (quantityInput <= 0) {
        showtradeformAlert("Please trade at least 1 stock!");
        return;
    }
    if (/^0[0-9]+$/.test(quantityInput)) {
        showtradeformAlert("Please only enter number with no leading zeros!");
        return;
    }

    document.getElementById('popup-identifier').innerText = document.getElementById('identifier').value;
    document.getElementById('popup-action').innerText = document.getElementById('buySellAction').value;
    document.getElementById('popup-trade-type').innerText = document.getElementById('orderType').value;
    document.getElementById('popup-price').innerText = document.getElementById('price').value;
    document.getElementById('popup-quantity').innerText = document.getElementById('quantity').value;
    
    const priceString = document.getElementById('price').value;
    const priceNumber = parseFloat(priceString.replace('$', ''));
    document.getElementById('popup-totalprice').innerText = "Total Price: $" + (priceNumber * 1.01 *document.getElementById('quantity').value).toFixed(2);
    document.getElementById('popup-transaction').innerText = "$" +(priceNumber * 0.01 *document.getElementById('quantity').value).toFixed(2);

    popup.classList.add("open-popup");

    return false;
}

function closePopup() {
    popup.classList.remove("open-popup");
}

function confirmTrade() {
    popup.classList.remove("open-popup");
    document.getElementById('tradeForm').submit();
}
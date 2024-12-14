<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📈 Investment Profile - NASDAQ-Trade</title>
    <link rel="stylesheet" href="/stock-trading-simulator/assets/css/signup.css">
</head>
<body>
    <div class="container">
        <div class="left-side">
            <div class="logo">
                <div class="logo-box">
                    <h1>📈 NASDAQ-Trade</h1>
                    <p>I E 4 7 2 7</p>
                </div>
            </div>
            <div class="description">
                <p>Join us and start trading today!</p>
                <div class="features">
                    <button>Stocks Analysis/Charting</button>
                    <button>Stocks News/Feeds</button>
                    <button>Virtual Wallet for Simulation</button>
                    <button>Trading history</button>
                </div>
            </div>
            <p class="footer-text">Project for IE4727</p>
        </div>
        
        <div class="right-side">
            <h2>Investment Profile</h2>
            <form class="investment-profile-form">

                <!-- Investment Knowledge Level -->
                <label for="knowledge-level">Investment Knowledge Level</label>
                <select id="knowledge-level" name="knowledge_level" required>
                    <option value="">Select your level</option>
                    <option value="Beginner">Beginner</option>
                    <option value="Intermediate">Intermediate</option>
                    <option value="Advanced">Advanced</option>
                </select>

                <!-- Trading Experience -->
                <label for="trading-experience">Trading Experience</label>
                <select id="trading-experience" name="trading_experience" required>
                    <option value="">Select your experience</option>
                    <option value="Stocks">Stocks</option>
                    <option value="Forex">Forex</option>
                    <option value="Cryptocurrencies">Cryptocurrencies</option>
                    <option value="Options">Options</option>
                    <option value="Commodities">Commodities</option>
                    <option value="None">No Prior Experience</option>
                </select>

                <!-- Investment Goals -->
                <label for="investment-goals">Investment Goals</label>
                <select id="investment-goals" name="investment_goals" required>
                    <option value="">Select your goal</option>
                    <option value="Long-term growth">Long-term growth</option>
                    <option value="Short-term gains">Short-term gains</option>
                    <option value="Income generation">Income generation</option>
                </select>

                <!-- Risk Tolerance -->
                <label for="risk-tolerance">Risk Tolerance</label>
                <select id="risk-tolerance" name="risk_tolerance" required>
                    <option value="">Select your risk tolerance</option>
                    <option value="Conservative">Conservative</option>
                    <option value="Moderate">Moderate</option>
                    <option value="Aggressive">Aggressive</option>
                </select>

                <!-- Preferred Trading Instruments -->
                <label for="trading-instruments">Preferred Trading Instruments</label>
                <select id="trading-instruments" name="trading_instruments" multiple required>
                    <option value="Stocks">Stocks</option>
                    <option value="Forex">Forex</option>
                    <option value="Options">Options</option>
                    <option value="Cryptocurrencies">Cryptocurrencies</option>
                    <option value="Commodities">Commodities</option>
                </select>

                <button type="submit" class="submit-profile">Submit Profile</button>
            </form>
        </div>
        <div class="popup" id="popup">
            <h1>✅</h1>
            <h2>Thank You!</h2>
            <p>Your account has been registered! You will be directed to the Login page shortly.</p>
            <button type="button" onclick="window.location.href='/stock-trading-simulator/php/auth/login.php'; return false;">Okay</button>
        </div>
    </div>

<script>
    document.querySelector('.investment-profile-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevents form from submitting immediately

    // Check if all required fields are filled
    const knowledgeLevel = document.getElementById("knowledge-level").value;
    const tradingExperience = document.getElementById("trading-experience").value;
    const investmentGoals = document.getElementById("investment-goals").value;
    const riskTolerance = document.getElementById("risk-tolerance").value;
    const tradingInstruments = document.getElementById("trading-instruments").selectedOptions;

    // Ensure multiple select field has at least one option selected
    const selectedInstruments = Array.from(tradingInstruments).map(option => option.value);
    
    // Validate required fields
    if (knowledgeLevel && tradingExperience && investmentGoals && riskTolerance && selectedInstruments.length > 0) {
        // If all fields are valid, show the popup and set up a redirect
        openPopup();
    } 
});

function openPopup() {
    let popup = document.getElementById("popup");
    popup.classList.add("open-popup");
}

</script>

</body>
</html>

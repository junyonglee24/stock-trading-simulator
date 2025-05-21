# Stock Trading Simulator
![search   trade](https://github.com/user-attachments/assets/463870aa-bcbf-428c-af27-a40c5e203568)

## About
The website is a trading simulator that replicates a real trading platform, allowing beginner traders to learn about trading in a risk-free environment. A free [NASDAQ stock market API](https://www.alphavantage.co/documentation/) is integrated to fetch live and historical stock data and is updated periodically throughout the day.  

## Features
1. **Dashboard:** View financial news and performance summaries of NASDAQ stocks for in-depth analysis.  

2. **Search & Trade:** Search individual stock and analyse detailed candlestick and RSI charts with scrollable time frame. Users can start buying and selling stocks with the virtual balance allocated to them upon creating an account.  

3. **Wallet:** Top up virtual balance upon exhausted and view trading history for further analysis.  

4. **Portfolio:** Review performance summaries of holdings with RSI chart to consider subsequent trades.  

## Navigation
.  
├── assets  
├──  ├── js  
├──  ├── css    
├── php          
├── sql&nbsp;&nbsp;&nbsp;&nbsp;# database  
├── web pages&nbsp;&nbsp;&nbsp;&nbsp;# screenshots  
├── index.php&nbsp;&nbsp;&nbsp;&nbsp;# entry point  
└── README.md  

## Launch Website
This website is hosted on local server [XAMPP](https://www.apachefriends.org/index.html).  

After XAMPP is started on laptop:  

1. Git clone this repo to the local directory `/xampp/htdocs/`.  
   
2. Create a database named `tradingdg13` at `http://localhost/phpmyadmin`. Then, create tables by importing the SQL file `sql/tradingdg13.sql`.  
   
3. Access the website at `http://localhost/stock-trading-simulator`.    

## Contributors
- [@jy158654](https://github.com/jy158654)
- [@clim172](https://github.com/clim172)

use tradingdg13;

insert into users values
  (1, "joshi37y", "Passw0rd928!", "Josh", "Hill","josh2987@gmail.com"),
  (2, "beth3921", "Passw0rd928!", "Beth", "Chang","beth374278@gmail.com"),
  (3, "masie92839", "Passw0rd928!", "Masie", "Chia","masie924384@gmail.com");

  insert into virtualwallet values
  (1, "joshi37y", 500000),
  (2, "beth3921", 500000),
  (3, "masie92839", 500000);

  insert into userportfolio values
  (1, "joshi37y", "MSFT", 50, 555555),
  (2, "beth3921", "AMGEN", 100, 666666),
  (3, "masie92839", "META", 12, 777777);

insert into transactions values
  (1, "joshi37y", "MSFT", "Buy", "Market Open", 228.0, 2, 456.0, "2024-09-13 19:25:25"),
  (2, "joshi37y", "APPL", "Buy", "Market Open", 175.0, 5, 875.0, "2024-09-16 19:27:26"),
  (3, "joshi37y", "TSLA", "Buy", "Market Open", 300.0, 3, 900.0, "2024-09-16 19:27:54");
CREATE TABLE 'order_histories' (
    'history_id' int AUTO_INCREMENT,
    'user_id' int,
    'created'  datetime,
    'updated' datetime,
    primary key('history_id')
);

CREATE TABLE 'order_details'(
    'detail_id' int AUTO_INCREMENT,
    'history_id' int,
    'item_id' int,
    'amount' int,
    'purchased_price' int,
    'created'  datetime,
    'updated' datetime,
    primary key('detail_id')
);
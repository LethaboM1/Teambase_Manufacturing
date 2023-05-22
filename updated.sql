
alter table users_tbl add column updated_at datetime default CURRENT_TIMESTAMP() on update CURRENT_TIMESTAMP();
alter table users_tbl add column created_at datetime default CURRENT_TIMESTAMP();

create table manufacture_products (
    id bigint primary key auto_increment,
    code varchar(100),
    description varchar(100),
    unit_measure varchar(100),
    has_recipe boolean default 0,
    updated_at datetime default CURRENT_TIMESTAMP() on update CURRENT_TIMESTAMP(),
    created_at datetime default CURRENT_TIMESTAMP()
);


alter table manufacture_products add column updated_at datetime default CURRENT_TIMESTAMP() on update CURRENT_TIMESTAMP();
alter table manufacture_products add column created_at datetime default CURRENT_TIMESTAMP();


/* Done */

create table manufacture_product_transactions (
    id bigint primary key auto_increment,
    product_id bigint,
    type varchar(20),
    type_id bigint,
    qty decimal(10,2),
    comment text,
    user_id bigint,
    updated_at datetime default CURRENT_TIMESTAMP() on update CURRENT_TIMESTAMP(),
    created_at datetime default CURRENT_TIMESTAMP()
);
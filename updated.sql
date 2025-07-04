
alter table users_tbl add column updated_at datetime default CURRENT_TIMESTAMP() on update CURRENT_TIMESTAMP();
alter table users_tbl add column created_at datetime default CURRENT_TIMESTAMP();

create table manufacture_products (
    id bigint primary key auto_increment,
    code varchar(100),
    description varchar(100),
    unit_measure varchar(100),
    has_recipe boolean default 0,
    active boolean default 1,
    updated_at datetime default CURRENT_TIMESTAMP() on update CURRENT_TIMESTAMP(),
    created_at datetime default CURRENT_TIMESTAMP()
);


alter table manufacture_products add column updated_at datetime default CURRENT_TIMESTAMP() on update CURRENT_TIMESTAMP();
alter table manufacture_products add column created_at datetime default CURRENT_TIMESTAMP();


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

create table manufacture_product_recipe (
    id bigint primary key auto_increment,
    product_id bigint,
    product_add_id bigint,
    qty decimal(10,2),
    updated_at datetime default CURRENT_TIMESTAMP() on update CURRENT_TIMESTAMP(),
    created_at datetime default CURRENT_TIMESTAMP()
); 

alter table manufacture_products add column active boolean default 1 after has_recipe;

create table manufacture_jobcards (
    id bigint primary key auto_increment,
    jobcard_number varchar(100),
    contractor varchar(100),
    site_number varchar(100),
    contact_person varchar(100),
    delivery boolean default 0,
    delivery_address text,
    notes text,
    status varchar(20),
    updated_at datetime default CURRENT_TIMESTAMP() on update CURRENT_TIMESTAMP(),
    created_at datetime default CURRENT_TIMESTAMP()
    -- Open / Onhold / Busy / Dispatch / Delivery / Completed / Canceled
);

create table manufacture_jobcard_products (
    id bigint primary key auto_increment,
    job_id bigint,
    batch_id bigint,
    product_id bigint,
    qty decimal(10,2),
    updated_at datetime default CURRENT_TIMESTAMP() on update CURRENT_TIMESTAMP(),
    created_at datetime default CURRENT_TIMESTAMP()
);

create table manufacture_batch (
    id bigint primary key auto_increment,
    batch_number varchar(100),
    product_id bigint,
    qty decimal(10,2),
    notes text,
    status varchar(20),
    updated_at datetime default CURRENT_TIMESTAMP() on update CURRENT_TIMESTAMP(),
    created_at datetime default CURRENT_TIMESTAMP()
);

create table manufacture_batch_recipe (
    id bigint,
    batch_id bigint,
    product_id bigint,
    qty decimal(10,2),
    updated_at datetime default CURRENT_TIMESTAMP() on update CURRENT_TIMESTAMP(),
    created_at datetime default CURRENT_TIMESTAMP()
);

create table manufacture_batch_labs (
    id bigint primary key auto_increment,
    batch_id bigint,
    date datetime,
    description text,
    quantity decimal(10,2),
    results text,
    updated_at datetime default CURRENT_TIMESTAMP() on update CURRENT_TIMESTAMP(),
    created_at datetime default CURRENT_TIMESTAMP()
);

alter table manufacture_jobcards add column contact_number varchar(100) after contact_person;



alter table manufacture_jobcards add column updated_at datetime default CURRENT_TIMESTAMP() on update CURRENT_TIMESTAMP();
alter table manufacture_jobcards add column created_at datetime default CURRENT_TIMESTAMP();
alter table manufacture_jobcards add column delivery boolean default 0;

alter table manufacture_jobcard_products add column updated_at datetime default CURRENT_TIMESTAMP() on update CURRENT_TIMESTAMP();
alter table manufacture_jobcard_products add column created_at datetime default CURRENT_TIMESTAMP();

alter table manufacture_batch add column updated_at datetime default CURRENT_TIMESTAMP() on update CURRENT_TIMESTAMP();
alter table manufacture_batch add column created_at datetime default CURRENT_TIMESTAMP();

alter table manufacture_batch_recipe add column updated_at datetime default CURRENT_TIMESTAMP() on update CURRENT_TIMESTAMP();
alter table manufacture_batch_recipe add column created_at datetime default CURRENT_TIMESTAMP();

alter table manufacture_batch_labs add column updated_at datetime default CURRENT_TIMESTAMP() on update CURRENT_TIMESTAMP();
alter table manufacture_batch_labs add column created_at datetime default CURRENT_TIMESTAMP();

alter table manufacture_product_transactions change column qty qty decimal(10,3);
alter table manufacture_product_recipe change column qty qty decimal(10,3);



alter table manufacture_jobcard_products change column qty qty decimal(10,3);

create table manufacture_settings (
    batch_number bigint default 0 not null,
    batch_prefix varchar(25) default '#',
    batch_digits int default 5
);

alter table manufacture_settings add column updated_at datetime default CURRENT_TIMESTAMP() on update CURRENT_TIMESTAMP();
alter table manufacture_settings add column created_at datetime default CURRENT_TIMESTAMP();

alter table manufacture_products add column lab_test varchar(100) after has_recipe;
/* Done */

create table manufacture_jobcard_product_dispatches(
    id bigint primary key auto_increment,
    dispatch_number varchar(100),
    manufacture_jobcard_product_id bigint,
    batch_id bigint,
    qty decimal(10,3),
    updated_at datetime default CURRENT_TIMESTAMP() on update CURRENT_TIMESTAMP(),
    created_at datetime default CURRENT_TIMESTAMP()
);

alter table manufacture_jobcard_products add column filled boolean default 0 after qty;
alter table manufacture_jobcard_product_dispatches add column dispatch_number varchar(100) after id;


alter table manufacture_jobcard_product_dispatches add column driver_id bigint  default 0 after dispatch_number;
alter table manufacture_jobcard_product_dispatches add column status varchar(25)  default 'Ready' after dispatch_number;

alter table manufacture_settings add column dispatch_number bigint default 0 after batch_digits;
alter table manufacture_settings add column dispatch_prefix varchar(25) default 'D#' after dispatch_number;
alter table manufacture_settings add column dispatch_digits integer default 5 after dispatch_prefix;

alter table manufacture_settings add column jobcard_number bigint default 0 after batch_digits;
alter table manufacture_settings add column jobcard_prefix varchar(25) default 'D#' after jobcard_number;
alter table manufacture_settings add column jobcard_digits integer default 5 after jobcard_prefix;


alter table manufacture_products add column updated_at datetime default CURRENT_TIMESTAMP() on update CURRENT_TIMESTAMP();
alter table manufacture_products add column created_at datetime default CURRENT_TIMESTAMP();

alter table manufacture_products add column filled boolean default 0;


alter table manufacture_jobcard_product_dispatches add column reference varchar(100) after dispatch_number;
alter table manufacture_jobcard_product_dispatches add column haulier_code varchar(100) after reference;
alter table manufacture_jobcard_product_dispatches add column weight_in decimal(10,3) after reference;
alter table manufacture_jobcard_product_dispatches add column weight_in_datetime datetime after weight_in;
alter table manufacture_jobcard_product_dispatches add column weight_out decimal(10,3) after weight_in_datetime;
alter table manufacture_jobcard_product_dispatches add column weight_out_datetime datetime after weight_out;

alter table manufacture_jobcard_product_dispatches change column driver_id plant_id bigint;
alter table manufacture_jobcard_product_dispatches add column registration_number varchar(100) after plant_id;

alter table manufacture_jobcard_product_dispatches add column comment text after haulier_code;



alter table plants_tbl add column updated_at datetime default CURRENT_TIMESTAMP() on update CURRENT_TIMESTAMP();
alter table plants_tbl add column created_at datetime default CURRENT_TIMESTAMP();

create table manufacture_suppliers (
    id bigint primary key auto_increment,
    name varchar(100),
    contact_name  varchar(100),
    contact_number varchar(15),
    email varchar(255),
    vat_number varchar(30),
    address text,
    
);


alter table manufacture_suppliers add column updated_at datetime default CURRENT_TIMESTAMP() on update CURRENT_TIMESTAMP();
alter table manufacture_suppliers add column updated_at datetime default CURRENT_TIMESTAMP() on update CURRENT_TIMESTAMP();

alter table manufacture_suppliers change column id id bigint auto_increment;

alter table manufacture_product_transactions add column reference_number varchar(100);

alter table manufacture_product_transactions change column reference_number reference_number varchar(100) after type_id;
alter table manufacture_product_transactions add column weight_in decimal(10,3) after reference_number;
alter table manufacture_product_transactions add column weight_in_datetime datetime after weight_in;
alter table manufacture_product_transactions add column weight_in_user bigint after weight_in_datetime;
alter table manufacture_product_transactions add column weight_out decimal(10,3) after reference_number;
alter table manufacture_product_transactions add column weight_out_datetime datetime after weight_out;
alter table manufacture_product_transactions add column weight_out_user bigint after weight_out_datetime;
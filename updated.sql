
alter table users_tbl add column updated_at datetime default CURRENT_TIMESTAMP() on update CURRENT_TIMESTAMP();
alter table users_tbl add column created_at datetime default CURRENT_TIMESTAMP();
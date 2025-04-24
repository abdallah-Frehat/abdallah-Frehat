declare
    today date := sysdate;
    tomorrow today%type;
begin
    dbms_output.put_line('hello world');

    tomorrow := today + 1;

    dbms_output.put_line('today is: ' || to_char(today, 'dd-mon-yyyy'));
    dbms_output.put_line('tomorrow is: ' || to_char(tomorrow, 'dd-mon-yyyy'));
end;



declare
    my_date date := sysdate;
    v_last_day date;
begin
    v_last_day := last_day(my_date);

    dbms_output.put_line('today''s date: ' || to_char(my_date, 'month dd, yyyy'));
    dbms_output.put_line('last day of this month: ' || to_char(v_last_day, 'dd-mon-yyyy'));
end;

declare
    my_date date := sysdate;
    new_date date;
    months_between_dates number;
begin
    new_date := my_date + 45;
    months_between_dates := months_between(new_date, my_date);

    dbms_output.put_line('today: ' || to_char(my_date, 'month dd, yyyy'));
    dbms_output.put_line('date after 45 days: ' || to_char(new_date, 'month dd, yyyy'));
    dbms_output.put_line('months between: ' || months_between_dates);
end;



create table countries (
    country_name varchar2(50),
    median_age number(6, 2)
);

insert into countries values ('jordan', 48.4);
insert into countries values ('egypt', 38.5);
insert into countries values ('emrates', 47.8);



declare
    cursor country_cursor is
        select country_name, median_age from countries where country_name = 'jordan';

    v_country countries.country_name%type;
    v_age countries.median_age%type;
begin
    open country_cursor;
    fetch country_cursor into v_country, v_age;

    dbms_output.put_line('the median age in ' || v_country || ' is ' || v_age || '.');

    close country_cursor;
end;
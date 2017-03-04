

$file = fopen("other.csv","r");
while(! feof($file))
{
$a = fgetcsv($file);
print_r($a[0]);
}
fclose($file);
echo __DIR__;
echo '</br>';
echo "123";
?>

r_table_id
c_table_id   foreign
sku
entity_id



CREATE TABLE test1(
id INT NOT NULL AUTO_INCREMENT,
c1 VARCHAR(64) NOT NULL,
c2 VARCHAR(64) NOT NULL,
c3 INT NOT NULL,
PRIMARY KEY ( id )
);
INSERT INTO MyTable  (PriKey, Description)        SELECT ForeignKey, Description   FROM SomeView;

INSERT INTO db1_name(field1,field2) SELECT field1,field2 FROM db2_name

insert  into relation_id  (r_table_id , c_table_id ,sku, "",)  SELECT   c_table_id  FROM compatibility where year ='{$year}' AND make = '{$make}'  AND  model = '{$model}')


insert into  relaton_id  (,c_table_id,ku1,, ) SELECT   c_table_id     FROM compatibility where year ='1977' AND make = 'Porsche'  AND  model = '911' limit 0,1;

INSERT INTO relation_id (r_table_id, c_table_id,sku,entity_id) VALUES ('', '123','test','');


data[] = SELECT   c_table_id  FROM compatibility where year ='1983' AND make = 'Subaru'  AND  model = 'DL' limit 0,1;
insert into  relaton_id  (,c_table_id,ku1,, )


INSERT INTO relation_id ( r_table_id,c_table_id,sku,entity_id) VALUES (99999,9,abc,9996);


INSERT INTO relation_id ( r_table_id,c_table_id,sku,entity_id) VALUES (default,110,'a',110);


INSERT INTO relation_id ( r_table_id,c_table_id,sku,entity_id) VALUES (default,12,'aca',null);


r_table_id
r_table_id
c_table_id
c_table_id
sku
sku
entity_id
entity_id

1
73988040
1977
Porsche
911


1983
Subaru
DL
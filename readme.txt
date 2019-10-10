Requirements:
PHP 7.3
MySql
Symfony 4.3 (setup instraller)
Git

Instralation process:
1) Install default AMP, can be XAMPP, WAMP (env must provide PHP/MySQL in versions that are listed above) 
or you can configure it manualy through virtual box with Ubuntu for example. 
2) Create manualy database: district, can be accessed for root user without permission if you wish
3) Clone git repository to your working folder

git clone https://github.com/dwor-kin/district.git

setup application:
1) First setup your db connection, which is located in .env file in main directory
	DATABASE_URL=mysql://root@127.0.0.1:3306/district

If you set DB name sa district and didn't provide any password, you can leave it without any changes.

2) Provide libraries
   composer install  
3) Migrate database 
   php bin/console d:m:m
4) Run application 
   After installed symfony setuper, you can execute it by typing bash command.
   Execute project by typing: symfony serve
   
   Now application should be available from browser by: localhost:8000/district 

------
Application functionality:
1) Migrate from command  
   You can migrate data from two different sites: 
   http://www.gdansk.pl/matarnia
   http://www.bip.krakow.pl/?bip_id=1&mmi=10501

   just paste:
   php bin/console app:migrate-district Gdansk
   
   or
   php bin/console app:migrate-district Krak�w

2) Application functionality
   - show data (available from localhost:8000/district or localhost:8000/district/list site
   - filter data trough any field (district name, area min max, population min, max, city name) - executed by apply filter button
   - add new district (set with flag is_default = false)
   - update district (selected from grid)
   - remove district (selected from grid)
   - sort by column (by clicking head of column from the grid)
   - Manage DB operations:
     - Import from external sites - the same operation like migration from command - but executes all defined sites (attention: all old defaults entries will be purged)
     - Purge imported - delete all defaults entries
     - Purge inserted - delete all entries added by user

enjoy!
		
	
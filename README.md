# Gain.POS
Laravel + vue based POS application!


## How to run the application

* Rename .env.example file to .env inside your project root or (command mac terminal cd your project root directory and run - `cp .env.example .env` you get .env file) and fill the database information.
* Open the console and cd your project root directory
* Run `composer install` or `php composer.phar install` (if vendor directory not available )
* Run `php artisan key:generate`
* Now open project directory in root find the `.env` file. In the .env file fill in the `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` options to match the credentials of the database you just created. This will allow us to run migrations and seed the database in the next step.
* Run `php artisan migrate`
* Run `php artisan db:demo` to run seeders.

##Login admin user/pass:
* login url: https://pos.dev.gainhq.com/login
* Email: admin@demo.com
* Pass: 123456

## Complete settings
* Got to setting manu
* Application setting: Set all the fields as your choice.
* Email setting: Set application name as your choice.
  
  - Set your Email address as email [ N.B: access your email address for this application from your email if needed.].
  
  - Select email driver. If smtp:- 
  
    - Here, we set driver as smtp, host for gmail as smtp.googlemail.com, smtp port for gmail as 587 and encryption method to ssl. Make sure you have replaced your Gmail username and password. Next, you need to make some changes in your Gmail settings. Login to your Google account and click on My Account. Once you are on My Account page then click on Sign-in & Security. On the next page, scroll down to the bottom and you will find �Allow less secure apps� settings. Set it to ON.
    
    - type email host name.[ example : for gmail `smtp.gmail.com` ]
  
    - Type the port number :- [ example : for gmail port `587` ]
    
    - Type password :- password of that email with is used as the email address.

    - Select Encryption type :- Port `465` (SSL required), Port `587` (TLS required).
                                                
**N.B:-** Be carefull about `Email Setting` which is must needed for sending auto email notification. If you don't complete the `Email Setting` some features will not work properly.

Other settings set as you need.

# Bull-E

This repository contains the code for our transversal project.  
This project is about a website with a private messagery and a forum (though we didn't have time to develop it), to put in contact professional (either psys or lawyers)
and people being or having been victim of cyber bullying.

To use this repository, do not forget to install composer. (The **doc** is available [here](https://getcomposer.org/))  
and to run the command
>composer install  

or 
>php composer.phar install

(depending of whether you installed it locally or ,globally.)

also, run the command :
>npm install

, to get all dependencies to the project.

afterward, do not forget to include a private.yml file with the following lines in it (and yes, the .sql file is still 
not online, but he shall come afterwards.)
>db_config:  
     name: _your db name_  
     host: _your host_  
     user: _the username to connect to your db_  
     pass: _the password of your db_
     
>mail_config:  
   host:     'smtp.example.com;smtp2.example.com'  
   _(put smtp.gmail.com if you have a gmail adress, for example)_  
   adress:   _your server email adress_  
   password: _its password_  

Finally, create an _access.log_, a _message.log_ AND a _security.log_ file in the logs directory.

It was made by _Eugénie POUPET_, _Eddy TONG_ and _Jonathan SIMONNEY_. Please contact 
one of us at _eugenie.poupet@supinternet.fr_, _eddy.tong@supinternet.fr_ or 
_jonathan.simonney@supinternet.fr_ if you experiment any problem with this repository.

possible amelioration :  
#### refactorisation of the code, mainly!  
- for example, there are three .html.twig extremely similar :  
 -connected/victime/successAbandon ;  
 -connected/account.html.twig, and  
 -connected/inbox.html.twig
 
  These shall be put to extend a single template, to avoid code repetition.
 
 - There are also many useless routes.
 - The errors are absolutely not handled (which makes our UX completely awful).

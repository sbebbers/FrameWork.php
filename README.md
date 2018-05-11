# FrameWork.php #
### Developed by Shaun Bebbington and Linden Bryon ###
#### Special thanks to Aaron Zvimba for his help, advice and support ####

FrameWork.php started out life as a simple and quite messy OO MVC framework in order to mentor a junior PHP developer whilst I was working at Rushcliff Ltd in Burton-Upon-Trent, Staffordshire. The idea was to link a controller to each page, and have a model per controller, so that the controller class would act as a middle-man between the front-end view and the database as necessary.

I also thought it necessary to allow numerous file extensions on the page uri alias, so for instance, in the skeleton provided, one might use any of the following:

* http://framework.local/home
* http://framework.local/home.php
* http://framework.local/home.aspx
* http://framework.local/home.htm
* http://framework.local/home.html

The idea was to obscure that it was a PHP framework, and to send the page URLs with the .aspx file extension to our pen testers. I don't know if this was a good idea, however there were no issues that came back requiring attention even after we let them know that it was a PHP framework. One sundry benefit to this approach is that I've accidentially provided a way for websites to migrate to this framework from another tech stack without losing any SEO ratings that they have. For instance, if your categories.aspx page ranked highly, you are still able to use this extension on a site here. I handle this by using canonical tags in the header by default.

I never had the chance to finish the framework to a satisfactory degree at Rushcliff Ltd, so although it has been deployed on a live product, it remains imperfect. I was allowed to continue developing it on leaving [Rushcliff] so here it is. Still imperfect but always improving. This is not something for those hipster and artisan developer-types, it is fairly simple and fairly stupid.

Linden has set up on GitHub so I have invited him as a collaborator on this project. Linden has expertise with MySQL, PHP 5/7, HTML 5/CSS 3, JavaScript (jQuery) and some JavaScript frameworks, and is a very keen and highly recommended developer.

If you have any comments then please feel free to contact me.

Shaun Bebbington.
Twitter: @YearOfCodes
--
Scanned with SonarCloud, see -> 
https://sonarcloud.io/api/project_badges/measure?project=coffee&metric=alert_status

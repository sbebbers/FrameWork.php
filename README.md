# FrameWork.php
### Developed by Shaun Bebbington and Linden Bryon

FrameWork.php started out life as a simple and quite messy OO MVC framework in order to mentor a junior PHP developer whilst I was working at Rushcliff Ltd in Burton-Upon-Trent, Staffordshire. The idea was to link a controller to each page, and have a model per controller, so that the controller class would act as a middle-man between the front-end view and the database as necessary.

I also thought it necessary to allow numerous file extensions on the page uri alias, so for instance, in the skeleton provided, one might use any of the following:

* http://framework.local/home
* http://framework.local/home.php
* http://framework.local/home.aspx
* http://framework.local/home.htm
* http://framework.local/home.html

The idea was to obscure that it was a PHP framework, and to send the page URLs with the .aspx file extension to our pen testers. I don't know if this was a good idea, however there were no issues that came back requiring attention.

I never had the chance to finish the framework to a satisfactory degree at Rushcliff Ltd, so although it has been deployed on a live product, it remains imperfect. I was allowed to continue developing it on leaving so here it is. Still imperfect but slightly improved. This is not something for those hipster and artisan developer-types, it is fairly simple and fairly stupid.

I'd like to thank Linden for his help and support whilst at Rushcliff Ltd, I learnt a lot from him and became a better developer as a result.

If you have any comments then please feel free to contact me.

Shaun Bebbington.
Twitter: @yearofcodes
--
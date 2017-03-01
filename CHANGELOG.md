# Project FrameWork.php MVC v0.1.0

### This is a fairly simple and fairly stupid MCV framework for PHP 7. Simply set up your path in the allowed segments, the name of your path will point to the /application/view folder - you must place a view file with the same name as the allowed path with a .phtml extension, so in the example home in the $allowedSegments resource in the FrameworkCore.php will load the home.phtml view in /application/view

#### File Structure:
```
	../application
		--> controller
			\ For all of the custom controller methods and logic
		--> core
			\ Contains the main engine room for looking up page requests,
				and loading the right controller and view
		--> model
			\ main Db connection and model logic
		--> library
			\ Helper functions and other such things
		--> view
			\ HTML (with embedded PHP) views
			--> partial
				\ For partial HTML views such as headers, footers and menus
```
--
	@version	0.1.0
	@date		February 2016 - current date
	@author		Shaun Bebbington (version 0.0.1 to current)
				&& Linden Bryon (version 0.0.1 to 0.0.7)
	@changes	as of 2016-02-19:
 				Added partial views in seperate directory
 				Added model and controller core so all other model and controller classes
 				extend this as required
 				Controller core file will include the library class for all helper functions
 				Default to Bootstrap 3.3.x and jQuery 2.2.x
 				Application folder moved outside of the public-facing directory
 				Admin area added for future use and expansion
 				Fixed: Chooses correct segment (may need changing according to URL structure)
	@changes as of 2016-05-12:
 				Fixed: Session Cookie handling correctly for HTTP and HTTPS connections
	@changes as of 2016-06-02:
 				Forgot to say that the TimeAndDate class in the library has been fixed
	@changes as of 2016-06-09:
 				Removed much of the functionality to the Framework Core class
 				Added global methods for file routing to the application folder
 				You can now relate controllers to views; if a view has a related controller,
 				the controller is automatically required and an instance of which is initiated
 				in the $this->controller object set in the core
 				Partial views are set in the core under $this->partial['partial-instance'], to use
 				this, use require_once($this->partial['partial-instance']); in the PHP view
	@changes as of 2016-09-12:
 				Can now utilise ZF-style view variables if using $this->view->objName in the
 				controllers; this becomes $this->objName in the PHP/HTML view files, meaning
 				you no longer require $this->controller->instance->view->objName - note that
 				anything set in the view object will overwrite any other objects of the same
 				name
	@changes as of 2016-09-14:
 				Now has specific flash message for session cookie, use $this->setFlashMessage($k, $v);
 				in the controllers where $k is the key and $v is the value, and to check if the
 				flash message has been set, use $this->getFlashMessage($k), which will check if
 				the key is set. In the view, use $this->flash->$k to get the value.
	@changes as of 2017-01-06:
 				Namespaces beginning to be added for more flexibility, renaming project and other
 				tidying up to improve the stuff; as of this date all updates and improvements will
 				be handled by Shaun Bebbington
	@changes as of 2017-01-11:
 				Minor refactoring to the methods in the library
	@changes as of 2017-01-12:
 				Some more refactoring and an initial commit to GitHub, the main thing is to move the
 				global helpers to a file in the application/core directory, therefore making this
 				file quite light-weight in PHP terms. All of the page handling stuff is handled in
 				the Core directly now; Need to add in the HTML builder and other things, as well
 				as work on the modelling.
	@changes as of 2017-01-13:
				I've added a convertSnakeCase function to the library so that database keys may be
				used in a PHP-friendly way such as camelCase. In my test, sub_header in the database
				becomes subHeader in my PHP view - much easier to remember.
	@changes as of 2017-01-23:
				I've added in the HTML builder with an example of how to use on the home page view
				in application/view/home.phtml. This will allow you to generate much of your HTML
				using PHP but will also produce the HTML in minimised form; as PHP doesn't care so
				much about white spaces, you can align your PHP in nice way without affecting the
				outcome of the HTML (still generated as minimised regardless of how you format your
				PHP).
				Some of the SEO companies that I have worked with like minimised HTML as it might
				affect the page load time by a few milliseconds if you're lucky.
				I've also started to use the strict PHP 7 types in parts of the project, it should
				be easy enough to fix this for PHP 5.x version, but from now on I'll be using the
				latest PHP standards, so consider it now a PHP 7 framework.
	@changes as of 2017-02-06:
				Added in a path router to the core - for instance, if you have in your view folder
				/admin/login.phtml and /admin/dashboard.phtml, you will now be able to use this
				path as a URI request. Setting up the $allowedSegments and $pageControllers has not
				changed in the Framework Core, as it will build the URI string and route to it.
				I've also improved the logic for working out which view to load in the loadPage()
				method of the core; now it will check if the file exists as well as a check to see
				if the URI request is allowed.
	@changes as of 2017-02-08:
				I've been in contact with my former colleague Linden Bryon (@Lindenbryon) who has
				agreed to collaborate on this project which will help in its development in the
				PHP side as well as the JavaScript and jQuery stuff. Happy Days.
	@changes as of 2017-03-01:
				I've changed the way that the library encryption and decryption works to remove
				some PHP functions that have been deprecated in PHP 7.1.x. In addition, there is
				a file handling function in the library which will post your data to a restful
				service without the cURL dependencies.

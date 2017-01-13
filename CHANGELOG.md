# Project FrameWork.php MCV v0.0.8
# =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-

### This is a fairly simple and fairly stupid MCV framework for PHP 5.4 or later Simply set up your path in the allowed segments, it will now allow all file extensions by default (.aspx, .jsp, .html etc...) - this creates a canonical tag thing to include in the head.

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
	@version	0.0.8
	@date		February 2016 - January 2017
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

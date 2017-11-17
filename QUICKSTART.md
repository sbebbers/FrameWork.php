# Project FrameWork.php MVC v0.1.4-RC3 #

### Quick start guide - updated 2017-11-10 ###

You will already have a home page and 404 page to play with; these files are located in the `./path/to/application/view` folder. There are two partial views included as well, a file called header.phtml for the `<head>` section of your website, and one named footer.phtml for your page `<footer>`. These are in a sub-folder in your view folder called `/partial`.

You can use plain HTML in any view, or use the HTML builder, which is still in progress but should allow you to open and close any HTML tag using open() and close() methods. For instance, the following PHP:

	$this->open("div", "content", "container")
		->h(1, "Main header")
		->open("p", "opening-text", "col-xs-12")
			->text("This is the opening paragraph")
		->close("p")
	->close("div");

will generate the following HTML (when inspecting elements in your browser):

	<div id="content" class="container">
		<h1>Main header</h1>
		<p id="opening-text" class="col-xs-12">This is the opening paragraph</p>
	</div>

but will actually generate minimised HTML, as follows:

	<div id="content" class="container"><h1>Main header</h1><p id="opening-text" class="col-xs-12">This is the opening paragraph</p></div>

To add in a new view, you will need to edit the file in `./path/to/application/config/pages.json`. This file by default may look like this:

	{
		"allowedSegments": {
			"Home":	"home"
		},
		"pageController": {
			"home":	"HomeController"
		},
		"errorReporting":{
			"0":		"http://my-project.dev"
		},
		"allowedFileExts": {
			"0":		"phtml"
		}
	}

Therefore, to add in a new page, you need to name it appropraitely; if you want a section called my-blog on your website (and therefore, you'll have `http://my-project.dev/my-blog` in this example), then add the following:

	{
		"allowedSegments": {
			"home":		"home",
			"my-blog":	"my-blog",
		},
		"pageController": {
			"home":		"HomeController",
			"my-blog":	"MyBlogController"
		},
		"errorReporting":{
			"0":		"http://my-project.dev",
		},
		"allowedFileExts": {
			"0":		"phtml"
		}
	}

Please note that it is a <strike>bug</strike> intended feature of this framework that each view has a related controller. The Application Core will look for a controller for each page views (`allowedSegments` object) in the JSON configuration above.

You may wish to set up some meta data for your new page; this is done in the `pagedata.json` file in `./path/to/application/config/pagedata.json`, which may look like this:

	{
		"titles":{
			"home":	"My home page meta title"
		},
		"descriptions":{
			"home":	"My home page meta description"
		}
	}

Therefore, to add a title and description, you will need to use the name of your page (in this example `my-blog`) so that the framework may identify it, as follows:

	{
		"titles":{
			"home":		"My home page meta title",
			"my-blog": "The meta title for my-blog page"
		},
		"descriptions":{
			"home":		"My home page meta description",
			"my-blog":	"This is where I add a meta description for my page"
		}
	}

Now you will need to add a controller class in the `./path/to/application/controller` directory. Continuing with the `my-blog` example, you will need to add in a `MyBlogController.php` file. A minimum Controller class is as follows:

	<?php
	
	class MyBlogController
	{
	}

If you wish to extend your Controller class from the `ControllerCore`, amend the above script as follows as a minimum:

	<?php
	
	use Application\Controller\ControllerCore;
	
	class MyBlogController extends ControllerCore
	{
		public function __construct(){
			ControllerCore::__construct();
		}
	}

The `ControllerCore` class is loaded on all pages in the `site.json` if this setting is true:

	"loadCoreController" : true

If you set this to false, then this will imply that you don't require the `ControllerCore` class on some or all pages, so for those page controllers that will extend it, you will need to `require` it on each page. To do so, put this at the top of your relevant page controller (after the `use` statement and before the opening `class` declaration:

    require_once (serverPath('/controller/ControllerCore.php'));

The `serverPath` function will auto-route to the `./path/to/application` directory.

If your page is interacting with a MySQL database then you will want to set up a model class in the `./path/to/application/model` directory. If you have called your model class `MyBlogModel.php`, use the following at the top of your PHP controller class after the `use` statement specified above:

	require_once(serverPath('/model/MyBlogModel.php'));

Your controller class might look like this including the core and model:

	<?php
	
	use Application\Controller\ControllerCore;
	
	require_once(serverPath('/model/MyBlogModel.php'));

	class MyBlogController extends ControllerCore
	{
		public function __construct(){
			ControllerCore::__construct();
			$this->sql = new MyBlogModel();
		}
	}

You can add Zend Framework-style view variables in your controller core by using $this->view which is part of the CoreController class. So in your MyPageController class, you can simply do something like this in your constructor:

	<?php
	
	use ControllerCore;
	
	require_once(serverPath('/model/MyBlogModel.php'));
	
	class MyBlogController extends ControllerCore
	{
		public function __construct(){
			ControllerCore::__construct();
			$this->sql = new MyPageModel();
		
			$this->view->myPageHeader		= "<h1>New Header</h1>";
			$this->view->myPageSubHeader	= "<h3>Sub Header</h3>";
			$this->view->myPageContent		= "<p>This is the opening paragraph for my content</p>";
		}
	}

To see these variables in your page view, you simply remove the `view` keyword from the object name, for instance:

	<!DOCTYPE html>
	<html lang="en-gb">
    	<head><?php require_once("{$this->partial['header']}"); ?></head>
    	<body>
    		<div id="content" class="container">
    			<!-- $this->view->myPageHeader in your controller -->
				<?php echo $this->myPageHeader; ?>
				<!-- etc... -->
				<?php echo $this->myPageSubHeader; ?>
				<?php echo $this->myPageContent; ?>
			</div>
			<?php require_once("{$this->partial['footer']}"); ?>
		</body>
	</html>

Please note that the instructions for the date example are as yet unfinished, but this should be enough to go on for now. Any questions, please contact me through GitHub or Twitter.

---

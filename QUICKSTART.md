# Project FrameWork.php MVC v0.1.0 #

### Quick start guide - updated 2017-01-23###

You will already have a home page and 404 page to play with; these files are located in the /application/view folder. There are two partial views included as well, a file called header.phtml for the ```<head>``` section of your website, and one named footer.phtml for your page ```<footer>```. These are in a sub-folder in your view folder.

You can use plain HTML in any view, or use the HTML builder, which is still in progress but should allow you to open and close any HTML tag using open() and close() methods. For instance, the following PHP will generate:

	$this->open("div", "content", "container")
		->h(1, "Main header")
		->open("p", "opening-text", "col-xs-12")
			->text("This is the opening paragraph")
		->close("p")
	->close("div");

will generate the following HTML (when inspecting elements with your browser):

	<div id="content" class="container">
		<h1>Main header</h1>
		<p id="opening-text" class="col-xs-12">This is the opening paragraph</p>
	</div>

but will actually generate minimised HTML, as follows:

	<div id="content" class="container"><h1>Main header</h1><p id="opening-text" class="col-xs-12">This is the opening paragraph</p></div>

To add in a new view, you will need to locate the $allowedSegments array in the FrameworkCore.php file in /application/core; you will see that the home segment is already allowed, simple add to this array, for example:

	public $allowedSegments	= array(
		'home',
		'my-page',
	);

To relate this view to a controller, locate the $pageController array below it, for instance:

	public $pageController	= array(
		'home'			=> "HomeController",
		'my-page'		=> "MyPageController",
	);

Now locate the setTitle() and setDescription() methods in the FrameworkCore class, this allows you to set the title and description meta tags for your page, for instance:

	public function setTitle($page = ''){
	    $titles = array(
			'home'				=> "Example FrameWork.php skeleton site",
			'my-page'			=> "This is my new web page",
	    );
	    return $titles["{$page}"];
	}

and:

	public function setDescription($page = ''){
	    $descriptions = array(
            'home'				=> "The Skeleton",
			'my-page'			=> "I tend not to use Lorem Ipsum in my examples as I don't speak Latin",
	    );
	    return $descriptions["{$page}"];
	}

Once you have your controller, you will need to create a controller class in the /application/controller directory. The class name must match the controller name that you have given above including the .php file extension. If your page is interacting with a MySQL database then you will want to set up a model class in the /application/model directory. If you have called your model class MyPageModel.php, use the following at the top of your PHP controller class:

	require_once(serverPath('/model/MyPageModel.php'));

and if you want to use the ControllerCore class, use

	require_once(serverPath('/controller/ControllerCore.php'));

Your controller class might look like this:

	<?php
	require_once(serverPath('/model/MyPageModel.php'));
	require_once (serverPath('/controller/ControllerCore.php'));

	class MyPageController extends \Application\Controller\ControllerCore
	{
		public function __construct(){
			parent::__construct();
			$this->sql = new MyPageModel();
		}
	}

You can add Zend Framework-style view variables in your controller core by using $this->view which is part of the CoreController class. So in your MyPageController class, you can simply do something like this in your constructor:

	public function __construct(){
		parent::__construct();
		$this->sql = new MyPageModel();
		$this->view->myPageHeader		= "<h1>New Header</h1>";
		$this->view->myPageSubHeader	= "<h3>Sub Header</h3>";
		$this->view->myPageContent		= "<p>This is the opening paragraph for my content</p>";
	}

To see these variables in your page view, you simply remove the view keyword from the object, for instance:

	<!DOCTYPE html>
	<html lang="en-gb">
    	<head><?php require_once("{$this->partial['header']}"); ?></head>
    	<body>
    		<div id="content" class="container">
				<?php echo $this->myPageHeader; ?>
				<?php echo $this->myPageSubHeader; ?>
				<?php echo $this->myPageContent; ?>
			</div>
			<?php require_once("{$this->partial['footer']}"); ?>
		</body>
	</html>

This should be enough to go on for now. As this is a living project, expect more functionality to be added soon. I intend to finish the HTML builder first before moving onto a hopefully useful PDO query builder.

---

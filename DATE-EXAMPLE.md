# Project FrameWork.php MVC v0.1.2 #

### Setting up an example page ###

In this document, we will look in more detail at how FrameWork.php works; it looks at setting page view objects, accessing methods from your page view within the controller, loading in custom JavaScript files in your footer if you are on certain page within your project, and validating form data on your page view with JavaScript and posted to your page controller. The page is available to view at `http://framework.php.local/date-example`.

I assume here that you have some knowledge of PHP, HTML 5 and JavaScript or jQuery. If you are using this document to learn something about PHP and you have experience with a different tech stack or language then I assume that I provide enough here for you to transpose your knowledge of web development. If you are new to PHP then please consider a PHP primer first, including an introduction to object orientated programming.

We have a several files to consider here:

	/path/to/application/controller/DateController.php
	/path/to/application/core/FrameworkCore.php
	/path/to/application/view/date-example.phtml
	/path/to/application/view/partial/footer.phtml
	/path/to/public_html/js/date-check.js

We are not connecting to the database in this example, so a DateModel.php class is not necessary here, though may be added if you want to record the form data that you are posting.

Firstly, we will need to consider the following reserved object names used in FrameWork.php and the usage. These are as follows:

#### Reserved object			Usage ####
`$this->post`			This contains any posted data in your page controller from your HTML form

`$this->view->xxxx`		$this->view is a standard class object used in your page controller to create view objects to be used on your HTML page where xxxx is the name of your view object. For instance, if you set `$this->view->helloSailor = "Hello Sailor";` in your page controller, you are able to echo out this object on your page view using PHP, like: `<?php echo $this->helloSailor; ?>`

`$this->host`			This is used in your page views and is the URL for your project and will re-assign itself when going from local development environments to staging sites to live sites. This means that you can load images on your page with `<img id="my-banner" src="<?php echo $this->host; ?>/img/my-banner.jpg" alt="My page banner" class="img-responsive" />`

`$this->segment`		This is used in your page views and contains the last URI segment of the page of your project. This can be used in forms (if the form is posting to the same page and therefore page controller, you may use this in your form action), or in a condition to decide if there are elements that you want to display, or CSS/JavaScript files that you want to load on particular pages but not others.



Now, let's look at the footer partial view in `/path/to/application/view/partial/footer.phtml`, we have the following PHP and HTML:

	<footer id="footer">
		<script type="text/javascript" src="<?php echo $this->host; ?>/js/scripts.js"></script>
		<?php if($this->segment == 'date-example'): ?>
			<script type="text/javascript" src="<?php echo $this->host; ?>/js/date-check.js"></script>
		<?php endif; ?>
	</footer>

On the third line of the above, we have a PHP condition which is checking the segment, which is the `date-example` part of your URL. This is set in `/path/to/application/core/FrameworkCore.php` in the `$allowedSegments` object. This object is at the top of the file near to the class declaration. It is as follows:

	protected $allowedSegments	= array(
		'home',
		'date-example'
	);

To link this segment (page) to our controller, this is handled by the next the `$pageController` object, which reads as follows:

	protected $pageController	= array(
		'home'			=> "HomeController",
		'date-example'	=> "DateController"
	);

Each new segment that you add expects a page controller, so if you added a new page called 'my-games-list' to the `$allowedSegments` object, you must add a controller instance even if the instance is an empty class, and the name of your controller instance must match your file in `/path/to/application/controller/`, for instance, in this example:

	protected $allowedSegments	= array(
		'home',
		'date-example',
		'my-games-list'
	);
	protected $pageController	= array(
		'home'			=> "HomeController",
		'date-example'	=> "DateController",
		'my-games-list'	=> "MyGamesListController"
	);

You would then need to create a PHP file called `MyGamesListController.php` in `/path/to/application/controller/` as an empty PHP class as a minimum without modification to the core framework.








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

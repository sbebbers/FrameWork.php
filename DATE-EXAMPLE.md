# Project FrameWork.php MVC v0.1.2 #

### Setting up a new page ###

In this document, we will look in more detail at how FrameWork.php works; it looks at setting page view objects, accessing methods from your page view within the controller, loading in custom JavaScript files in your footer if you are on certain page within your project, and validating form data on your page view with JavaScript and posted to your page controller. The page is available to view at `http://your-domain.com/date-example`.

I assume here that you have some knowledge of PHP, HTML 5 and JavaScript or jQuery. If you are using this document to learn something about PHP and you have experience with a different tech stack or language then I assume that I provide enough here for you to transpose your knowledge of web development to the PHP world. If you are new to development generally and PHP 7 specifically then please consider a PHP primer first, including an introduction to object orientated programming.

We have a several files to consider here:

	/path/to/application/controller/DateController.php
	/path/to/application/core/FrameworkCore.php
	/path/to/application/view/partial/header.phtml
	/path/to/application/view/partial/footer.phtml
	/path/to/application/view/date-example.phtml
	/path/to/public_html/js/date-check.js

We are not connecting to the database in this example, so a `DateModel.php` class is not necessary here, though may be added if you want to record the form data that you are posting.

Firstly, we will need to consider the following reserved object names used in FrameWork.php and the usage. These are as follows:

<table >
	<tbody>
		<tr>
			<td><em>Reserved object</em></td>
			<td><em>Usage</em></td>
		</tr>
		<tr>
			<td><code>$this->post</code> &nbsp;&nbsp;</td>
			<td>This is a PHP array object that contains any posted data. This object is used in your page controller and retrieves information from your HTML form</td>
		</tr>
		<tr>
			<td><code>$this->view->xxxx</code> &nbsp;&nbsp;</td>
			<td><code>$this->view</code> is a standard class object used in your page controller to create view objects to be used on your HTML page where xxxx is the name of your view object. For instance, if you set <code>$this->view->helloSailor = "Hello Sailor";</code> in your page controller, you are able to echo out this object on your <code>page-view.phtml</code> file using PHP, like: <code>&lt;?php echo $this->helloSailor; ?&gt;</code></td>
		</tr>
		<tr>
			<td><code>$this->host</code> &nbsp;&nbsp;</td>
			<td>This is used in your page views and is the URL for your project and will re-assign itself when going from local development environments to staging sites to live sites. This means that you can load images on your page with <code>&lt;img id="my-banner" src="&lt;?php echo $this->host; ?&gt;/img/my-banner.jpg" alt="My page banner" class="img-responsive" /&gt;</code></td>
		</tr>
		<tr>
			<td><code>$this->segment</code> &nbsp;&nbsp;</td>
			<td>This is used in your page views and contains the last URI segment of the page of your project. This can be used in forms (if the form is posting to the same page and therefore page controller, you may use this in your form action), or in a condition to decide if there are elements that you want to display, or CSS/JavaScript files that you want to load on particular pages but not others.</td>
		</tr>
		<tr>
			<td> &nbsp;&nbsp;</td>
			<td></td>
		</tr>
		<tr>
			<td> &nbsp;&nbsp;</td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td> &nbsp;&nbsp;</td>
			<td></td>
		</tr>
		<tr>
			<td> &nbsp;&nbsp;</td>
			<td></td>
		</tr>
		<tr>
			<td> &nbsp;&nbsp;</td>
			<td></td>
		</tr>
		<tr>
			<td> &nbsp;&nbsp;</td>
			<td></td>
		</tr>
		<tr>
			<td> &nbsp;&nbsp;</td>
			<td></td>
		</tr>
	</tbody>
</table>
		
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
		'home'		=> "HomeController",
		'date-example'	=> "DateController"
	);

Each new segment that you add expects a page controller, so if you added a new page called 'my-games-list' to the `$allowedSegments` object, you must add a controller instance even if the instance is an empty class, and the name of your controller instance must match your file in `/path/to/application/controller/`, for instance, in this example:

	protected $allowedSegments	= array(
		'home',
		'date-example',
		'my-games-list'
	);
	protected $pageController	= array(
		'home'		=> "HomeController",
		'date-example'	=> "DateController",
		'my-games-list'	=> "MyGamesListController"
	);

You would then need to create a PHP file called `MyGamesListController.php` in `/path/to/application/controller/` as an empty PHP class as a minimum without modification to the core framework.

---

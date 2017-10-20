# Project FrameWork.php MVC v0.1.3a #

### Setting up a new page ###

#### This document is still work in progress as of 2017-10-19 ####

In this document, we will look in more detail at how FrameWork.php works; it looks at setting page view objects, accessing methods from your page view within the page controller, loading in custom JavaScript files in your footer if you are on certain page within your project, and validating form data on your page view with JavaScript and with the related page controller class. The page is available to view at `http://your-domain.com/date-example` where `your-domain.com` is your domain name and `date-example` is the page.

I assume here that you have some knowledge of PHP, HTML 5 and JavaScript or jQuery. If you are using this document to learn something about PHP and you have experience with a different tech stack or language then I assume that I provide enough here for you to transpose your knowledge of web development to the PHP world. If you are new to development generally and PHP 7 specifically then please consider a PHP primer first, including an introduction to object orientated programming. `http://php.net` is an excellent resource for understanding the core PHP concepts, commands and APIs.

We have a several files to consider here:

	/path/to/application/config/pages.json - and other configuration files
	/path/to/application/controller/DateController.php
	/path/to/application/view/partial/header.phtml
	/path/to/application/view/partial/footer.phtml
	/path/to/application/view/date-example.phtml
	/path/to/public_html/js/date-check.js

We are not connecting to the database in this example, so a `DateModel.php` class is not necessary here, though may be added if you want to record the form data that you are posting. You will need to set up relevant MySQL database tables and such.

Firstly, we will need to consider the following reserved objects within the FrameWork.php microcosm and usages of each. These are as follows:

<table style="border:1px solid #000;border-radius:4px;padding:2px">
	<tbody>
		<tr>
			<td style="border:1px solid #444"><em>Reserved object</em></td>
			<td style="border:1px solid #444"><em>Usage(s)/notes</em></td>
		</tr>
		<tr>
			<td style="border:1px solid #444"><code>$this->post</code> &nbsp;&nbsp;</td>
			<td style="border:1px solid #444">This is a PHP array object that contains any posted data. This object is used in your page controller and retrieves information from your HTML form</td>
		</tr>
		<tr>
			<td style="border:1px solid #444"><code>$this->view->xxxx</code> &nbsp;&nbsp;</td>
			<td style="border:1px solid #444"><code>$this->view</code> is a standard class object set in your page controller to create view objects to be used on your PHTML page where xxxx is the given name of your view object. For instance, if you set <code>$this->view->helloSailor = "Hello Sailor";</code> in your page controller, you are able to echo out this object on your <code>page-view.phtml</code> file using PHP, like: <code>&lt;?php echo $this->helloSailor; ?&gt;</code> You may set more complex objects in your page controller; examples will be covered in another tutorial</td>
		</tr>
		<tr>
			<td style="border:1px solid #444"><code>$this->host</code> &nbsp;&nbsp;</td>
			<td style="border:1px solid #444">This is used in your page views and is the URL for your project and will re-assign itself when going from local development environments to staging sites to live sites. This means that you can load images on your page with <code>&lt;img id="my-banner" src="&lt;?php echo $this->host; ?&gt;/img/my-banner.jpg" alt="My page banner" class="img-responsive" /&gt;</code></td>
		</tr>
		<tr>
			<td style="border:1px solid #444"><code>$this->segment</code> &nbsp;&nbsp;</td>
			<td style="border:1px solid #444">This is used in your page views and contains the last URI segment of the page of your project. This can be used in forms (if the form is posting to the same page and therefore page controller, you may use this in your form action), or in a condition to decide if there are elements that you want to display, or CSS/JavaScript files that you want to load on particular pages but not others.</td>
		</tr>
		<tr>
			<td style="border:1px solid #444"><code>$this->title</code>, <code>$this->description</code></td>
			<td style="border:1px solid #444">These are page view object used in the header partial that may be echoed out in your meta title and meta description in your html <code>&lt;head&gt; &lt;/head&gt;</code></td>
		</tr>
		<tr>
			<td style="border:1px solid #444"><code>$this->controller</code></td>
			<td style="border:1px solid #444">This is a core object that allows you to call controller methods and obtain controller objects in your view file. In this example, we will be accessing the <code>DateController::getDefault()</code> and <code>DateController::clearDefault()</code> methods</td>
		</tr>
		<tr>
			<td style="border:1px solid #444">Various HTML builders such as <code>$this->open()</code> and <code>$this->form()</code></td>
			<td style="border:1px solid #444">FrameWork.php has some HTML methods available to all page views to allow you to build minified HTML by using PHP - minified HTML may not seem like much of a big deal, but from previous dealings with SEO companies, some of them may insist on it to make it look like they are doing something useful (i.e., they run some automated report script that gives them recommendations that they may pass on to a mutual client, which you then have to implement, and this is the reason that the HTML builder creates minified HTML views if used in PHP blocks within the page view)</td>
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

On the third line of the above, we have a PHP condition which is checking the segment, which is the `date-example` part of your URL. This is set in `/path/to/application/config/pages.json` in the `allowedSegments` JSON object. The data element here must match what you intend your URI segment to be; in this case, `date-example` will map to the relevant view, and at it's available to this view through `$this->segment` shown in the above mark-up/PHP snippet. We must then link the view to a controller instance, this is a <strike>bug</strike> <strike>feature</strike> <strike>limitation</strike> pre-requisite of the framework, in that each page must have a controller; page models are optional and are linked from the controller as required. This means that you can generate static landing pages either by setting up view objects in your controller, or by simply having an empty controller class and use static HTML without needing a MySQL connection option to do so. But more on this in another tutorial I think.

Linking the date-example view to the page controller is handled in the pages.json configuration file located in `/path/to/application/config/pages.json` - in this example, you will see:

	{
		"allowedSegments":{
			"Home": "home",
			"Date-Example":	"date-example"
		},
		"pageController":{
			"home": "HomeController",
			"date-example":	"DateController"
		},
		"errorReporting":{
			"0": "http://framework.php.dev",
			"1": "https://framework.php.dev"
		},
		"allowedFileExts":{
			"0": "htm",
			"1": "html",
			"2": "asp",
			"3": "aspx",
			"4": "php",
			"5": "phtml"
		},
		"ignoredFileExts":{
			"0": "jpeg",
			"1": "jpg",
			"2": "png",
			"3": "js",
			"4": "gif",
			"5": "ico",
			"6": "pdf"
		}
	}

Hopefully these configuration options are fairly self-explanatory. Please note that only `pageController` objects in the JSON are key/data pairs for individual items; in other configuration options, the key does not matter as long as there are no repeating keys. None of the names for the parent objects should be changed or this will cause the site to crash or behave in an unexpected way. You may wish to change the values in the `errorReporting` object to represent your domain name. This will allow you to set all visible PHP error reporting for your development and staging sites.

It is strongly not recommended to set error reporting on live sites unless you limit access on your server whilst debugging. Use the `GlobalHelpers::writeToLogFile()` method to create your own PHP Exceptions using the `FrameworkException` class and this will make switching on visible PHP error reporting unnecessary in the long run.


---

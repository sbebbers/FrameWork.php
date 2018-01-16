# Project FrameWork.php MVC v0.1.4-RC4 #

### Setting up a new page ###

#### This document is still work in progress as of 2017-11-16 ####

In this document, we will look in more detail at how FrameWork.php works; it looks at setting page view objects, accessing methods from your page view within the page controller, loading in custom JavaScript files in your footer if you are on certain page within your project, and validating form data on your page view with JavaScript and with the related page controller class. The page is available to view at `http://your-domain.com/date-example` where `your-domain.com` is your domain name and `date-example` is the page.

I assume here that you have some knowledge of PHP, HTML 5 and JavaScript with jQuery. If you are using this document to learn something about PHP and you have experience with a different tech stack or language then I assume that I provide enough here for you to transpose your knowledge of web development to the PHP world. If you are new to development generally and PHP 7 specifically then please consider a PHP primer first, including an introduction to object orientated programming. `http://php.net` is an excellent resource for understanding the core PHP concepts, commands and APIs. And when you are unable to find answers to your issues via php.net, `http://stackoverflow.com` is another vital resource. My network profile (for StackOverflow) is found at `https://stackoverflow.com/users/5463015/shaun-bebbers` for those interested.

We have a several files to consider here:

	./path/to/application/config/pages.json - and other configuration files
	./path/to/application/controller/DateController.php
	./path/to/application/view/partial/header.phtml
	./path/to/application/view/partial/footer.phtml
	./path/to/application/view/date-example.phtml
	./path/to/public_html/js/date-check.js

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
			<td style="border:1px solid #444">This is used in your page views and contains the last URI segment of the page of your project. This can be used in forms (if the form is posting to the same page and therefore page controller, you may use this in your form action), or in a conditional statement to decide if there are elements that you want to display, or CSS/JavaScript files that you want to load on particular pages but not others.</td>
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
			<td style="border:1px solid #444">FrameWork.php has a HTML builder class with various methods available to all page views to allow you to build minified HTML by using PHP - minified HTML may not seem like much of a big deal, but from previous dealings with SEO companies, some of them may insist on this in one of those automated reports such organisations like to send to web developers to make it look like they are doing something useful (i.e., automated reports are generated and given as recommendations to a mutual client, which you then have to implement usually for very minor if any benefits to the client)</td>
		</tr>
	</tbody>
</table>
		
Now, let's look at the footer partial view in `./path/to/application/view/partial/footer.phtml`, we have the following PHP and HTML:

	<footer id="footer">
		<script type="text/javascript" src="<?php echo $this->host; ?>/js/scripts.js"></script>
		<?php if($this->segment == 'date-example'): ?>
			<script type="text/javascript" src="<?php echo $this->host; ?>/js/date-check.js"></script>
		<?php endif; ?>
	</footer>

On the third line of the above, we have a PHP condition which is checking the string literal value of the segment object, which will match the `date-example` part of your URL. This is set in `./path/to/application/config/pages.json` in the `allowedSegments` JSON object.

In the `pages.json` file, each valid URI segment (`allowedSegments`) will link directly to a page controller when you load the page; this is a <strike>bug</strike> <strike>feature</strike> <strike>limitation</strike> pre-requisite of the FrameWork.php, in that each page must have a controller class assigned; page models are optional and are linked from the controller as required. This means that you can generate static landing pages either by assigning view objects in your controller, or by simply having an empty controller class and use static HTML without needing a MySQL connection. The only exception to this is the 404 page, which you may modify by using static HTML/CSS and jQuery (if you really want to) as required. But more on this in another tutorial.

Back to linking the date-example view to the page controller; looking at the `pages.json` configuration file, you will see something like this:

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

Hopefully these configuration options are fairly self-explanatory. Please note that only `pageController` objects in the JSON are key/data pairs for individual items; in other configuration options, the key value does not matter as long as there are no repeating keys with exception to the parent objects (`allowedSegments`, `pageController`, `errorReporting`, `allowedFileExts` and `ignoredFileExts`). These must remain as is or your site will crash or behave in an unexpected way. You may wish to change the values in the `errorReporting` object to represent your domain name. This will allow you to set PHP error reporting to visible for your development and staging site(s).

It is strongly recommended to switch off error reporting on live sites unless you limit access on your server whilst debugging. Using the `FrameworkException` class, you can create your own exceptions to be recorded in your log files generated in `./path/to/application/logs/xxx/yy.log` where `xxx` is the three-digit month code (jan - dec) and `yy` is the day of the month (01 - 31).

If we take a look at `./path/to/application/view/date-example.phtml` at the conditional statement from line 8 to 17 inclusive, you will see that you are calling the controller methods `DateController::getDefault()` and `DateController::clearDefault()`; this is not necessarily good practice but it shows that all page controller methods are available to your view during pre-processing. I'll leave you to think of a better way to do this, but here's what is happening at this point in the process:

    I. The FrameWorkCore class initiates an instance of the DateController when landing on the relevant page (http://framework.php.dev/date-example.phtml in this case);
    II. The controller sets the 'days', 'months' and 'years' values as view objects, to make them available to the view as $this->days, $this->months and $this->years;
    III. In each of the above object is a default value, which in each case will be dynamically assigned as the current day, month and year; and
    IV. The DateController::getDefault() method will return this value so that each drop-down item; each call is assigned to a relevant variable in the view.

This is a slightly convoluted way of going about things, as I've eluded to above.

If we take a look at the `DateController` class and investigate the methods therein, you will notice that the `goto` statement is used. Why? Well firstly, although frowned upon, it is a legal part of PHP from version 5.3.0 upwards - see here: `http://php.net/manual/en/control-structures.goto.php`; and secondly I read a StackOverflow post about a real-world scenario of using this command in a real-life example. And finally, as someone who grew up with Commodore [Microsoft] BASIC, `GOTO` was a good way of increasing the speed of program listings. I'll leave you to find a better way to refactor the controller here to replace `goto` with something that most software developers would prefer. As an aside, I'd much rather see `goto` in my PHP in most cases over `switch/case`, but avoid both.

Aside from the <strike>mis</strike>use of `goto`, you will see that on initiation of the `DateController` class, it first checks to see if the `$this->post` object is not empty - if so, we want to do something with the posted data which sets a veiw object as a string to be displayed on the page load. It will determine if the date you have submitted is valid or not, i.e., 30th February is never a valid date. This is secondary sanity check incase the jQuery script that checks the form before submission fails for some reason, and is good practice to sanitize your posted data even if you have some JavaScript that is doing so (as JS in a browser can be disabled, whereas it is more difficult to disable or disrupt the pre-processing on the server). But all of this happens after the page has loaded and you have submitted your date.

Before it does all of that, it builds the view objects for the page view; and magically, the page view uses the `HTML Builder` to make the HTML page, including the web form - this can be viewed in `./path/to/application/core/HtmlBuilder.php`; each method should be self-explanatory but if you are unsure then simply use plain HTML in your views.

I'll therefore leave you to delve into the following files so that you can work out what's going on:

	./path/to/application/view/date-example.phtml and
	./path/to/public_html/js/date-check.js

If you have any questions then feel free to contact me.

---

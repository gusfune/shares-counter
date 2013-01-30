Social Counter (v.1.0.2)

This simple PHP function allows the user to display the amount of times
that an URL have been shared on different social networks. Right now it
supports Facebook, Twitter, Google Plus and LinkedIn.

This repository contains the open source PHP and is licensed under the
Apache Licence, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0.html).


Usage
-----

First you need to add the function into your PHP code, then you just need
to call the function directly into the code:

	<?php 
		$the_counter = new ShareCounter();
		$the_counter->echoAllSharesCount();
	?>

You can use some methods of the class if you don't want to get counts
from any specific network or if you want to pass an url that is not the current
page.

The methods are:

- setURL($url (string)): Set the URL used with the object, if empty, get automatically the URL of the requested page.
- getURL() (string): Return the URL used with the object
- getFacebookSharesCount() (int): Return the number of shares of the URL in Facebook.
- getTwitterSharesCount() (int): Return the number of shares of the URL in Twitter.
- getGooglePlusSharesCount() (int): Return the number of shares of the URL in Google Plus.
- getLinkedinSharesCount() (int): Return the number of shares of the URL in Linkedin.
- getSharesCount($services) (int): Return the number of shares of the URL in services specified by the array $services.
- getAllSharesCount(): Return the sum of number of shares of the URL in all services supported by this script
- echoAllSharesCount(): Just echo the sum of number of shares of the URL in all services supported by this script

For example, if you want to remove linkedin from the list, you can:

	<?php 
		$count = new SharesCounter();
		echo $count->getSharesCount(array("facebook", "twitter","google plus"));
	?>


To use the code in Wordpress for any specific post, you can simply add (within
the loop):

	<?php 
		$count = new SharesCounter( get_permalink() );
		$count->echoAllSharesCount();
	?>


You can also return the result as a variable instead of echoing the number:

	<?php 
		$the_counter = new SharesCounter( '' );
		$count = $the_counter->getAllSharesCount();
	?>


Contributing
===========

When commiting, keep all lines to less than 80 characters, and try to
follow the existing style.

Before creating a pull request, squash your commits into a single commit.

Add the comments where needed, and provide ample explanation in the
commit message.


Report Issues/Bugs
===============
[Send me an e-mail](mailto:gusfune@epicawesome.co)

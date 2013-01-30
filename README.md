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

	<?php sharesCounter(); ?>

You can pass some parameters on the function if you don't want to get counts
from any specific network or if you want to pass an url that is not the current
page.

The parameters are (in order of usage):

- $url (string): defaults to current url.
- $echo (bool): if false it will just return the sum as a variable, if true it
echoes the results. Defaults to true.
- $facebook (bool): defaults to true.
- $twitter (bool): defaults to true.
- $gplus (bool): defaults to true.
- $linkedin (bool): defaults to true.


For example, if you want to remove linkedin from the list, you can:

	<?php sharesCounter('', true, true, true, true, false); ?>


To use the code in Wordpress for any specific post, you can simply add (within
the loop):

	<?php sharesCounter( get_permalink() ); ?>


You can also return the result as a variable instead of echoing the number:

	<?php $variable = sharesCounter('', false); ?>


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
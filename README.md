JCD Online PHP Class
==========

Class that will help you manage your auto logins for JCD Online.

Use
==========
```php
  $jcd = new JCDOnline(); # live use

  $jcd = new JCDOnline(true); # to initiate the object for testing

  $url_link = $jcd->getTPSLink(); # generates your auto login link - simply echo it into link.

  echo '<a href="'.$url_link.'">Access JCD Online</a>';
```

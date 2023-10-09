# Router boilerplate

**How does it work?** Simple, just define your routes inside routes.yml, create some pages inside viwes directory and you are done!

## Directory Structure

These are the main files you are worried about

```
├── public              - all public files goes here, that means css, js, images etc.
│   ├── index.php       - don't touch this
│   ├── .htaccess       - also don't touch this
│   └── **/**           - all other public files you desire to put here
├── src                 - some files that handle routing, you don't have to touch that if you don't want to
└── views               - you will put all your pages inside this directory, 
    ├── error/404.php   - your error 404 page, edit this based on your likings, don't delete this unless you are absolutely sure aboud what you are doing
    └── **/*.php        - all php files of your pages that are defined inside routes.yml
```

## Defining routes

All you need to do is define name of the route, specify it's url and define which file sohould be loaded

```yaml
# routes.yml
homepage:                            # name of the route
    route: /                         # url
    view: pages/homepage.php         # file to get from the views/ directory
about-us:
    route: /about-us/
    view: pahe/about-us.php
```

Router also supports dynamic routes, just provide a parameter name in curly braces and you can access it in your view file later

```yml
dynamic:
  route: /dynamic-route/{slug}/    # dynamic parameter names slug, you can define as many parameters as you want
  view: pages/dynamic-route.php
```

## Views

All your view files live inside `views/` directory. Below are some usefull functions you can use in this files.

### Creating links

You can hardcode every url into your html code, but you can also let the router handle that, just use `link` function;

```php
<?php
$this->link("homepage"); // this will generate url of specified route
/* usage <a href="<?= $this->link("homepage"); ?>">link name</a> */

// You can also provide array of url parameters as second parameter of the function
$this->link("dynamic", ['slug' => 'example-slug']); // based on example config above this will generate url /dynamic-route/example-slug/

// if route doesn't have any dynamic parameters defined, parameters will be appended as query parameters
$this->link("homepage", ['example' => 'param']); // this will generate /?example=param
```

### Getting current route name

If you want to get current route name from your view, you absolutely can, just call `getCurrentRoute` function. For example this is especially usefull when you want to highlight active menu item.

This code will ad class active to the link, if the current route is homepage
```php
<a href="<?= $this->link('homepage') ?>" class="<?= $this->getCurrentRoute() == "homepage" ? 'active' : '' ?>">Homepage</a>
```

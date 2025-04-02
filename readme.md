Starter kit for a new LAMP stack project using [Flight](https://docs.flightphp.com/en/v3/) and [Bootstrap 3](https://getbootstrap.com/docs/3.4/css/) with optional classes. An example can be found [here](https://take-flight.gerbz.com). To create a new site:

1. This repo contains the contents of the `/public_html` directory which needs to be created along with the `/private_html` directory containing empty `/cache`, `/data` and `/logs` directories. Don't forget to move `config.php` from `/public_html` to `/private_html`. The final top level directory structure should be as follows:

```
/private_html
↳ /cache
↳ /data
↳ /logs
↳ config.php`
/public_html
↳ /app
↳ /home
```

2. Point your server's webroot at `/public_html/home/`
3. Download the latest version of [Flight](https://docs.flightphp.com/en/v3/) to `/public_html/app/vendor/`
4. Move `config.php` from `public_html` to `private_html` and update the variables. If you have a staging/development environment, be sure to target it in `config.php`'s //Buidl mode
5. Update images in `/public_html/assets/images/og/`
6. Create favicons using [realfavicongenerator.net](https://realfavicongenerator.net/) and add to`/public_html/home/` and `/public_html/assets/favicons/`
7. To include an optional class, move it up one directory from `/public_html/app/classes/optional/` and complete the @todo items at the top.
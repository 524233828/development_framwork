<?php

route()->get('/', 'WelcomeController@welcome');
route()->get('/hello/{name}', 'WelcomeController@sayHello');
route()->get('/demo', 'DemoController@demo')->withAddMiddleware("filter");

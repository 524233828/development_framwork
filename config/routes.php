<?php

route()->get('/demo', 'DemoController@demo')->withAddMiddleware("filter");

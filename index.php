<?php

/*
|--------------------------------------------------------------------------
| Front controller for document-root installs
|--------------------------------------------------------------------------
|
| This file only matters when the whole project is uploaded straight into the
| document root (public_html/), which is the usual cPanel File Manager upload.
| It boots the real front controller in public/ while keeping SCRIPT_NAME at
| the project root: Symfony works the URL base out from SCRIPT_NAME, so
| rewriting directly to public/index.php would make Laravel believe it lives
| in a "/public" sub-directory and every route would 404.
|
| When the document root points at public/ instead, this file is never used
| and public/index.php answers every request as normal.
|
*/

require __DIR__.'/public/index.php';

<?php

/*
|--------------------------------------------------------------------------
| Basic Pest setup (for plain PHP project)
|--------------------------------------------------------------------------
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

function something()
{
    // custom helpers (if you ever need them)
}

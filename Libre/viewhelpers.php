<?php
use Libre\Helpers;

/**
 * @return string
 */
function css()
{
    return Helpers::getCssAsTags(false, true);
}

/**
 * @return string
 */
function js()
{
    return Helpers::getJsAsTags(false, true);
}

function user()
{
    return $_SESSION['User'];
}
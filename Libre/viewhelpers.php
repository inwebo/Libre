<?php
use Libre\Helpers;
use Libre\System;
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

/**
 * @param $name
 * @return \Libre\Models\Module
 */
function module($name)
{
    return System::this()->getModule($name);
}
/**
 * @param $route
 * @return \Libre\Routing\Route;
 */
function addRoute($route)
{
    return System::this()->getRoutesCollection()->addRoute($route);
}

/**
 * @return string Return global public dir url
 */
function publicUrl()
{
    return System::this()->getInstanceLocator()->getBaseUrl() . 'public/';
};
/**
 * @return string Return instance public dir url
 */
function publicInstanceUrl()
{
    return System::this()->getInstance()->toUrl(). 'public/';
};
/**
 * @param string Theme name
 * @return string Return theme public dir url
 */
function publicThemeUrl($name)
{
    return System::this()->getTheme($name)->getPathsLocator()->getBaseUrl() . 'public/';
};
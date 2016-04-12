<?php
/*
 *  Copyright notice
 *
 *  (c) 2016 Andreas Thurnheer-Meier <tma@iresults.li>, iresults
 *  Daniel Corn <cod@iresults.li>, iresults
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 */

/**
 * @author COD
 * Created 12.04.16 16:10
 */


namespace App\Http;


use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class RequestFactory
{
    /**
     * @return Request
     */
    public static function createRequest()
    {
        SymfonyRequest::setFactory([new static(), 'create']);

        return Request::capture();
    }

    /**
     * @param array $query
     * @param array $request
     * @param array $attributes
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param null  $content
     * @return SymfonyRequest
     * @internal
     */
    public function create(
        array $query = array(),
        array $request = array(),
        array $attributes = array(),
        array $cookies = array(),
        array $files = array(),
        array $server = array(),
        $content = null
    ) {
        if (isset($query['p'])) {
            $path = $query['p'];

            $this->patchRequestAttribute($server, 'QUERY_STRING', $path, true);
            $this->patchRequestAttribute($server, 'REQUEST_URI', $path);
            $this->patchRequestAttribute($server, 'PATH_INFO', $path);
            $this->patchRequestAttribute($server, 'PHP_SELF', $path);
        }

        return new SymfonyRequest($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    private static function patchRequestAttribute(&$server, $attribute, $path, $removeIfEmpty = false)
    {
        if (isset($server[$attribute])) {
            $newValue = str_replace(["p=$path&", "p=$path"], '', $server[$attribute]);

            $questionMarkPosition = strpos($newValue, '?');
            $questionMarkPosition = $questionMarkPosition !== false ? $questionMarkPosition : strlen($newValue);

            $newValue = substr_replace($newValue, $path, $questionMarkPosition, 0);

            if ($newValue || !$removeIfEmpty) {
                $server[$attribute] = $newValue;
            } else {
                unset($server[$attribute]);
            }
        }
    }
}

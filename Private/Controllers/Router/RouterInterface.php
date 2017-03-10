<?php
namespace Controllers\Router;

/**
 * @author "Diego Lopez Rivera forgin50@gmail.com"
 *
 */
interface RouterInterface {

	/*
	 *
	 */
	public function matchRequest();

	/*
	 *
	 */
	public function findMatch($requestUrl);

	/*
	 *
	 */
	public function generateRoute($routeName, array $params);
}
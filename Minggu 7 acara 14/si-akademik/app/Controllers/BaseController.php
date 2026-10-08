<?php

class BaseController
{
	protected function view($view, array $data = [])
	{
		extract($data, EXTR_SKIP);
		require __DIR__ . '/../Views/' . $view . '.php';
	}

	protected function redirect($path)
	{
		header('Location: ' . app_url($path));
		exit;
	}
}

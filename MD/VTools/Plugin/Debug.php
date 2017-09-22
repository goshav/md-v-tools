<?php

namespace MD\VTools\Plugin;

use EApp\App;
use EApp\Plugin\Shortable;
use EApp\Proto\Plugin;
use MD\VTools\Traits\PluginShortTagTrait;

class Debug extends Plugin implements Shortable
{
	use PluginShortTagTrait;

	public function getContent()
	{
		$name = $this->shortTag ? $this->choice(['debug', 0], false) : $this->get("debug");
		if( $name === false )
		{
			return '';
		}

		if( ! is_string($name) )
		{
			$data = $name;
			$name = '?';
		}
		else
		{
			$view = App::View();
			$data = $name === '*' ? $view->getData() : ( strpos($name, '->') ? $view->getPath($name) : $view->get($name) );
		}

		ob_start();
		print_r( $data );
		$value = "Debug `{$name}`: \n" . ob_get_contents();
		ob_end_clean();

		if( $this->shortTag ? $this->choice(['pre', 1], true) : $this->getOr('pre', true) )
		{
			$value = '<pre>' . $value . '</pre>';
		}

		return $value;
	}
}

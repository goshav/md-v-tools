<?php

namespace MD\VTools\Plugin;

use EApp\Cache;
use EApp\Proto\Plugin;
use MD\VTools\Svg\SvgCollection;

class ShadowSvgIcons extends Plugin
{
	protected $cacheType = "plugin";

	protected $prefix = '';

	public function getContent()
	{
		$id = $this->getOr("id", "icons");

		if( !$id )
		{
			return "<!-- set valid 'id' property -->";
		}

		$dir = $this->getOr("dir", "svg");
		$dir = trim($dir, "/");

		if( !strlen($dir) )
		{
			return "<!-- set valid 'dir' property -->";
		}

		if( $this->cacheType() == "plugin" )
		{
			$cache = new Cache( $id, "svg/" . $dir, $this->cacheData() );
			if( $cache->ready() )
			{
				return $cache->getContentData();
			}
		}
		else
		{
			$cache = null;
		}

		return $this->getSvg( $id, $dir, $cache );
	}

	protected function getSvg( $id, $dir, Cache $cache = null )
	{
		$dir_key = $dir;
		if( DIRECTORY_SEPARATOR !== "/" )
		{
			$dir = str_replace("/", DIRECTORY_SEPARATOR, $dir);
		}

		$svg = new SvgCollection( ASSETS_DIR . $dir );
		if( ! $svg->isReady() )
		{
			return "<!-- can not ready svg directory '{$dir_key}' -->";
		}

		$out = '';

		/** @var \MD\VTools\Svg\SvgIcon $item */
		foreach( $svg as $item)
		{
			$out .= "\n\t" . $item->getSvg([], 'symbol');
		}

		if( strlen($out) )
		{
			$out = '<svg id="' . $id . '" xmlns="http://www.w3.org/2000/svg" style="display: none">' . $out . "\n</svg>";
		}

		if( $cache !== null )
		{
			$cache->write($out);
		}

		return $out;
	}

	// set cache type

	protected function filterData( array $data )
	{
		$this->items = $data;

		$prop = [];
		$prefix = $this->getOr("prefix", "");
		if( strlen($prefix) )
		{
			$prop['prefix'] = $this->prefix = $prefix;
		}

		unset($this->items['prefix']);

		return $prop;
	}
}
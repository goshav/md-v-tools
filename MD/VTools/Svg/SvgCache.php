<?php
/**
 * Created by IntelliJ IDEA.
 * User: GoshaV [Maniako] <gosha@rozaverta.com>
 * Date: 22.09.2017
 * Time: 16:50
 */

namespace MD\VTools\Svg;

use EApp\Cache;

class SvgCache
{
	private static $icons = [];
	private static $icon_items = [];

	/**
	 * @param string $dir
	 * @return array
	 */
	public static function cache( $dir = 'svg' )
	{
		$dir = trim($dir, '/');

		if( ! isset(self::$icons[$dir]) )
		{
			$cache = new Cache('raw_' . md5($dir), 'svg');
			if( $cache->ready() )
			{
				self::$icons[$dir] = $cache->getContentData();
			}
			else
			{
				$path = ASSETS_DIR;
				if( strlen($dir) )
				{
					$path .= $dir;
				}

				$svg = new SvgCollection( $path );
				self::$icons[$dir] = [];

				if( $svg->isReady() )
				{
					/** @var \MD\VTools\Svg\SvgIcon $item */
					foreach( $svg as $item )
					{
						self::$icons[$dir][ $item->getName() ] = [ $item->getData(), $item->getViewBox(), $item->getWidth(), $item->getHeight() ];
					}

					$cache->write(self::$icons[$dir]);
				}
			}
		}

		return self::$icons[$dir];
	}

	/**
	 * @param string $name
	 * @param string $dir
	 * @return null | SvgIcon
	 */
	public static function get( $name, $dir = 'svg' )
	{
		$dir = trim($dir, '/');
		$key = $dir . '_' . $name;

		if( ! isset(self::$icon_items[$key]) )
		{
			if( ! isset(self::$icons[$dir]) )
			{
				self::cache( $dir );
			}

			if( isset(self::$icons[$dir][$name]) )
			{
				$val = self::$icons[$dir][$name];
				$val = new SvgIcon($name, $val[0], $val[1], $val[2], $val[3] );
			}
			else
			{
				$val = null;
			}

			self::$icon_items[$key] = $val;
		}

		return self::$icon_items[$key];
	}

	public static function getIs( $name, $dir = 'svg' )
	{
		$dir = trim($dir, '/');
		$key = $dir . '_' . $name;

		if( isset(self::$icon_items[$key]) )
		{
			return true;
		}

		if( ! isset(self::$icons[$dir]) )
		{
			self::cache( $dir );
		}

		return isset(self::$icons[$dir][$name]);
	}
}
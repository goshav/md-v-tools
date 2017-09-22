<?php
/**
 * Created by IntelliJ IDEA.
 * User: GoshaV [Maniako] <gosha@rozaverta.com>
 * Date: 07.09.2017
 * Time: 22:09
 */

namespace V;

use EApp\Support\Str;
use MD\VTools\Svg\SvgCache;

function svgIcon($name, $width = null, $height = null)
{
	$icon = SvgCache::get($name);
	if( !$icon )
	{
		return "<!-- icon '{$name}' not found -->";
	}

	$prop = [];
	if( $width )
	{
		$prop['width'] = $width;
	}
	if( $height )
	{
		$prop['height'] = $height;
	}

	return '<i class="svg-icon icon-' . $name . '">' . $icon->getSvg($prop) . '</i>';
}

function svgIconUse($name, $width = null, $height = null)
{
	$icon = SvgCache::get($name);
	if( !$icon )
	{
		return "<!-- icon '{$name}' not found -->";
	}

	$prop = [];
	if( $width )
	{
		$prop['width'] = $width;
	}
	if( $height )
	{
		$prop['height'] = $height;
	}

	return '<i class="svg-icon icon-' . $name . '">' . $icon->getSvgUse($prop, 'icon-') . '</i>';
}


function className( $val, $as_attr = false )
{
	if( is_array( $val ) )
	{
		$map = [];
		$i = 0;

		foreach( $val as $name => $value )
		{
			if( $i != $name )
			{
				$pref = $name . "-";
			}
			else
			{
				$i++;
				$pref = '';
			}

			if( is_array( $value ) )
			{
				foreach( $value as $add )
				{
					$map[] = $pref . $add;
				}
			}
			else
			{
				$map[] = $pref . $value;
			}
		}
		$get = implode( ' ', $map );
	}
	else
	{
		$get = trim( $val );
	}

	if( $as_attr )
	{
		if( strlen($get) )
		{
			$get = ' class="' . $get . '"';
		}
	}

	return $get;
}

function idName( $val, $as_attr = false )
{
	if( is_array( $val ) )
	{
		$get = implode( '_', $val );
	}
	else
	{
		$get = trim( $val );
	}

	if( $as_attr )
	{
		if( !strlen($get) )
		{
			$get = 'id_' . Str::random(12);
		}
		$get = ' id="' . $get . '"';
	}

	return $get;
}
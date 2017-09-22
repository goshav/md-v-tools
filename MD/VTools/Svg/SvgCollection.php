<?php
/**
 * Created by IntelliJ IDEA.
 * User: GoshaV [Maniako] <gosha@rozaverta.com>
 * Date: 22.09.2017
 * Time: 16:50
 */

namespace MD\VTools\Svg;

use EApp\Support\Collection;

class SvgCollection extends Collection
{
	private $ready = false;

	public function __construct( $path = null )
	{
		if( !$path )
		{
			$path = ASSETS_DIR . "svg";
		}
		else
		{
			$path = rtrim( $path, DIRECTORY_SEPARATOR );
		}

		if( is_dir($path) )
		{
			parent::__construct( $this->load($path) );
		}
	}

	public function isReady()
	{
		return $this->ready;
	}

	private function load( $path )
	{
		$files = @ scandir( $path );
		if( !is_array($files) )
		{
			return [];
		}

		$this->ready = true;
		$path .= DIRECTORY_SEPARATOR;
		$items = [];

		foreach($files as $file)
		{
			if( $file[0] !== "." && preg_match('/^(.*?)\.svg$/i', $file, $m) )
			{
				$icon = $this->readyIcon( $m[1], file_get_contents($path . $file) );
				if( $icon )
				{
					$items[] = $icon;
				}
			}
		}

		return $items;
	}

	private function readyIcon( $name, $data )
	{
		$pos = stripos($data, '<svg');
		if( $pos === false )
		{
			return false;
		}

		if( $pos > 0 )
		{
			$data = substr($data, $pos);
		}

		$pos = strpos($data, '>');
		$end = stripos($data, '</svg>');
		if( $pos === false || $end === false )
		{
			return false;
		}

		$viewBox = null;
		$width = null;
		$height = null;
		$svg = substr($data, 0, $pos);

		if(preg_match('/viewbox=("|\')(.*?)\1/i', $svg, $m))
		{
			$box = preg_split('/\s+/', $m[2]);
			$viewBox = implode(" ", $box);
			if( count($box) == 4 )
			{
				$width = $this->getWH($box[2]);
				$height = $this->getWH($box[3]);
			}
		}

		$w = $this->getWH($svg, '/width=("|\')(.*?)\1/i');
		if( $w )
		{
			$h = $this->getWH($svg, '/height=("|\')(.*?)\1/i');
			if( $h )
			{
				$width = $w;
				$height = $h;
			}
		}

		$data = substr($data, $pos + 1, $end - $pos - 1);
		$data = preg_replace('/>\s+</', '><', $data);
		$data = preg_replace('/\s{2,}/', ' ', $data);
		$data = preg_replace('/\s\/>/', '/>', $data);

		return new SvgIcon($name, $data, $viewBox, $width, $height);
	}

	private function getWH( $number, $reg = false )
	{
		if( $reg )
		{
			$number = preg_match($reg, $number, $m);
			if( !$m )
			{
				return null;
			}
			$number = $number[2];
		}

		$number = preg_replace( '/px$/i', '', $number );
		if( is_numeric($number) && $number > 0 )
		{
			return (float) $number;
		}
		else
		{
			return null;
		}
	}
}
<?php
/**
 * Created by IntelliJ IDEA.
 * User: GoshaV [Maniako] <gosha@rozaverta.com>
 * Date: 22.09.2017
 * Time: 16:50
 */

namespace MD\VTools\Svg;

use EApp\Support\Interfaces\Arrayable;

class SvgIcon implements Arrayable
{
	protected $name;

	protected $data;

	protected $viewBox;

	protected $width;

	protected $height;

	public function __construct( $name, $data, $viewBox = null, $width = null, $height = null )
	{
		$this->name = $name;
		$this->data = $data;
		$this->viewBox = $viewBox;
		$this->width = $width;
		$this->height = $height;
	}

	public function getSvg( array $attr = [], $name = 'svg' )
	{
		return '<' . $name . $this->getAttr($attr) . '>' . $this->data . '</' . $name . '>';
	}

	public function getSvgUse( array $attr = [], $prefix = '' )
	{
		return '<svg' . $this->getAttr($attr) . '><use xlink:href="#' . $prefix . $this->name . '"></use></svg>';
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return mixed
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @return null|string
	 */
	public function getViewBox()
	{
		return $this->viewBox;
	}

	/**
	 * @return null|int
	 */
	public function getWidth()
	{
		return $this->width;
	}

	/**
	 * @return null|int
	 */
	public function getHeight()
	{
		return $this->height;
	}

	/**
	 * Get the instance as an array.
	 *
	 * @return array
	 */
	public function toArray()
	{
		$array = [
			"data" => $this->data
		];

		if( !is_null($this->viewBox) )
		{
			$array['viewBox'] = $this->viewBox;
		}

		if( !is_null($this->width) && !is_null($this->height) )
		{
			$array["width"] = $this->width;
			$array["height"] = $this->height;
		}

		return $array;
	}

	protected function getAttr( array $attr )
	{
		if( $this->viewBox )
		{
			$attr['viewBox'] = $this->viewBox;
		}

		$w = isset($attr['width']);
		$h = isset($attr['height']);
		$p = $this->width && $this->height ? $this->width / $this->height : 0;

		if( ! $w && ! $h )
		{
			if( $p > 0 )
			{
				$attr['width'] = $this->width;
				$attr['height'] = $this->height;
			}
		}
		else if( $p > 0 )
		{
			if( ! $w )
			{
				$attr['width'] = floor( $attr['height'] * $p * 100 ) / 100;
			}
			else
			{
				$attr['height'] = floor( $attr['width'] / $p * 100 ) / 100;
			}
		}

		$html_attr = '';
		foreach($attr as $key => $val)
		{
			$html_attr .= ' ' . $key . '="' . htmlspecialchars($val) . '"';
		}

		return $html_attr;
	}
}